<?php
require 'config.php';
require 'generate_csrf_token.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    // CSRF Token validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Неверный CSRF токен.");
    }

    // Валидация данных
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $topic = filter_input(INPUT_POST, 'topic', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $attachment = $_FILES['attachment'];

    // Проверка валидности данных
    if (!$name || !$email || !$phone || !$topic || !$message) {
        die("Некорректные данные формы.");
    }

    // Подключение к базе данных
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    // Проверка подключения
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    // Сохранение данных в таблицу users
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $phone);
    $stmt->execute();
    $user_id = $stmt->insert_id;
    $stmt->close();

    // Получение id темы
    $stmt = $conn->prepare("SELECT id FROM topics WHERE topic_name = ?");
    $stmt->bind_param("s", $topic);
    $stmt->execute();
    $stmt->bind_result($topic_id);
    $stmt->fetch();
    $stmt->close();

    // Обработка прикрепленного файла
    $file_path = null;
    if ($attachment['size'] > 0) {
        $file_path = "uploads/" . basename($attachment["name"]);
        move_uploaded_file($attachment["tmp_name"], $file_path);
    }

    // Сохранение данных в таблицу feedback
    $stmt = $conn->prepare("INSERT INTO feedback (user_id, topic_id, message, attachment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $topic_id, $message, $file_path);
    $stmt->execute();
    $stmt->close();

    $conn->close();

    echo "Сообщение успешно отправлено.";
} else {
    echo "Некорректный метод запроса.";
}
?>
