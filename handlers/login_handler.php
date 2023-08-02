<?php
session_start();
require_once '../classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User();

    $result = $user->login($username, $password);

    if ($result) {
        header('Location: ../cabinet.php');
        exit();
    } else {
        header('Location: ../login.php?error=1');
        exit();
    }
}
