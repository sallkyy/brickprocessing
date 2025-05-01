<?php
require 'config.php';
session_start();

//$userId = $_SESSION['user_id'];
$userId = 1;
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $userId");
$currentUser = mysqli_fetch_assoc($userQuery);

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
        <a href="enter.php" style="color: white;">На главную</a>
        <h1>Личный кабинет <?= htmlspecialchars($currentUser['login']) ?></h1>
    </div>
    
    <div class="container">
        <div class="user-info">
            <h2>Ваши данные</h2>
            <p><strong>Имя пользователя:</strong> <?= htmlspecialchars($currentUser['login']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($currentUser['email']) ?></p>
        </div>
        
        <div class="notes-section">
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
                    <a href="delete_note.php?id=<?= $note['note_id'] ?>" onclick="return confirm('Удалить заметку?')">Удалить</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>