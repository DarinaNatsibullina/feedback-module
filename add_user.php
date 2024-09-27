<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Перенаправляем на страницу авторизации
    exit();
}

// Настройки для подключения к базе данных
$servername = "localhost";
$username = "f1033603_univerproject"; 
$password = "ZBTh007B"; 
$dbname = "f1033603_univerproject"; 

// Подключение к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Обработка формы добавления пользователя
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $new_role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, role) VALUES ('$new_username', '$new_password', '$new_role')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Новый пользователь успешно добавлен.";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить пользователя</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <h1>Добавить пользователя</h1>
    <form method="POST" action="">
        <label for="username">Имя пользователя:</label>
        <input type="text" name="username" required>

        <label for="password">Пароль:</label>
        <input type="password" name="password" required>

        <label for="role">Роль:</label>
        <select name="role" required>
            <option value="operator">Оператор</option>
            <option value="marketer">Маркетолог</option>
            <option value="admin">Администратор</option>
        </select>

        <button type="submit">Создать пользователя</button>
    </form>
    <a href="admin.php" class="logout-button">Назад</a>
</body>
</html>

<?php
$conn->close();
?>
