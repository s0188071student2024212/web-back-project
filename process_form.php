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
    
    if (empty($_POST['name'])) $errors[] = "Name is required";
    if (empty($_POST['phone_number'])) $errors[] = "Phone number is required";
    if (empty($_POST['email'])) $errors[] = "Email is required";
    if (!isset($_POST['agreement'])) $errors[] = "You must agree to receive communications";
    
    if (!empty($errors)) {
        $_SESSION['form_error'] = implode("<br>", $errors);
        header("Location: index.php#contact-form");
        exit();
    }
    
    // Генерация логина и пароля
    function generateCredentials() {
        $login = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 8);
        $password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*"), 0, 12);
        return [$login, $password];
    }
    
    list($login, $password) = generateCredentials();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        // Сохранение пользователя
        $stmt = $pdo->prepare("INSERT INTO users (login, password, fio, phone, email) 
                              VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $login,
            $hashedPassword,
            $_POST['name'],
            $_POST['phone_number'],
            $_POST['email']
        ]);
        
        // Сохранение сообщения
        if (!empty($_POST['message'])) {
            $user_id = $pdo->lastInsertId();
            $stmt = $pdo->prepare("INSERT INTO contacts (user_id, message, created_at) 
                                  VALUES (?, ?, NOW())");
            $stmt->execute([$user_id, $_POST['message']]);
        }
        
        // Очищаем сохраненные данные формы
        unset($_SESSION['form_data']);
        
        // Сохраняем credentials для отображения
        $_SESSION['form_credentials'] = [
            'login' => $login,
            'password' => $password
        ];
        
        header("Location: registration_success.php");
        exit();
        
    } catch (PDOException $e) {
        $_SESSION['form_error'] = "Database error: " . $e->getMessage();
        header("Location: index.php#contact-form");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
