<?php
session_start();

$dbname = "event_platform"; // Имя вашей базы данных
require_once('./include/db.php');

 // Создание соединения с базой данных
 $conn = new mysqli($servername, $username, $password, $dbname);

 // Проверка соединения
 if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
 }

$errors = [];
$nameValue = '';
$surnameValue = '';
$emailValue = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных от пользователя и очистка их
    $name = sanitizeData($_POST['name']);
    $surname = sanitizeData($_POST['surname']);
    $email = sanitizeData($_POST['email']);


    // Подготовка SQL запроса для проверки совпадений
    $sql = "SELECT * FROM User WHERE name='$name' AND surname='$surname' AND email='$email'";

    $result = $conn->query($sql);

    // Проверка наличия совпадений
    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Устанавливаем user_id в сессии
            $_SESSION['user_id'] = $row['id'];
            // Авторизация успешна
            echo "Авторизация успешна! ID пользователя: " . $row['id'];
            header('Location: events.php');
            exit();
        }
         else {
            // Неверные данные
            echo "Неверные данные, попробуйте еще раз.";
        }
    } else {
        // Ошибка запроса
        echo "Ошибка запроса: " . $conn->error;
    }

    // Закрытие соединения с базой данных
    $conn->close();
}

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
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<main class="form-signin">
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <h1 class="h3 mb-3 fw-normal">Авторизация</h1>

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

        <button class="w-100 btn btn-lg btn-primary" type="submit">Войти</button>
    </form>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
