<?php

require_once __DIR__ . '/classes/Db.php';

try {
    $db = Db::getInstance();
    $pdo = $db->getConnection();

    $query = "DELETE FROM movies WHERE id = {$_POST['id']}";
    $query = $pdo->prepare($query);
    $query->execute();

    header('location: /index.php');
} catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}