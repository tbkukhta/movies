<?php

require_once __DIR__ . '/classes/Db.php';

$movies = [];
$file = $_FILES['file'];

if ($file['size'] != 0 && $file['error'] != 4) {
    $fileContents = file_get_contents($file['tmp_name']);
    $moviesArray = preg_split('/(\s)(?=Title:)/', $fileContents);
    foreach ($moviesArray as $key => $value) {
        preg_match('/(?<=Title:\s)([\s\S]+)(?=\sRelease Year:)/', $value, $title);
        $movies[$key]['title'] = $title[0];
        preg_match('/(?<=Release Year:\s)([\s\S]+)(?=\sFormat:)/', $value, $year);
        $movies[$key]['year'] = $year[0];
        preg_match('/(?<=Format:\s)([\s\S]+)(?=\sStars:)/', $value, $format);
        $movies[$key]['format'] = $format[0];
        preg_match('/(?<=Stars:\s)([\s\S]+)/', $value, $stars);
        $movies[$key]['stars'] = $stars[0];
    }
    foreach ($movies as $movie) {
        if (empty(array_filter($movie))) {
            trigger_error('Загружаемый файл неправильного формата!', E_USER_ERROR);
        }
    }
} elseif (!empty($_POST)) {
    $movies[0] = [
        'title' => trim($_POST['title']),
        'year' => trim($_POST['year']),
        'format' => $_POST['format'],
        'stars' => $_POST['stars']
    ];
} else {
    trigger_error('Загружаемый файл не содержит данных!', E_USER_ERROR);
}

try {
    $db = Db::getInstance();
    $pdo = $db->getConnection();

    foreach ($movies as $movie) {
        $query = "
            SELECT s.name FROM stars s
            JOIN movies_stars ms ON (s.id = ms.star_id)
            JOIN movies m ON (ms.movie_id = m.id)
            WHERE m.title = '{$movie['title']}'
            AND m.year = '{$movie['year']}'
            AND m.format = '{$movie['format']}'
        ";
        $query = $pdo->query($query);
        $starsDb = $query->fetchAll();
        $stars = array_map('trim', explode(',', $movie['stars']));
        if (!empty($starsDb)) {
            array_walk($starsDb, function (&$value) {
                $value = $value['name'];
            });
            $diff = array_merge(array_diff($stars, $starsDb), array_diff($starsDb, $stars));
            if (empty($diff)) {
                trigger_error('Фильм "'. $movie['title'] . '" с идентичными даннымы уже был добавлен!');
                $errorTriggered = true;
                continue;
            }
        }

        $query = "INSERT INTO movies VALUES (DEFAULT, :title, :year, :format)";
        $query = $pdo->prepare($query);
        $query->execute(['title' => $movie['title'], 'year' => $movie['year'], 'format' => $movie['format']]);
        $movieId = $pdo->lastInsertId();

        foreach ($stars as $star) {
            $query = "INSERT IGNORE INTO stars VALUES (DEFAULT, :name)";
            $query = $pdo->prepare($query);
            $query->execute(['name' => $star]);

            $query = "SELECT id FROM stars WHERE name = '{$star}'";
            $query = $pdo->query($query);
            $starId = $query->fetchColumn();

            $query = "SELECT * FROM movies_stars WHERE movie_id = '{$movieId}' AND star_id = '{$starId}'";
            $query = $pdo->query($query);
            $record = $query->fetch();
            if ($record) {
                trigger_error('Актёр "'. $star . '" уже присутствует в фильме "'. $movie['title'] . '"!');
                $errorTriggered = true;
                continue;
            }

            $query = "INSERT INTO movies_stars VALUES (:movie_id, :star_id)";
            $query = $pdo->prepare($query);
            $query->execute(['movie_id' => $movieId, 'star_id' => $starId]);
        }
    }

    if (!isset($errorTriggered)) {
        header('location: /index.php');
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}