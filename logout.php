<?php
session_start();
session_unset(); // Удалить все переменные сессии
session_destroy(); // Удалить сессию
header('Location: login.php'); // Перенаправление на страницу входа
exit;
?>
