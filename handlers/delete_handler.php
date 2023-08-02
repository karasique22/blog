<?php
session_start();
require_once '../classes/Blog.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit();
    }

    if (isset($_POST['post_id'])) {
        $postId = $_POST['post_id'];

        $blog = new Blog();

        $result = $blog->deletePost($postId);

        if ($result) {
            header('Location: ../cabinet.php?success=delete');
            exit();
        } else {
            header('Location: ../cabinet.php?error=delete');
            exit();
        }
    } else {
        header('Location: ../cabinet.php?error=delete');
        exit();
    }
} else {
    header('Location: ../cabinet.php?error=delete');
    exit();
}
