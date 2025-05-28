<?php
session_start();
if (!isset($_SESSION['form_credentials'])) {
    header("Location: index.php");
    exit();
}

$credentials = $_SESSION['form_credentials'];
unset($_SESSION['form_credentials']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Successful</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .success-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .credentials {
            margin: 20px 0;
            padding: 15px;
            background-color: #e9f7ef;
            border-radius: 5px;
        }
        .btn-return {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #1a73e8;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h2>Thank You for Your Request!</h2>
        <p>Your account has been successfully created. Here are your login credentials:</p>
        
        <div class="credentials">
            <p><strong>Login:</strong> <?php echo htmlspecialchars($credentials['login']); ?></p>
            <p><strong>Password:</strong> <?php echo htmlspecialchars($credentials['password']); ?></p>
        </div>
        
        <p>Please save these credentials securely. You can use them to log in to your account.</p>
        
        <a href="login.php" class="btn-return">Go to Login Page</a>
        <a href="index.php" class="btn-return">Return to Home</a>
    </div>
</body>
</html>
