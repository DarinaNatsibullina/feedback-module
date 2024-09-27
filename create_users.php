<?php
// Подключение к базе данных
$servername = "localhost";
$username = "f1033603_univerproject";
$password = "ZBTh007B";
$dbname = "f1033603_univerproject";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Пользователи для добавления
$users = [
    ['username' => 'operator1', 'password' => 'password123', 'role' => 'operator'],
    ['username' => 'marketer1', 'password' => 'password123', 'role' => 'marketer'],
    ['username' => 'admin1', 'password' => 'password123', 'role' => 'admin']
];

// Подготовка и привязка
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hashed_password, $role);

foreach ($users as $user) {
    $username = $user['username'];
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT); // Хеширование пароля
    $role = $user['role'];

    if ($stmt->execute()) {
        echo "Пользователь {$username} успешно добавлен.<br>";
    } else {
        echo "Ошибка добавления пользователя {$username}: " . $stmt->error . "<br>";
    }
}

$stmt->close();
$conn->close();
?>
