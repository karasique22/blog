<?php
session_start();

require_once 'classes/User.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userID = $_SESSION['user_id'];

$user = new User();

$userInfo = $user->getUserInfo($userID);

$_SESSION['first_name'] = $userInfo['first_name'];
$_SESSION['last_name'] = $userInfo['last_name'];
$_SESSION['username'] = $userInfo['username'];

$posts = $user->getUserPosts($userID);
$posts = array_reverse($posts);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postTitle = $_POST['title'];
    $postContent = $_POST['content'];

    header('Location: cabinet.php');
    exit();
}

if (isset($_POST['edit_post_id'])) {
    $editPostID = $_POST['edit_post_id'];
    $editPostTitle = $_POST['edit_title'];
    $editPostContent = $_POST['edit_content'];

    header('Location: cabinet.php');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg border-bottom">
            <a class="navbar-brand ms-3" href="#">Личный кабинет</a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="nav-link">Привет, <?= $_SESSION['first_name'] ?> <?= $_SESSION['last_name'] ?> (<?= $_SESSION['username'] ?>)</span>
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
                <h5 class="card-title">Новый пост</h5>
                <form action="./handlers/create_handler.php" method="POST">
                    <div class="form-group">
                        <label for="title">Заголовок:</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Содержание:</label>
                        <textarea name="content" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Создать</button>
                </form>
            </div>
        </div>

        <hr>

        <h1 class="display-5 text-center my-3">Ваши посты</h1>
        <?php foreach ($posts as $post) : ?>
            <div class="post card mb-3">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted"><?= $post['created_at'] ?></h6>
                    <h4 class="card-title"><?= $post['title'] ?></h4>
                    <p class="card-text"><?= $post['content'] ?></p>
                    <a href="edit_post.php?post_id=<?= $post['id'] ?>" class="btn btn-primary">Редактировать</a>
                    <form action="handlers/delete_handler.php" method="POST" style="display:inline;">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <button type="submit" class="btn btn-danger">Удалить</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>