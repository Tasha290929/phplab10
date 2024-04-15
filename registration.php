<?php
session_start(); // Начинаем сессию

$dbname = "event_platform"; // Имя вашей базы данных
require_once('./include/db.php');

$errors = [];
$nameValue = '';
$surnameValue = '';
$emailValue = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Валидация данных
    if (empty($_POST['name'])) {
        $errors['name'][] = 'Введите имя!';
        $nameValue = ''; // Стираем неправильное имя
    } else {
        $nameValue = sanitizeData($_POST['name']);
    }
    if (empty($_POST['surname'])) {
        $errors['surname'][] = 'Введите фамилию!';
        $surnameValue = ''; // Стираем неправильную фамилию
    } else {
        $surnameValue = sanitizeData($_POST['surname']);
    }
    if (empty($_POST['email'])) {
        $errors['email'][] = 'Введите email!';
        $emailValue = ''; // Стираем неправильный email
    } else {
        $emailValue = sanitizeData($_POST['email']);
        if (!filter_var($emailValue, FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = 'Неверный формат email!';
            $emailValue = ''; // Стираем неправильный email
        }
    }

    // Проверяем, выбрана ли роль пользователя
    if (!isset($_POST['role'])) {
        $errors['role'][] = 'Выберите роль пользователя!';
    }

    if (empty($errors)) {
       
        $role = $_POST['role'];

        // Добавление пользователя в базу данных с указанием выбранной роли
        $sql = "INSERT INTO User (name, surname, email, role_id) VALUES ('$nameValue', '$surnameValue', '$emailValue', '$role')";

        // Подключение к базе данных
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Проверяем, нет ли уже пользователя с таким email
        $email_check_sql = "SELECT * FROM User WHERE email = '$emailValue'";
        $email_result = $conn->query($email_check_sql);
        if ($email_result->num_rows > 0) {
            $errors['email'][] = 'Пользователь с таким email уже существует!';
        } else {

      // Добавляем пользователя в базу данных
if ($conn->query($sql) === TRUE) {
    // Получаем идентификатор только что добавленного пользователя
    $user_id = $conn->insert_id;

    // Устанавливаем HTTP-код 201 (Created)
    http_response_code(201);
    echo "Регистрация прошла успешно!";
    
    echo "<br/>" . "<a href='events.php'> --> Посмотреть доступные мероприятия </a>";
    // Устанавливаем сессию, указывая, что пользователь зарегистрирован
    $_SESSION['authenticated'] = true;

    // Сохраняем user_id в сессию или переменную
    $_SESSION['user_id'] = $user_id;

    // Перенаправляем пользователя на страницу "events.php"
    header('Location: events.php');
    exit(); // Завершаем выполнение скрипта после перенаправления
} else {
    echo "Ошибка: " . $sql . "<br>" . $conn->error;
}

        }
        $conn->close();
    }
}

/**
 * Sanitizes the given data.
 * @param string $data The data to sanitize.
 * @return string The sanitized data.
 */
function sanitizeData(string $data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="stilereg.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container">
        <main class="form-signin">
            <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                <h1 class="h3 mb-3 fw-normal">Регистрация</h1>

                <div class="form-floating">
                    <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" placeholder="Имя" name="name" value="<?php echo $nameValue; ?>">
                    <label for="name">Имя</label>
                    <?php if (isset($errors['name'])) : ?>
                        <div class="invalid-feedback">
                            <?php foreach ($errors['name'] as $error) : ?>
                                <?php echo $error; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-floating">
                    <input type="text" class="form-control <?php echo isset($errors['surname']) ? 'is-invalid' : '' ?>" id="surname" placeholder="Фамилия" name="surname" value="<?php echo $surnameValue; ?>">
                    <label for="surname">Фамилия</label>
                    <?php if (isset($errors['surname'])) : ?>
                        <div class="invalid-feedback">
                            <?php foreach ($errors['surname'] as $error) : ?>
                                <?php echo $error; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-floating">
                    <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" placeholder="name@example.com" name="email" value="<?php echo $emailValue; ?>">
                    <label for="email">Email адрес</label>
                    <?php if (isset($errors['email'])) : ?>
                        <div class="invalid-feedback">
                            <?php foreach ($errors['email'] as $error) : ?>
                                <?php echo $error; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-floating">
                    <select class="form-select <?php echo isset($errors['role']) ? 'is-invalid' : '' ?>" id="role" name="role">
                        <option value="" selected disabled>Выберите роль</option>
                        <option value="1">Пользователь</option>
                        <option value="2">Администратор</option>
                    </select>
                    <label for="role">Роль</label>
                    <?php if (isset($errors['role'])) : ?>
                        <div class="invalid-feedback">
                            <?php foreach ($errors['role'] as $error) : ?>
                                <?php echo $error; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <br>
                <button class="w-100 btn btn-lg btn-primary mb-3" type="submit">Зарегистрироваться</button>
                <a href="./autorization.php" class="w-100 btn btn-lg btn-primary">Авторизироваться</a>

            </form>
        </main>
    </div>

</body>

</html>