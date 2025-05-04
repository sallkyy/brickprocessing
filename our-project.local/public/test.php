<?php
require 'config.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


$testId = (int)$_GET['test_id'];
//$userId = (int)$_SESSION['user_id'];
$userId = 1;

if (isset($_GET['reset']) && $_GET['reset'] === '1') {
    // Полный сброс с подтверждением
    session_regenerate_id(true); // Важно для безопасности
    
    // Полностью очищаем данные теста
    unset($_SESSION['test_data']);
    
    // Сбрасываем результат в БД
    $resetQuery = "DELETE FROM user_test WHERE user_id = ? AND test_id = ?";
    $stmt = $conn->prepare($resetQuery);
    $stmt->bind_param("ii", $userId, $testId);
    $stmt->execute();
    
    // Инициализируем заново
    $_SESSION['test_data'] = [
        'test_id' => $testId,
        'current_question' => 0,
        'answers' => [],
        'total_score' => 0,
        'user_id' => $userId,
        'show_result' => false
    ];
    
    // Редирект без параметра reset
    header("Location: test.php?test_id=$testId");
    exit;
}

$checkResultsFromDB = "SELECT score FROM user_test WHERE user_id = ? AND test_id = ?";
$stmt = $conn->prepare($checkResultsFromDB);
$stmt->bind_param("ii", $userId, $testId);
$stmt->execute();
$existingResult = $stmt->get_result()->fetch_assoc();

if($existingResult && !isset($_GET['reset'])){
    $_SESSION['test_data'] = [
        'test_id' => $testId,
        'current_question' => 0,
        'answers' => [],
        'total_score' => $existingResult['score'],
        'user_id' => $userId,
        'show_result' => true // Флаг, что нужно показать результат
    ];
    header("Location: results.php");
    exit;
}

if (!isset($_SESSION['test_data']) || $_SESSION['test_data']['test_id'] != $testId) {
    $_SESSION['test_data'] = [
        'test_id' => $testId,
        'current_question' => 0,
        'answers' => [],
        'total_score' => 0,
        'user_id' => $userId,
        'show_result' => false
    ];
}

// Получаем список всех вопросов теста
$questionsQuery = "SELECT * FROM questions WHERE test_id = $testId ORDER BY question_id";
$questionsResult = mysqli_query($conn, $questionsQuery);
$allQuestions = mysqli_fetch_all($questionsResult, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    // Сохраняем ответ
    $questionId = (int)$_POST['question_id'];
    $score = (int)$_POST['score'];
    
    $_SESSION['test_data']['answers'][$questionId] = $score;
    $_SESSION['test_data']['total_score'] += $score;
    
    // Переходим к следующему вопросу
    $_SESSION['test_data']['current_question']++;
}

if ($_SESSION['test_data']['current_question'] >= count($allQuestions)) {
    $userid = (int)$_SESSION['test_data']['user_id'];
    $totalScore = (int)$_SESSION['test_data']['total_score'];
    $testid = (int)$_SESSION['test_data']['test_id'];

    $query_res = "SELECT * FROM user_test WHERE user_id = ? AND test_id = ?";
    $stmt = $conn->prepare($query_res);
    $stmt->bind_param("ii",$userid,$testid);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Обновляем существующую запись
        $update = "UPDATE user_test SET score = ? WHERE user_id = ? AND test_id = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("iii", $totalScore, $userid, $testid);
    } else {
        // Создаем новую запись
        $insert = "INSERT INTO user_test (user_id, test_id, score) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("iii", $userid, $testid, $totalScore);
    }
    
    
    $_SESSION['test_data']['show_result'] = true;
    header("Location: results.php");
    exit;
}

$query_questions = "SELECT description FROM questions WHERE question_id = ?";
$quest_descript = $conn->prepare($query_questions);
$quest_descript -> bind_param("i",$questionId);
$quest_descript->execute();

$currentQuestionIndex = $_SESSION['test_data']['current_question'];
$currentQuestion = $allQuestions[$currentQuestionIndex];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Тест - вопрос <?= $currentQuestionIndex + 1 ?></title>
</head>
<body>
    <h1>Тестирование</h1>
    
    <div class="progress">
        <div class="progress-bar"></div>
    </div>
    <p>Вопрос <?= $currentQuestionIndex + 1 ?> из <?= count($allQuestions) ?></p>
    
    <div class="question-container">
        <h3><?= htmlspecialchars($currentQuestion['description']) ?></h3>
        
        <form method="POST">
            <input type="hidden" name="question_id" value="<?= $currentQuestion['question_id'] ?>">
            
                <div class="rating-scale">
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <div class="rating-option">
                            <input type="radio" 
                                id="score-<?= $i ?>" 
                                name="score" 
                                value="<?= $i ?>" 
                                required>
                            <label for="score-<?= $i ?>"><?= $i ?></label>
                        </div>
                    <?php endfor; ?>
                </div>
            
            <button type="submit">
                <?= ($currentQuestionIndex + 1 == count($allQuestions)) ? 'Завершить тест' : 'Следующий вопрос' ?>
            </button>
        </form>
    </div>
</body>
</html>