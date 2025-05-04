<?php
require 'config.php';
session_start();

//$userId = $_SESSION['user_id'];

$userId = 2;

if (isset($_GET['achievement_id'])) {
    $achievementId = (int)$_GET['achievement_id'];

    $checkQuery = "SELECT achievement_id FROM achievements WHERE achievement_id = $achievementId";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        $deleteQuery = "DELETE FROM achievements WHERE achievement_id = $achievementId";
        
        if (mysqli_query($conn, $deleteQuery)) {
            $_SESSION['success_message'] = 'Снесли достижение(!';
        } else { 
            $_SESSION['error_message'] = 'Ошибка при удалении: ' . mysqli_error($conn);
        }
    } else {
        $_SESSION['error_message'] = 'Папочка окси зол, нет фоты раком';
    }
} else {
    $_SESSION['error_message'] = 'Не указан ID для удаления???? 666';
}

// Перенаправление обратно в личный кабинет
header('Location: admin_tests.php');
exit;
?>