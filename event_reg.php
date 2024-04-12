<?php
// Подключение к базе данных и другие необходимые файлы
$dbname = "event_platform"; // Имя вашей базы данных
require_once('./include/db.php');

// Проверка, был ли передан параметр event_id в URL
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Если был передан параметр event_id, выполните соответствующие действия для регистрации на мероприятие
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Обработка данных, которые пользователь отправляет с формы регистрации
        $email = $_POST['email'];

        // Получение ID пользователя по email
        $sql_select_user = "SELECT id FROM User WHERE email = ?";
        $stmt_select_user = $conn->prepare($sql_select_user);

        // Проверка на ошибку подготовки запроса
        if (!$stmt_select_user) {
            die("Ошибка подготовки запроса: " . $conn->error);
        }

        // Привязываем параметры
        $stmt_select_user->bind_param("s", $email);

        // Выполнение запроса
        $stmt_select_user->execute();

        // Получение результата запроса
        $result_select_user = $stmt_select_user->get_result();

        // Проверка наличия пользователя в базе данных
        if ($result_select_user->num_rows > 0) {
            // Получаем ID пользователя
            $row_user = $result_select_user->fetch_assoc();
            $user_id = $row_user['id'];

            // Подготовка запроса для вставки записи о регистрации на мероприятие в таблицу event_records
            $sql_insert_event_record = "INSERT INTO event_records (user_id, event_id) VALUES (?, ?)";
            $stmt_insert_event_record = $conn->prepare($sql_insert_event_record);

            // Проверка на ошибку подготовки запроса
            if (!$stmt_insert_event_record) {
                die("Ошибка подготовки запроса: " . $conn->error);
            }

            // Привязываем параметры
            $stmt_insert_event_record->bind_param("ii", $user_id, $event_id);

            // Выполнение запроса
            if ($stmt_insert_event_record->execute()) {
                echo "Регистрация на мероприятие прошла успешно!";
            } else {
                echo "Ошибка при регистрации на мероприятие: " . $conn->error;
            }

            // Закрытие подготовленного запроса
            $stmt_insert_event_record->close();
        } else {
            echo "Пользователь с таким email не найден.";
        }

        // Закрытие подготовленного запроса для поиска пользователя
        $stmt_select_user->close();
    }

    // Здесь может быть HTML-код вашей формы для регистрации пользователя на мероприятие
    // Например:
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Регистрация на мероприятие</title>
    </head>
    <body>
        <h1>Регистрация на мероприятие</h1>
        <form method="post" action="">
            <label for="email">Введите ваш email:</label>
            <input type="email" id="email" name="email" required><br>
            <input type="submit" value="Зарегистрироваться">
        </form>
    </body>
    </html>
    <?php
} else {
    // Если параметр event_id не был передан, вы можете вывести сообщение об ошибке или перенаправить пользователя на другую страницу
    echo "Ошибка: ID мероприятия не передан.";
}

// Закрытие подключения к базе данных
$conn->close();
?>
