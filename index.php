<?php
session_start();
require_once __DIR__ . '/classes/User.php';

$user = new User();
$posts = $user->getAllPosts();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];

    $user->addComment($post_id, $user_id, $content);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];
    $user->deleteComment($comment_id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_comment'])) {
    $comment_id = $_POST['comment_id'];
    $content = $_POST['edited_content'];

    $user->editComment($comment_id, $content);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Фасебук</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <?php if (isset($_GET['logout'])) : ?>
        <div class="alert alert-success m-0" role="alert">Вы успешно вышли из аккаунта.</div>
    <?php endif; ?>
    <div class="container">
        <nav class="navbar navbar-expand-lg border-bottom">
            <a class="navbar-brand" href="#">
                <img src="vk.svg" alt="" height="50px">
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="cabinet.php">Личный кабинет</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Личный кабинет</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <h1 class="display-4 text-center my-3">Лента</h1>

        <?php
        foreach ($posts as $post) {
            $bloggerInfo = $user->getUserInfo($post['user_id']);
            $bloggerNickname = $bloggerInfo['username'];
            $bloggerFirstName = $bloggerInfo['first_name'];
            $bloggerSecondName = $bloggerInfo['last_name'];
        ?>

            <div class="card mb-3 col-8 offset-2 mb-5">
                <div class="card-header"><?= $bloggerNickname ?> (<?= $bloggerFirstName ?> <?= $bloggerSecondName ?>), <?= $post['created_at'] ?></div>
                <div class="card-body">
                    <div class="text-justify mb-3">
                        <h4 class="card-title"><?= $post['title'] ?></h4>
                        <p class="card-text mb-4"><?= $post['content'] ?></p>
                    </div>

                    <h6>Комментарии</h6>
                    <?php
                    // Выводим комментарии для текущего поста
                    $comments = $user->getCommentsForPost($post['id']);
                    if (empty($comments)) {
                        echo "<p>Комментариев пока нет</p>";
                    }
                    foreach ($comments as $comment) {
                    ?>
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="comment-buttons">
                                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']) : ?>
                                        <form method="POST" action="" class="float-end">
                                            <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                            <button type="submit" name="delete_comment" class="btn btn-light">
                                                <i class="fas fa-trash-alt btn-sm"></i>
                                            </button>
                                        </form>
                                        <div class="btn-group float-end">
                                            <button type="button" class="btn btn-light" onclick="toggleEditForm(<?= $comment['id'] ?>)">
                                                <i class="fas fa-edit btn-sm"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <h6 class="card-subtitle mb-2 text-muted">
                                    <?= $comment['username'] ?><?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']) {
                                        echo ' (вы)';
                                    } ?>, <?= $comment['created_at'] ?>
                                </h6>
                                <p class="card-text mb-0"><?= $comment['content'] ?></p>

                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']) : ?>
                                    <div id="edit-form-<?= $comment['id'] ?>" style="display: none;">
                                        <form method="POST" action="" class='mt-2 mb-0'>
                                            <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                            <textarea name="edited_content" class="form-control" rows="3" required><?= $comment['content'] ?></textarea>
                                            <button type="submit" name="edit_comment" class="btn btn-sm btn-primary mt-2">Сохранить</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    <?php
                    }
                    ?>

                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <form method="POST" action="handlers/comment_handler.php">
                            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                            <div class="form-group">
                                <textarea name="content" class="form-control" rows="3" placeholder="Оставьте комментарий..." required></textarea>
                            </div>
                            <button type="submit" name="comment" class="btn btn-primary mt-2">Отправить комментарий</button>
                        </form>
                    <?php else : ?>
                        <p class="text-center mt-4">Авторизуйтесь, чтобы оставить комментарий.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php
        }
        ?>
    </div>

    <script>
        function toggleEditForm(commentId) {
            const editForm = document.getElementById('edit-form-' + commentId);
            editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
        }
    </script>

    <script src="js/bootstrap.min.js"></script>

</body>

</html>