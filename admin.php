<?php
session_start();

$dbname = "event_platform"; // Имя вашей базы данных
require_once('./include/db.php');

if (!isset($_SESSION['authenticated']) && $_SESSION['role_id'] == 2) {

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

// Обработка добавления мероприятия
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_event') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $number_seats = $_POST['number_seats'];
    $date = $_POST['date'];

    // Валидация и обработка данных (можно добавить дополнительные проверки)

    // SQL-запрос для добавления мероприятия в базу данных
    $sql = "INSERT INTO events (name, price, number_seats, date) VALUES ('$name', '$price', '$number_seats', '$date')";

    if ($conn->query($sql) === TRUE) {
        echo "Мероприятие успешно добавлено";
    } else {
        echo "Ошибка при добавлении мероприятия: " . $conn->error;
    }
}

// Обработка удаления мероприятия
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event'])) {
    $event_id = $_POST['event_id'];

    // SQL-запрос для удаления мероприятия из базы данных
    $sql = "DELETE FROM events WHERE id = $event_id";

    if ($conn->query($sql) === TRUE) {
        echo "Мероприятие успешно удалено";
    } else {
        echo "Ошибка при удалении мероприятия: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Административная панель</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1>Административная панель</h1>

        <!-- Форма для добавления мероприятия -->
        <h2>Добавить мероприятие</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div>
                <label for="name">Название мероприятия:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="price">Цена:</label>
                <input type="number" id="price" name="price" required>
            </div>
            <div>
                <label for="number_seats">Количество мест:</label>
                <input type="number" id="number_seats" name="number_seats" required>
            </div>
            <div>
                <label for="date">Дата и время:</label>
                <input type="datetime-local" id="date" name="date" required>
            </div>
            <input type="hidden" name="action" value="add_event">
            <button type="submit">Добавить</button>
        </form>

        <!-- Таблица для отображения списка мероприятий -->
        <h2>Список мероприятий</h2>
        <table>
            <tr>
                <th>Название</th>
                <th>Цена</th>
                <th>Количество мест</th>
                <th>Дата и время</th>
                <th>Действия</th>
            </tr>
            <?php
            // SQL-запрос для получения списка мероприятий
            $sql = "SELECT * FROM events";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>$" . $row['price'] . "</td>";
                    echo "<td>" . $row['number_seats'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td><form action='" . $_SERVER['PHP_SELF'] . "' method='post'>";
                    echo "<input type='hidden' name='event_id' value='" . $row['id'] . "'>";
                    echo "<button type='submit' name='delete_event'>Удалить</button>";
                    echo "</form></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Нет мероприятий</td></tr>";
            }
            ?>
        </table>
    </div>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <button type="submit" name="logout">Выход</button>
    </form>
</body>

</html>
