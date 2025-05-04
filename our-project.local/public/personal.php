<?php
require 'config.php';
session_start();

//$userId = $_SESSION['user_id'];
$userId = 1;
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $userId");
$currentUser = mysqli_fetch_assoc($userQuery);

$achievementsQuery = "SELECT achive_description, achive_text, ua.date_of_getting
                     FROM achievements a
                     JOIN user_achievement ua ON a.achievement_id = ua.achievement_id
                     WHERE ua.user_id = ?
                     ORDER BY ua.date_of_getting DESC";
$stmt = $conn->prepare($achievementsQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$userAchievements = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Получение данных пользователя (например, его заметок)
$notesQuery = mysqli_query($conn, "SELECT * FROM notes WHERE user_id = $userId ORDER BY created_at DESC");
$userNotes = mysqli_fetch_all($notesQuery, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
</head>
<body>
    <div class="header">
        <a href="enter.php" style="color: black;">На главную</a>
        <h1>Личный кабинет <?= htmlspecialchars($currentUser['login']) ?></h1>
    </div>
    
    <div class="container">
        <div class="user-info">
            <h2>Ваши данные</h2>
            <p><strong>Имя пользователя:</strong> <?= htmlspecialchars($currentUser['login']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($currentUser['email']) ?></p>
        </div>

        <div>
            <h2>Ваши достижения</h2>
                <?php if (!empty($userAchievements)): ?>
                    <?php foreach ($userAchievements as $achievement): ?>
                        <div class="achievement-card">
                            <div class="achievement-name">
                                <?= htmlspecialchars($achievement['achive_description']) ?>
                            </div>
                            <div><?= htmlspecialchars($achievement['achive_text']) ?></div>
                            <div class="achievement-date">
                                Получено: <?= date('d.m.Y', strtotime($achievement['date_of_getting'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>У вас пока нет достижений</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div>
            <h2>Ваши заметки</h2>
            
            <form class="note-form" action="add_note.php" method="POST">
                <textarea name= "note_description" placeholder = "Заголовок" rows="1" required></textarea>
                <br>
                <textarea name="note_text" rows="4" placeholder="Добавьте новую заметку..." required></textarea>
                <button type="submit">Сохранить заметку</button>
            </form>
            
            <?php foreach ($userNotes as $note): ?>
                <div class="note">
                    <p><?= nl2br(htmlspecialchars($note['note_head'])) ?></p>
                    <p><?= nl2br(htmlspecialchars($note['note_body'])) ?></p>
                    <small><?= $note['created_at'] ?></small>
                    <a href="delete_note.php?note_id=<?= $note['note_id'] ?>" onclick="return confirm('Удалить заметку?')">Удалить</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>