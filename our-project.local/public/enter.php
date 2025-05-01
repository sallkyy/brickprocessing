<?php
require 'config.php'; // Подключаем файл, где есть $conn = mysqli_connect(...)

//айдиник юзера
$userId = 1;
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
      <div class="tests">
        <h2>Разнообразие тестов для тебя!</h2>
        <form action = "test.php" method = "GET">
          <select name = "test_id" required>
            <option value="">Выберите интересующий тест</option>
            <?php foreach ($tests as $test): ?>
              <option value="<?= $test['test_id'] ?>"><?=htmlspecialchars($test['about_text']) ?></option>
              <?php endforeach; ?>
          </select>
          <button type="submit">Начать выбранный тест</button>
        </form>
      </div>
      <div class="personalpage">
        <h2>Страничка с твоими умными мыслями</h2>
        <a href="personal.php">Перейти в личный кабинет</a>
      </div>
    </div>
  </body>
</html>
