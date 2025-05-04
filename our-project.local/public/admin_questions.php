<?php
require 'config.php';

// Обработка добавления вопроса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_question'])) {
    $testId = (int)$_POST['test_id'];
    $questiondescription = mysqli_real_escape_string($conn, $_POST['description']);
    
    $query = "INSERT INTO questions (test_id, description) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $testId, $questiondescription);
    $stmt->execute();
    $questionId = $stmt->insert_id;
}

// Обработка удаления вопроса
if (isset($_GET['delete_question'])) {
    $questionId = (int)$_GET['delete_question'];
    $conn->query("DELETE FROM questions WHERE question_id = $questionId");
    header("Location: admin_questions.php");
    exit;
}

$tests = mysqli_query($conn, "SELECT * FROM tests")->fetch_all(MYSQLI_ASSOC);

// Получение списка вопросов
$questionsQuery = "SELECT q.*, t.about_text as test_name 
                  FROM questions q
                  JOIN tests t ON q.test_id = t.test_id
                  ORDER BY q.question_id";
$questions = mysqli_query($conn, $questionsQuery)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Управление вопросами</title>
</head>
<body>
    <a href="main_admin.php">На главную</a>
    <h1>Управление вопросами тестов</h1>
    
    <div class="section">
        <h2>Добавить новый вопрос</h2>
        <form method="POST">
            <select name="test_id" required>
                <option value="">Выберите тест</option>
                <?php foreach ($tests as $test): ?>
                <option value="<?= $test['test_id'] ?>">
                    <?= htmlspecialchars($test['about_text']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            
            <div style="margin: 10px 0;">
                <textarea name="description" placeholder="Текст вопроса" 
                          style="width: 100%; min-height: 80px;" required></textarea>
            </div>
            
            <button type="submit" name="add_question">Добавить вопрос</button>
        </form>
    </div>
    
    <div class="section">
        <h2>Список вопросов</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Тест</th>
                    <th>Вопрос</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $question): ?>
                <tr>
                    <td><?= $question['question_id'] ?></td>
                    <td><?= htmlspecialchars($question['test_name']) ?></td>
                    <td><?= htmlspecialchars($question['description']) ?></td>
                    <td>
                        <a href="?delete_question=<?= $question['question_id'] ?>"
                           onclick="return confirm('Удалить вопрос?')">Удалить</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>