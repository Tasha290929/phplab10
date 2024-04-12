<?php 
// Подключение к базе данных
$servername = "localhost";
$username = "root"; // Имя пользователя базы данных
$password = ""; // Пароль базы данных


// Создание подключения
$conn = @new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) exit('Ошибка подключения BD');
$conn->set_charset('utf8');