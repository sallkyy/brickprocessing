<?php
require 'config.php'; // Подключаем файл, где есть $conn = mysqli_connect(...)

//айдиник юзера
$userId = 2;
//$userId = $_SESSION['user_id'];
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $userId");
$currentUser = mysqli_fetch_assoc($userQuery);
$currentUser = 1;
//список тестов
$result = mysqli_query($conn, "SELECT test_id, about_text FROM tests;");
if (!$result) {
    die("Ошибка запроса: " . mysqli_error($conn));
}

$tests = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8">
    <title>Брикпроцессинг</title>
  </head>
  <body>
    <div class="header_of_list">
        <a href="index.php">
            <img src="" width=40px />
        </a>
        <a href = "index.php">Выход</a>  
    </div>
    <div class="maincontainer">
    <div class="admin-links">
            <h3>Администрирование</h3>
            <a href="admin_tests.php">Управление тестами</a>
            <a href="admin_questions.php">Управление вопросами</a>
        </div>
    </div>
  </body>
</html>
