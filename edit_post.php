<?php
session_start();

require_once 'classes/User.php';
require_once 'classes/Blog.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['post_id'])) {
    header('Location: cabinet.php');
    exit();
}

$userID = $_SESSION['user_id'];

$user = new User();
$blog = new Blog();

$userInfo = $user->getUserInfo($userID);
$_SESSION['first_name'] = $userInfo['first_name'];
$_SESSION['last_name'] = $userInfo['last_name'];

$postID = $_GET['post_id'];
$post = $blog->getPost($postID);

if ($post['user_id'] != $userID) {
    header('Location: cabinet.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $editedPostID = $_POST['post_id'];
    $editedPostTitle = $_POST['title'];
    $editedPostContent = $_POST['content'];

    $blog->editPost($editedPostID, $editedPostTitle, $editedPostContent);

    header('Location: cabinet.php');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Редактирование поста</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand ms-3" href="#">Личный кабинет</a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="nav-link">Привет, <?= $_SESSION['first_name'] ?> <?= $_SESSION['last_name'] ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">На главную</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./handlers/logout_handler.php">Выйти из аккаунта</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Редактирование поста</h5>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="title">Заголовок:</label>
                        <input type="text" name="title" class="form-control" required value="<?= $post['title'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="content">Содержание:</label>
                        <textarea name="content" class="form-control" required><?= $post['content'] ?></textarea>
                    </div>
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <button type="submit" class="btn btn-primary mt-3">Сохранить</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>