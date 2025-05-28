<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Очищаем предыдущие данные
    unset($_SESSION['form_error']);
    unset($_SESSION['form_success']);
    unset($_SESSION['form_data']);
    
    // Сохраняем данные формы для повторного заполнения
    $_SESSION['form_data'] = [
        'name' => $_POST['name'] ?? '',
        'phone_number' => $_POST['phone_number'] ?? '',
        'email' => $_POST['email'] ?? '',
        'message' => $_POST['message'] ?? '',
        'agreement' => isset($_POST['agreement']) ? true : false
    ];
    
    // Валидация данных
    $errors = [];
    
    // Проверка имени
    if (empty($_POST['name'])) {
        $errors[] = "Name is required";
    } elseif (strlen($_POST['name']) > 100) {
        $errors[] = "Name is too long (max 100 characters)";
    }
    
    // Проверка телефона
    if (empty($_POST['phone_number'])) {
        $errors[] = "Phone number is required";
    } elseif (!preg_match('/^[\d\s\-\(\)\+]+$/', $_POST['phone_number'])) {
        $errors[] = "Invalid phone number format";
    }
    
    // Проверка email
    if (empty($_POST['email'])) {
        $errors[] = "Email is required";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Проверка согласия
    if (!isset($_POST['agreement'])) {
        $errors[] = "You must agree to receive communications";
    }
    
    // Если есть ошибки
    if (!empty($errors)) {
        $_SESSION['form_error'] = implode("<br>", $errors);
        header("Location: index.php#contact-form");
        exit();
    }
    
    try {
        // Сохранение в базу данных
        $stmt = $pdo->prepare("INSERT INTO contacts (name, phone, email, message, agreement, created_at) 
                              VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $_POST['name'],
            $_POST['phone_number'],
            $_POST['email'],
            $_POST['message'] ?? null,
            isset($_POST['agreement']) ? 1 : 0
        ]);
        
        // Очищаем сохраненные данные формы
        unset($_SESSION['form_data']);
        
        // Устанавливаем сообщение об успехе
        $_SESSION['form_success'] = "Thank you! Your request has been sent successfully.";
        
        // Перенаправляем обратно к форме
        header("Location: index.php#contact-form");
        exit();
        
    } catch (PDOException $e) {
        $_SESSION['form_error'] = "Database error: " . $e->getMessage();
        header("Location: index.php#contact-form");
        exit();
    }
} else {
    // Если запрос не POST
    header("Location: index.php");
    exit();
}
