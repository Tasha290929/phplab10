<?php
// Проверка авторизации пользователя
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== 'manager') {
    header("Location: login.php"); // Перенаправление на страницу авторизации
    exit;
}

// Подключение к базе данных
include_once 'db_connect.php'; // Файл с настройками подключения к БД

// Добавление мероприятия
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    
    // SQL запрос для добавления мероприятия в базу данных
    $sql = "INSERT INTO events (name, date, description) VALUES ('$name', '$date', '$description')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Мероприятие успешно добавлено";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}

// Изменение мероприятия
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_event'])) {
    $event_id = $_POST['event_id'];
    $name = $_POST['name'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    
    // SQL запрос для изменения мероприятия в базе данных
    $sql = "UPDATE events SET name='$name', date='$date', description='$description' WHERE id=$event_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Мероприятие успешно обновлено";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}

// Получение списка мероприятий
$sql_events = "SELECT * FROM events";
$result_events = $conn->query($sql_events);

// Получение списка зарегистрированных пользователей на мероприятия
$sql_registrations = "SELECT * FROM registrations";
$result_registrations = $conn->query($sql_registrations);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Административная панель</title>
</head>
<body>
    <h1>Добавить мероприятие</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Название: <input type="text" name="name"><br>
        Дата: <input type="date" name="date"><br>
        Описание: <textarea name="description"></textarea><br>
        <input type="submit" name="add_event" value="Добавить">
    </form>
    
    <h1>Изменить мероприятие</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Выберите мероприятие:
        <select name="event_id">
            <?php
            if ($result_events->num_rows > 0) {
                while($row = $result_events->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                }
            }
            ?>
        </select><br>
        Название: <input type="text" name="name"><br>
        Дата: <input type="date" name="date"><br>
        Описание: <textarea name="description"></textarea><br>
        <input type="submit" name="update_event" value="Изменить">
    </form>
    
    <h1>Зарегистрированные на мероприятия</h1>
    <table border="1">
        <tr>
            <th>Имя</th>
            <th>Мероприятие</th>
        </tr>
        <?php
        if ($result_registrations->num_rows > 0) {
            while($row = $result_registrations->fetch_assoc()) {
                echo "<tr><td>" . $row["name"] . "</td><td>" . $row["event_name"] . "</td></tr>";
            }
        }
        ?>
    </table>
</body>
</html>
