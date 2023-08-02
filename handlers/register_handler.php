<?php
require_once '../classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];

    $user = new User();

    $result = $user->register($username, $password, $firstName, $lastName);

    if ($result) {
        header('Location: ../cabinet.php');
        exit();
    } else {
        header('Location: ../register.php?error=1');
        exit();
    }
}
