<?php
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

    $to = "university@example.com"; // Замените на ваш email
    $subject = "Новое сообщение с обратной связи";
    $body = "Имя: $name\nEmail: $email\nТелефон: $phone\nТема: $topic\nСообщение:\n$message";

    // Обработка прикрепленного файла
    if ($attachment['size'] > 0) {
        $file_path = "uploads/" . basename($attachment["name"]);
        move_uploaded_file($attachment["tmp_name"], $file_path);
        $body .= "\nПрикрепленный файл: " . $file_path;
    }

    $headers = "From: $email";

    // Отправка email
    if (mail($to, $subject, $body, $headers)) {
        echo "Сообщение успешно отправлено.";
    } else {
        echo "Ошибка при отправке сообщения.";
    }
} else {
    echo "Некорректный метод запроса.";
}
?>
