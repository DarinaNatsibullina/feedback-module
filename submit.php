<?php
// Настройки для подключения к базе данных
$servername = "localhost"; // Обычно это 'localhost'
$username = "f1033603_univerproject"; // Имя пользователя базы данных
$password = "ZBTh007B"; // Пароль
$dbname = "f1033603_univerproject"; // Имя базы данных

// Подключение к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Обработка формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $surname = htmlspecialchars(trim($_POST['surname']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : null; // Получаем телефон, если он есть

    // Проверка на пустые поля
    if (empty($name) || empty($surname) || empty($subject) || empty($email) || empty($message)) {
        echo "Все поля обязательны для заполнения.";
        exit;
    }

    // Проверка формата email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Некорректный адрес электронной почты.";
        exit;
    }

    // Обработка загруженного файла (если он есть)
    $fileName = null; // Изначально имя файла равно null
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];

        // Указываем директорию для загрузки
        $filePath = 'uploads/' . basename($fileName);
        if (!move_uploaded_file($fileTmpName, $filePath)) {
            echo "Ошибка при загрузке файла.";
            exit;
        }
    }

    // Подготовка SQL-запроса с учетом имени, фамилии, темы обращения и телефона
    $stmt = $conn->prepare("INSERT INTO feedback (name, surname, subject, email, message, phone, file_name) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $surname, $subject, $email, $message, $phone, $fileName);

    // Выполнение запроса
    if ($stmt->execute()) {
        echo "Сообщение успешно сохранено!";
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
