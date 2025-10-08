<?php
// config.php - подключение к базе данных
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'shoe_store';

// Создаем соединение
$conn = mysqli_connect($host, $username, $password, $database);

// Проверяем соединение
if (!$conn) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

// Устанавливаем кодировку
mysqli_set_charset($conn, "utf8");
?>