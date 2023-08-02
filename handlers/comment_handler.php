<?php
session_start();
require_once '../classes/User.php';

$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];

    $user->addComment($post_id, $user_id, $content);

    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];
    $user->deleteComment($comment_id);

    header('Location: ../index.php');
    exit();
}
