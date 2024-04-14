<?php
// Проверяем, начата ли сессия
session_start();

// Проверяем, установлена ли сессия авторизации или регистрации
if (!isset($_SESSION['registered']) && !isset($_SESSION['authenticated'])) {
    // Если сессия не установлена, перенаправляем пользователя на страницу авторизации или регистрации
    header('Location: registration.php');
    exit(); // Завершаем выполнение скрипта после перенаправления
}

// Если пользователь нажимает на кнопку выхода, завершаем текущую сессию
if (isset($_POST['logout'])) {
    session_unset(); // Удаляем все переменные сессии
    session_destroy(); // Разрушаем сессию
    header('Location: registration.php'); // Перенаправляем пользователя на страницу авторизации или регистрации
    exit(); // Завершаем выполнение скрипта после перенаправления
}

$dbname = "event_platform"; 
require_once('./include/db.php');

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Запрос к базе данных для получения информации о мероприятиях
$sql = "SELECT * FROM events ORDER BY date";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Текущие мероприятия</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1>Текущие мероприятия</h1>
        <div class="events">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Формируем ссылку на страницу регистрации с передачей ID мероприятия и user_id через URL
                    echo "<div class='event'>";
                    echo "<h2><a href='event_reg.php?event_id=" . $row["id"] . "&user_id=" . $_SESSION['user_id'] . "'>" . $row["name"] . "</a></h2>";
                    echo "<p><strong>Дата:</strong> " . $row["date"] . "</p>";
                    echo "<p><strong>Цена:</strong> $" . $row["price"] . "</p>";
                    echo "<p><strong>Количество мест:</strong> " . $row["number_seats"] . "</p>";
                    // Добавим кнопку для регистрации на мероприятие
                    echo "<a href='event_reg.php?event_id=" . $row["id"] . "&user_id=" . $_SESSION['user_id'] . "' class='register-btn'>Зарегистрироваться</a>";
                    echo "</div>";
                }
            } else {
                echo "Нет текущих мероприятий.";
            }
            $conn->close();
            ?>
        </div>
    </div>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <button type="submit" name="logout">Выход</button>
    </form>
</body>

</html>
