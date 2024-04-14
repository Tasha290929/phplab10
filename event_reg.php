<?php
session_start();

// Подключение к базе данных и другие необходимые файлы
$dbname = "event_platform"; // Имя вашей базы данных
require_once('./include/db.php');

if (!isset($_SESSION['registered']) && !isset($_SESSION['authenticated'])) {
    // Если сессия не установлена, перенаправляем пользователя на страницу авторизации или регистрации
    header('Location: registration.php');
    exit(); // Завершаем выполнение скрипта после перенаправления
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Проверка, был ли передан параметр event_id в URL
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Получаем информацию о мероприятии, включая количество доступных мест
    $sql_select_event = "SELECT * FROM events WHERE id = ?";
    $stmt_select_event = $conn->prepare($sql_select_event);

    // Проверка на ошибку подготовки запроса
    if (!$stmt_select_event) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }

    // Привязываем параметры
    $stmt_select_event->bind_param("i", $event_id);

    // Выполнение запроса
    $stmt_select_event->execute();

    // Получение результата запроса
    $result_select_event = $stmt_select_event->get_result();

    // Проверка наличия мероприятия
    if ($result_select_event->num_rows > 0) {
        // Получаем информацию о мероприятии
        $row_event = $result_select_event->fetch_assoc();
        $event_name = $row_event['name'];
        $event_price = $row_event['price'];
        $event_date = $row_event['date'];
        $event_seats = $row_event['number_seats']; // Получаем количество мест

        // Проверяем, была ли отправлена форма
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Обработка данных, которые пользователь отправляет с формы регистрации
            $seats_requested = $_POST['seats'];

            // Проверка, что количество запрашиваемых мест не превышает доступное количество мест
            if ($seats_requested > $event_seats) {
                echo "Вы запросили слишком много мест. Доступно только $event_seats мест.";
            } else {
                // Проверяем, установлен ли user_id в сессии
                if (!isset($_SESSION['user_id'])) {
                    // Если user_id не установлен, перенаправляем на страницу авторизации или регистрации
                    header('Location: registration.php');
                    exit();
                }

                // Получение user_id из сессионной переменной
                $user_id = $_SESSION['user_id'];

                // Рассчитываем общую стоимость
                $total_price = $event_price * $seats_requested;

                // Регистрируем пользователя на мероприятие
                $sql_insert_event_record = "INSERT INTO event_records (user_id, event_id, seats_requested, total_price) VALUES (?, ?, ?, ?)";
                $stmt_insert_event_record = $conn->prepare($sql_insert_event_record);

                // Проверка на ошибку подготовки запроса
                if (!$stmt_insert_event_record) {
                    die("Ошибка подготовки запроса: " . $conn->error);
                }

                // Привязываем параметры, включая user_id и total_price
                $stmt_insert_event_record->bind_param("iiid", $user_id, $event_id, $seats_requested, $total_price);

                // Выполнение запроса
                if ($stmt_insert_event_record->execute()) {
                    echo "Регистрация на мероприятие \"$event_name\" прошла успешно!<br>";
                    echo "Выбранное количество мест: $seats_requested<br>";
                    echo "Общая стоимость: $total_price";
                } else {
                    echo "Ошибка при регистрации на мероприятие: " . $conn->error;
                }

                // Закрытие подготовленного запроса
                $stmt_insert_event_record->close();
            }
        }

        // Здесь выводим HTML-код формы для регистрации пользователя на мероприятие
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Регистрация на мероприятие</title>
        </head>
        <body>
            <h1>Регистрация на мероприятие "<?php echo $event_name; ?>"</h1>
            <p><strong>Дата мероприятия:</strong> <?php echo $event_date; ?></p>
            <p><strong>Цена за место:</strong> <?php echo $event_price; ?></p>
            <p><strong>Доступное количество мест:</strong> <?php echo $event_seats; ?></p>
            <form method="post" action="">
                <label for="seats">Введите количество мест:</label>
                <input type="number" id="seats" name="seats" min="1" max="<?php echo $event_seats; ?>" required><br>
                <input type="submit" value="Зарегистрироваться">
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Мероприятие с ID $event_id не найдено.";
    }

    // Закрытие подготовленного запроса для выборки мероприятия
    $stmt_select_event->close();
} else {
    // Если параметр event_id не был передан, выведите сообщение об ошибке
    echo "Ошибка: ID мероприятия не передан.";
}

// Закрытие подключения к базе данных
$conn->close();
?>
