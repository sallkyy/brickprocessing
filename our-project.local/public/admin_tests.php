<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_test'])) {
    $aboutText = mysqli_real_escape_string($conn, $_POST['about_text']);
    $maxResult = (int)$_POST['max_result'];
    
    $query = "INSERT INTO tests (about_text, max_result) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $aboutText, $maxResult);
    $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_achievement'])) {
    $testId = (int)$_POST['test_id'];
    $achive_descr = mysqli_real_escape_string($conn, $_POST['achive_description']);
    $achive_text = mysqli_real_escape_string($conn, $_POST['achive_text']);
    $minScore = (int)$_POST['min_score'];
    
    $query = "INSERT INTO achievements (test_id, achive_description, achive_text, min_score) 
              VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issi", $testId, $achive_descr, $achive_text, $minScore);
    $stmt->execute();
}

$tests = mysqli_query($conn, "SELECT * FROM tests")->fetch_all(MYSQLI_ASSOC);

$achievementsQuery = "SELECT a.*, t.about_text as test_name 
                     FROM achievements a
                     JOIN tests t ON a.test_id = t.test_id";
$achievements = mysqli_query($conn, $achievementsQuery)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Управление тестами</title>
</head>
<body>
    <a href = "main_admin.php"> папочка окси </a>
    <h1>Управление тестами и достижениями</h1>
    
    <div class="section">
        <h2>Добавить новый тест</h2>
        <form method="POST">
            <input type="text" name="about_text" placeholder="Название теста" required>
            <input type="number" name="max_result" placeholder="Максимальный балл" required>
            <button type="submit" name="add_test">Добавить тест</button>
        </form>
        
        <h3>Список тестов</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Макс. балл</th>
                    <th>Удаление</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tests as $test): ?>
                <tr>
                    <td><?= $test['test_id'] ?></td>
                    <td><?= htmlspecialchars($test['about_text']) ?></td>
                    <td><?= $test['max_result'] ?></td>
                    <td>
                        <a href="delete_test.php?test_id=<?= $test['test_id'] ?>"
                           class="delete-btn"
                           onclick="return confirm('Удалить тест?')">Удалить</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="section">
        <h2>Добавить новое достижение</h2>
        <form method="POST">
            <select name="test_id" required>
                <option value="">Выберите тест</option>
                <?php foreach ($tests as $test): ?>
                <option value="<?= $test['test_id'] ?>">
                    <?= htmlspecialchars($test['about_text']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="achive_description" placeholder="Название достижения" required>
            <textarea name="achive_text" placeholder="Описание" required></textarea>
            <input type="number" name="min_score" placeholder="Минимальный балл" required>
            <button type="submit" name="add_achievement">Добавить достижение</button>
        </form>
        
        <h3>Список достижений</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Тест</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Минимальный балл</th>
                    <th>Удаление</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($achievements as $ach): ?>
                <tr>
                    <td><?= $ach['achievement_id'] ?></td>
                    <td><?= htmlspecialchars($ach['test_name']) ?></td>
                    <td><?= htmlspecialchars($ach['achive_description']) ?></td>
                    <td><?= htmlspecialchars($ach['achive_text']) ?></td>
                    <td><?= $ach['min_score'] ?></td>
                    <td>
                        <a href="delete_achievment.php?achievement_id=<?= $ach['achievement_id'] ?>"
                           class="delete-btn"
                           onclick="return confirm('Удалить достижение?')">Удалить</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>