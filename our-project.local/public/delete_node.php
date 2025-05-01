<?php
require 'config.php';
session_start();

//$userId = $_SESSION['user_id'];

$userId = 1;

if (isset($_GET['note_id'])) {
    $noteId = (int)$_GET['note_id'];

    $checkQuery = "SELECT id FROM notes WHERE note_id = $noteId AND user_id = $userId";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        $deleteQuery = "DELETE FROM notes WHERE note_id = $noteId";
        
        if (mysqli_query($conn, $deleteQuery)) {
            $_SESSION['success_message'] = 'Заметка успешно удалена!';
        } else {
            $_SESSION['error_message'] = 'Ошибка при удалении заметки: ' . mysqli_error($conn);
        }
    } else {
        $_SESSION['error_message'] = 'Заметка не найдена или у вас нет прав на ее удаление';
    }
} else {
    $_SESSION['error_message'] = 'Не указан ID заметки для удаления';
}

// Перенаправление обратно в личный кабинет
header('Location: personal.php');
exit;
?>