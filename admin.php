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

// Получение всех сообщений
$sql = "SELECT * FROM feedback ORDER BY created_at DESC";
$result = $conn->query($sql);

// Массив тем для отображения
$subject_labels = [
    "admission" => "Заявка на поступление",
    "exams" => "Приемная комиссия и вступительные экзамены",
    "programs" => "Образовательные программы",
    "finance" => "Финансовые вопросы и стипендии",
    "university_life" => "Жизнь в университете",
    "administrative" => "Административные вопросы",
    "international_students" => "Международные студенты",
    "other" => "Другие вопросы"
];

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Перенаправляем на страницу авторизации
    exit();
}

// Проверка роли пользователя
$user_role = $_SESSION['role'];
$show_analytics = in_array($user_role, ['marketer', 'admin']); // Доступ к аналитике для маркетолога и администратора
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администраторская панель</title>
    <link rel="stylesheet" href="admin_styles.css"> <!-- Подключаем отдельный CSS для админки -->
    <link href="https://fonts.googleapis.com/css2?family=Proxima+Nova:wght@600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Администраторская панель</h1>
        <?php if ($_SESSION['role'] === 'admin'): // Проверка роли пользователя ?>
            <a href="add_user.php" class="btn">Добавить пользователя</a> <!-- Ссылка для добавления пользователя -->
        <?php endif; ?>
        <h2>Входящие сообщения</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Фамилия</th>
                <th>Email</th>
                <th>Тема</th>
                <th>Сообщение</th>
                <th>Дата</th>
                <th>Файл</th>
                <th>Ответ</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['surname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";

                    // Отображение темы обращения
                    $subject_display = isset($subject_labels[$row['subject']]) ? $subject_labels[$row['subject']] : "Неизвестная тема";
                    echo "<td>" . htmlspecialchars($subject_display) . "</td>";

                    echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";

                    // Проверяем наличие файла
                    if ($row['file_name']) {
                        echo "<td><a href='uploads/" . htmlspecialchars($row['file_name']) . "' download>Скачать файл</a></td>";
                    } else {
                        echo "<td>Нет файла</td>";
                    }

                    // Измененная строка для ответа
                    echo "<td><a href='mailto:" . htmlspecialchars($row['email']) . "?subject=" . urlencode($subject_display) . "'>Ответить</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>Нет сообщений</td></tr>";
            }
            ?>
        </table>

        <?php if ($show_analytics): // Проверка доступа к аналитике ?>
            <h2>Аналитика</h2>
            <div class="analytics">
                <?php
                // Получаем общее количество сообщений
                $sql_count = "SELECT COUNT(*) AS total FROM feedback";
                $result_count = $conn->query($sql_count);
                $data_count = $result_count->fetch_assoc();

                // Анализ по датам
                $sql_analytics = "SELECT DATE(created_at) AS date, COUNT(*) AS count FROM feedback GROUP BY DATE(created_at)";
                $result_analytics = $conn->query($sql_analytics);

                // Анализ по темам
                $sql_subject_analytics = "SELECT subject, COUNT(*) AS count FROM feedback GROUP BY subject";
                $result_subject_analytics = $conn->query($sql_subject_analytics);

                // Анализ по дням недели
                $sql_weekday_analytics = "
                    SELECT DAYOFWEEK(created_at) AS weekday, COUNT(*) AS count 
                    FROM feedback 
                    GROUP BY DAYOFWEEK(created_at)
                    ORDER BY DAYOFWEEK(created_at);
                ";
                $result_weekday_analytics = $conn->query($sql_weekday_analytics);

                // Массив дней недели на русском
                $days_of_week = [
                    1 => 'Воскресенье',
                    2 => 'Понедельник',
                    3 => 'Вторник',
                    4 => 'Среда',
                    5 => 'Четверг',
                    6 => 'Пятница',
                    7 => 'Суббота'
                ];

                echo "<p>Всего сообщений: " . $data_count['total'] . "</p>";
                echo "<h3>Сообщения по датам:</h3>";
                echo "<ul>";
                if ($result_analytics->num_rows > 0) {
                    while ($row = $result_analytics->fetch_assoc()) {
                        echo "<li>" . $row['date'] . ": " . $row['count'] . " сообщений</li>";
                    }
                } else {
                    echo "<li>Нет данных</li>";
                }
                echo "</ul>";

                // Вывод количества сообщений по темам
                echo "<h3>Сообщения по темам:</h3>";
                echo "<ul>";
                if ($result_subject_analytics->num_rows > 0) {
                    while ($row = $result_subject_analytics->fetch_assoc()) {
                        // Отображение темы обращения
                        $subject_display = isset($subject_labels[$row['subject']]) ? $subject_labels[$row['subject']] : "Неизвестная тема";
                        echo "<li>" . htmlspecialchars($subject_display) . ": " . $row['count'] . " сообщений</li>";
                    }
                } else {
                    echo "<li>Нет данных</li>";
                }
                echo "</ul>";

                // Вывод количества сообщений по дням недели
                echo "<h3>Сообщения по дням недели:</h3>";
                echo "<ul>";
                if ($result_weekday_analytics->num_rows > 0) {
                    while ($row = $result_weekday_analytics->fetch_assoc()) {
                        // Преобразуем номер дня в название дня на русском
                        $weekday_number = $row['weekday'];
                        $weekday_name = $days_of_week[$weekday_number];
                        echo "<li>" . htmlspecialchars($weekday_name) . ": " . $row['count'] . " сообщений</li>";
                    }
                } else {
                    echo "<li>Нет данных</li>";
                }
                echo "</ul>";
                ?>
            </div>
        <?php endif; // Конец проверки доступа к аналитике ?>

        <!-- Кнопка выхода -->
        <a href="logout.php" class="btn">Выйти</a>
        
    </div>
</body>
</html>

<?php
$conn->close();
?>