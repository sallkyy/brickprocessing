<?php
require 'config.php';
session_start();

if (empty($_SESSION['test_data'])) {
    die("Ошибка: данные теста не найдены. Пожалуйста, начните тест заново.");
}



// 2. Извлекаем данные с проверкой
$testData = $_SESSION['test_data'];
$testId = $testData['test_id'] ?? 0;
$totalScore = $testData['total_score'] ?? 0;
$userId = $testData['user_id'] ?? 0;

if (!$testId || !$totalScore) {
    die("Ошибка: некорректные данные теста");
}

// 3. Получаем информацию о тесте
$testTitle = "Неизвестный тест";
$maxScore = 100; // Значение по умолчанию

$testQuery = "SELECT about_text, max_result FROM tests WHERE test_id = ?";
if ($stmt = $conn->prepare($testQuery)) {
    $stmt->bind_param("i", $testId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $testTitle = htmlspecialchars($row['about_text'] ?? '');
        $maxScore = (int)($row['max_result'] ?? 100);
    }
    $stmt->close();
}

$percentage = $maxScore > 0 ? round(($totalScore / $maxScore) * 100) : 0;


$saveQuery = "INSERT INTO user_test (user_id, test_id, score) VALUES (?, ?, ?)
                                    ON DUPLICATE KEY UPDATE score = ?";
$stmt = $conn->prepare($saveQuery);
$stmt->bind_param("iiii", $userId, $testId, $totalScore, $totalScore);
$stmt->execute();
$stmt->close();

$achievementsQuery = "SELECT * FROM achievements WHERE test_id = ? AND min_score <= ?";
$stmt = $conn->prepare($achievementsQuery);
$stmt->bind_param("ii", $testId, $totalScore);
$stmt->execute();
$newAchievements = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

foreach ($newAchievements as $achievement) {
    $checkQuery = "SELECT 1 FROM user_achievement
                  WHERE user_id = ? AND achievement_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $userId, $achievement['achievement_id']);
    $stmt->execute();
    
    if (!$stmt->get_result()->num_rows) {
        // Добавляем новое достижение
        $insertQuery = "INSERT INTO user_achievement (user_id, achievement_id, date_of_getting) 
                       VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ii", $userId, $achievement['achievement_id']);
        $stmt->execute();
        
        $_SESSION['new_achievements'][] = $achievement;
    }
}

if (isset($_GET['restart'])) {
    unset($_SESSION['test_data']);
    header("Location: test.php?test_id=$testId");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Результаты теста</title>
</head>
<body>
    <div class="result-box">
        <h1>Результаты теста: <?= $testTitle ?></h1>
        
        <div class="progress-circle">
            Ваш процент прохождения получился <?=$percentage?>%! Поздравляем! 
        </div>
        
        <p>Вы набрали <strong><?= $totalScore ?></strong> из <strong><?= $maxScore ?></strong> баллов</p>
        
        <?php if (!empty($_SESSION['new_achievements'])): ?>
        <div class="achievement-notice" style="
        background: #f0f8ff;
        border: 1px solid #4682b4;
        border-radius: 4px;
        padding: 10px 15px;
        margin: 15px 0;
        text-align: center;
        font-size: 16px;
        color: #2e5984;
        ">
            <span style="float: right; cursor: pointer;" onclick="this.parentElement.style.display='none'">×</span>
            <div style="text-align: center;">
                🎉 Новое достижение получено! Проверьте свой профиль!
            </div>
            </div>
            <?php unset($_SESSION['new_achievements']); endif; ?>

        <div>
        <a href="test.php?test_id=<?= $testId ?>&reset=1" 
   class="btn"
   onclick="localStorage.removeItem('test_progress'); return true;">
   Перепройти тест (гарантированный сброс)
</a>
            <a href="enter.php" class="btn btn-home">На главную</a>
        </div>
    </div>
</body>
</html>