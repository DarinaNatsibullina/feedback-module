<?php
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

// Запрос для получения пользователей, давших согласие на рассылку
$sql = "SELECT name, surname, email FROM feedback WHERE newsletter = 1";
$result = $conn->query($sql);

// Заголовки для скачивания файла
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="subscribers.csv"');

// Создание CSV-вывода
$output = fopen('php://output', 'w');
fputcsv($output, ['Имя', 'Фамилия', 'Email']); // Заголовки столбцов

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [$row['name'], $row['surname'], $row['email']]); // Запись строки в CSV
    }
} else {
    // Если нет данных, можно записать пустую строку или оставить файл пустым
    fputcsv($output, ['Нет данных']);
}

fclose($output);
exit();
