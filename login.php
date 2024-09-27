<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: admin.php"); // Если уже авторизованы, перенаправляем на админку
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Подключение к базе данных
    $servername = "localhost"; 
    $username = "f1033603_univerproject"; 
    $password = "ZBTh007B"; 
    $dbname = "f1033603_univerproject"; 

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    $login_username = htmlspecialchars(trim($_POST['username']));
    $login_password = htmlspecialchars(trim($_POST['password']));

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $login_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($login_password, $user['password'])) {
            $_SESSION['username'] = $login_username;
            $_SESSION['role'] = $user['role'];
            header("Location: admin.php");
            exit();
        } else {
            $error = "Неправильный пароль.";
        }
    } else {
        $error = "Пользователь не найден.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <img src="img/logo-muiv.svg" alt="Логотип" class="logo">
    </header>
    <div class="background">
        <h1>Добро пожаловать!</h1>
        <p>Пожалуйста, войдите в свою учетную запись.</p>

        <div class="formcontainer">
            <h1>Авторизация</h1>
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="POST" action="">
                <div class="input-group">
                    <input type="text" name="username" required placeholder="Имя пользователя">
                </div>
                <div class="input-group">
                    <input type="password" name="password" required placeholder="Пароль">
                </div>
                <button type="submit">Войти</button>
            </form>
        </div>
    </div>
</body>
</html>