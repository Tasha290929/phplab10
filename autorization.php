<?php
session_start();

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

$errors = [];
$loginValue = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Валидация данных
    if (empty($_POST['login'])) {
        $errors['login'][] = 'Введите имя!';
        $loginValue = ''; // Стираем неправильный логин
    } else {
        $loginValue = sanitizeData($_POST['login']);
    }

    // Если нет ошибок, проводим авторизацию
    if (count($errors) === 0) {
        // Проверяем, существует ли пользователь с таким логином и паролем
        $usersData = file_get_contents("users.txt");
        $users = explode(PHP_EOL, $usersData);
        $login = $_POST['login'];
        $userFound = false;

        foreach ($users as $userData) {
            list($savedLogin, $savedPassword) = explode(':', $userData);
            if ($savedLogin === $login && md5($_POST['password']) === $savedPassword) {
                $userFound = true;
                // Перенаправляем на страницу с изображениями
                header("Location: images.php");
                exit;
            }
        }

        


        if (!$userFound) {
            // Если пользователь не найден, добавляем ошибку
            $errors["login"] []= "Неверный логин или пароль.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }

        main {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            text-align: center;
        }

        .form-signin {
            width: 100%;
        }

        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
<main class="form-signin w-100 m-auto">
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <h1 class="h3 mb-3 fw-normal">Please log in</h1>

        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="login" value="<?php echo $loginValue; ?>">
            <label for="floatingInput">Email address </label>
            <?php if (isset($errors["login"])) : ?>
                <?php foreach ($errors["login"] as $error) : ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
            <label for="floatingPassword">Password </label>
            <?php if (isset($errors["password"])) : ?>
                <?php foreach ($errors["password"] as $error) : ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

      
        <button class="btn btn-primary w-100 py-2" type="submit">Log in</button>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
