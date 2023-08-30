<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postId'])) {
    $postIdToDelete = $_POST['postId'];
    $blogData = file_exists('blog.json') ? json_decode(file_get_contents('blog.json'), true) : array();
    if (isset($blogData[$postIdToDelete])) {
        unset($blogData[$postIdToDelete]);
        file_put_contents('blog.json', json_encode($blogData, JSON_PRETTY_PRINT));
        header('Location: dashboard.php');
        exit;
    } else {
        header('Location: dashboard.php');
        exit;
    }
} else {
    header('Location: dashboard.php');
    exit;
}
