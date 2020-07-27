<?php

require_once __DIR__ . '/classes/Db.php';
require_once __DIR__ . '/classes/Config.php';
require_once __DIR__ . '/classes/UrlHelper.php';

$title = isset($_GET['title']) ? trim($_GET['title']) : '';
$star = isset($_GET['star']) ? trim($_GET['star']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$page = isset($_GET['page']) ? $_GET['page'] : '1';
$recordsPerPage = Config::$params['recordsPerPage'];
$offset = ($page - 1) * $recordsPerPage;
$limit = "LIMIT $offset, $recordsPerPage";

if ($title !== '') {
    $query = "SELECT * FROM movies WHERE title like '%{$title}%'";
} elseif ($star !== '') {
    $query = "
        SELECT m.* FROM movies m
        JOIN movies_stars ms ON (m.id = ms.movie_id)
        JOIN stars s ON (ms.star_id = s.id)
        WHERE s.name like '%{$star}%'
    ";
} else {
    $query = "SELECT * FROM movies";
}
$query .= " ORDER BY {$sort}";

try {
    $db = Db::getInstance();
    $pdo = $db->getConnection();

    $queryLimit = $pdo->query($query . " " . $limit);
    $movies = $queryLimit->fetchAll();

    $query = $pdo->query($query);
    $recordsTotal = count($query->fetchAll());
    $pagesTotal = ceil($recordsTotal / $recordsPerPage);

    foreach ($movies as &$movie) {
        $query = "
            SELECT s.name FROM stars s
            JOIN movies_stars ms ON (s.id = ms.star_id)
            JOIN movies m ON (ms.movie_id = m.id)
            WHERE m.id = {$movie['id']}
        ";
        $query = $pdo->query($query);
        $movie['stars'] = $query->fetchAll();
    }
    unset($movie);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}