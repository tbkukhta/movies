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

$query = "
    SELECT COUNT(*) OVER() as count, m.*, 
    (SELECT GROUP_CONCAT(st.name SEPARATOR ', ') as name from stars st
    JOIN movies_stars mvst ON (st.id = mvst.star_id)
    JOIN movies mv ON (mvst.movie_id = mv.id)
    WHERE m.id = mv.id) as stars
    FROM movies m
";

if ($title !== '') {
    $query .= " WHERE m.title like '%{$title}%'";
} elseif ($star !== '') {
    $query .= "
        JOIN movies_stars ms ON (m.id = ms.movie_id)
        JOIN stars s ON (ms.star_id = s.id)
        WHERE s.name like '%{$star}%'
    ";
}
$query .= " ORDER BY {$sort} LIMIT $offset, $recordsPerPage";

try {
    $db = Db::getInstance();
    $pdo = $db->getConnection();

    $query = $pdo->query($query);
    $movies = $query->fetchAll();
    $recordsTotal = $movies[0]['count'];
    $pagesTotal = ceil($recordsTotal / $recordsPerPage);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}