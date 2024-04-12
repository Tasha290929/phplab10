<?php
// Подключение к базе данных
$servername = "localhost";
$username = "root"; // Имя пользователя базы данных
$password = ""; // Пароль базы данных
$dbname = "event_platform"; // Имя вашей базы данных

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получение данных из формы
$name = $_POST['name'];
$user_id = $_POST['user_id'];
$event_id = $_POST['event_id'];

// SQL-запросы для проверки существования пользователя и мероприятия
$user_check_sql = "SELECT * FROM User WHERE id = '$user_id'";
$event_check_sql = "SELECT * FROM events WHERE id = '$event_id'";

$user_result = $conn->query($user_check_sql);
$event_result = $conn->query($event_check_sql);

// Проверка существования пользователя и мероприятия
if ($user_result->num_rows > 0 && $event_result->num_rows > 0) {
    // Если пользователь и мероприятие существуют, добавляем запись о регистрации
    $sql = "INSERT INTO event_records (user_id, event_id) VALUES ('$user_id', '$event_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Регистрация успешно завершена.";
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Если пользователь или мероприятие не существуют, выводим сообщение об ошибке
    echo "Ошибка: Пользователь или мероприятие не существуют.";
}

// Закрытие подключения
$conn->close();
?>
