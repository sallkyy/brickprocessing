<?php
require 'config.php';
session_start();

//$userId = $_SESSION['user_id'];

$userId = 1;

// Обработка добавления заметки
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['note_text'])) {
    $noteText = mysqli_real_escape_string($conn, $_POST['note_text']);
    $noteHead = mysqli_real_escape_string($conn, $_POST['note_description']);
    
    $query = "INSERT INTO notes (user_id, note_head, note_body, created_at) 
              VALUES ($userId, '$noteHead' ,'$noteText', NOW())";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = 'Заметка успешно добавлена!';
    } else {
        $_SESSION['error_message'] = 'Ошибка при добавлении заметки: ' . mysqli_error($conn);
    }
}

// Перенаправление обратно в личный кабинет
header('Location: personal.php');
exit;
?>