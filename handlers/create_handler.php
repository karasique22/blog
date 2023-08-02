<?php
session_start();
require_once '../classes/Blog.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit();
    }

    $title = $_POST['title'];
    $content = $_POST['content'];

    $userID = $_SESSION['user_id'];

    $blog = new Blog();

    $result = $blog->createPost($userID, $title, $content);

    if ($result) {
        header('Location: ../cabinet.php');
        exit();
    } else {
        header('Location: ../cabinet.php?error=create');
        exit();
    }
} else {
    header('Location: ../cabinet.php?error=create');
    exit();
}
?>
