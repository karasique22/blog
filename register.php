<!DOCTYPE html>
<html>

<head>
    <title>Регистрация</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <div class="container col-3 mt-4">
        <h2>Регистрация</h2>

        <?php
        if (isset($_GET['error']) && $_GET['error'] == 1) {
            echo '<div class="alert alert-danger" role="alert">Ошибка регистрации. Пользователь с таким никнеймом уже зарегистрирован.</div>';
        }
        ?>

        <form action="handlers/register_handler.php" method="POST">
            <div class="form-group">
                <label for="username">Никнейм:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="first_name">Имя:</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="last_name">Фамилия:</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="form-group mt-4 text-center">
                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                <a class="btn btn-outline-primary" href="login.php">Вход</a>
                <div class="mt-2">
                    <span class="text-muted">Перейти на </span> 
                    <a class="text-decoration-none" href="index.php">главную</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>