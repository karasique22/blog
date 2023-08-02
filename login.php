<!DOCTYPE html>
<html>

<head>
    <title>Авторизация</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <div class="container col-3 mt-4">
        <h2>Авторизация</h2>

        <?php if (isset($_GET['error']) && $_GET['error'] == 1) : ?>
            <div class="alert alert-danger" role="alert">Неверное имя пользователя или пароль.</div>
        <?php endif; ?>

        <form action="handlers/login_handler.php" method="POST">
            <div class="form-group">
                <label for="username">Никнейм:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group mt-4 text-center">
                <button type="submit" class="btn btn-primary">Войти</button>
                <a class="btn btn-outline-primary" href="register.php">Зарегистрироваться</a>
                <div class="mt-2">
                    <span class="text-muted">Перейти на </span> 
                    <a class="text-decoration-none" href="index.php">главную</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>