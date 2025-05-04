<?php
require 'config.php';
session_start();

//$userId = $_SESSION['user_id'];

$userId = 2;

if (isset($_GET['test_id'])) {
    $testId = (int)$_GET['test_id'];

    $checkQuery = "SELECT test_id FROM tests WHERE test_id = $testId";
    $checkResult = mysqli_query($conn, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        $deleteQuery = "DELETE FROM tests WHERE test_id = $testId";
        
        if (mysqli_query($conn, $deleteQuery)) {
            $_SESSION['success_message'] = 'Снесли тест!';
        } else {
            $_SESSION['error_message'] = 'Ошибка при удалении: ' . mysqli_error($conn);
        }
    } else {
        $_SESSION['error_message'] = 'Теста нет, ахах';
    }
} else {
    $_SESSION['error_message'] = 'Не указан ID теста для удаления???? 666';
}

// Перенаправление обратно в личный кабинет
header('Location: admin_tests.php');
exit;
?>