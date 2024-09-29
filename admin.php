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

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Перенаправляем на страницу авторизации
    exit();
}

// Проверка роли пользователя
$user_role = $_SESSION['role'];
$show_analytics = in_array($user_role, ['marketer', 'admin']); // Доступ к аналитике для маркетолога и администратора

// Установка переменных для пагинации
$limit = 5; // Количество сообщений на странице
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Получение всех сообщений с пагинацией
$sql = "SELECT * FROM feedback ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Получаем общее количество сообщений для расчета количества страниц
$sql_count = "SELECT COUNT(*) AS total FROM feedback";
$result_count = $conn->query($sql_count);
$data_count = $result_count->fetch_assoc();
$total_messages = $data_count['total'];
$total_pages = ceil($total_messages / $limit); // Общее количество страниц

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
                <th>Дали согласие на рассылку</th> <!-- Новый заголовок -->
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

                    // Проверяем поле newsletter и отображаем "Да" или "Нет"
                    echo "<td>" . ($row['newsletter'] ? 'Да' : 'Нет') . "</td>"; // Новый столбец для согласия на рассылку

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>Нет сообщений</td></tr>"; // Обновляем количество столбцов
            }
            ?>
        </table>

        <!-- Пагинация -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">&laquo; Назад</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo ($i === $page) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Вперёд &raquo;</a>
            <?php endif; ?>
        </div>
<!-- Кнопка для выгрузки списка пользователей, давших согласие на рассылку, доступна только администраторам и маркетологам -->
<?php if (in_array($user_role, ['admin', 'marketer'])): ?>
    <a href="export_newsletter.php" class="btn">Выгрузить список подписчиков на рассылку</a>
<?php endif; ?>

        <?php if ($show_analytics): // Проверка доступа к аналитике ?>
            <h2>Аналитика</h2>
            <div class="analytics">
                <?php
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

                echo "<p>Всего сообщений: " . $total_messages . "</p>";
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
                        $day_name = $days_of_week[$row['weekday']];
                        echo "<li>" . $day_name . ": " . $row['count'] . " сообщений</li>";
                    }
                } else {
                    echo "<li>Нет данных</li>";
                }
                echo "</ul>";
                ?>
            </div>
        <?php endif; ?>
        <!-- Кнопка выхода -->
        <a href="logout.php" class="btn">Выйти</a>
    </div>
</body>
</html>

<?php
$conn->close(); // Закрываем соединение с базой данных
?>
