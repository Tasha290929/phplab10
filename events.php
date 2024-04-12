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

            $dbname = "event_platform"; // Имя вашей базы данных
            require_once('./include/db.php');

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Запрос к базе данных для получения информации о мероприятиях
            $sql = "SELECT * FROM events ORDER BY date";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                   // Формируем ссылку на страницу регистрации с передачей ID мероприятия через URL
                   echo "<div class='event'>";
                   echo "<h2><a href='event_reg.php?event_id=" . $row["id"] . "'>" . $row["name"] . "</a></h2>";
                   echo "<p><strong>Дата:</strong> " . $row["date"] . "</p>";
                   echo "<p><strong>Цена:</strong> $" . $row["price"] . "</p>";
                   echo "<p><strong>Количество мест:</strong> " . $row["number_seats"] . "</p>";
                   // Добавим кнопку для регистрации на мероприятие
                   echo "<a href='event_reg.php?event_id=" . $row["id"] . "' class='register-btn'>Зарегистрироваться</a>";
                   echo "</div>";
                   
                }
            } else {
                echo "Нет текущих мероприятий.";
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
