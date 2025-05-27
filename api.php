<?php
require_once 'db_connection.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

$response = ['status' => 'error', 'message' => 'Invalid request'];

try {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'register_client':
            $response = handleClientRegistration($pdo);
            break;
            
        case 'client_login':
            $response = handleClientLogin($pdo);
            break;
            
        case 'save_submission':
            $response = saveFormSubmission($pdo);
            break;
            
        case 'get_submissions':
            $response = getClientSubmissions($pdo);
            break;
            
        case 'update_submission':
            $response = updateFormSubmission($pdo);
            break;
            
        case 'check_session':
            $response = checkClientSession($pdo);
            break;
            
        case 'client_logout':
            $response = handleClientLogout();
            break;
            
        default:
            $response = ['status' => 'error', 'message' => 'Unknown action'];
    }
    
} catch (PDOException $e) {
    $response = ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => 'Error: ' . $e->getMessage()];
}

echo json_encode($response);

function handleClientRegistration($pdo) {
    $required = ['email', 'password'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            return ['status' => 'error', 'message' => "Missing required field: $field"];
        }
    }
    
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['status' => 'error', 'message' => 'Invalid email format'];
    }
    
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    $stmt = $pdo->prepare("
        INSERT INTO construction_clients (email, password_hash) 
        VALUES (?, ?)
    ");
    
    try {
        $stmt->execute([$email, $password]);
        $_SESSION['client_id'] = $pdo->lastInsertId();
        $_SESSION['client_email'] = $email;
        
        return [
            'status' => 'success',
            'client_id' => $_SESSION['client_id'],
            'email' => $email
        ];
        
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            return ['status' => 'error', 'message' => 'Email already registered'];
        }
        throw $e;
    }
}

function handleClientLogin($pdo) {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        return ['status' => 'error', 'message' => 'Email and password required'];
    }
    
    $stmt = $pdo->prepare("
        SELECT client_id, password_hash 
        FROM construction_clients 
        WHERE email = ?
    ");
    $stmt->execute([$_POST['email']]);
    $client = $stmt->fetch();
    
    if (!$client || !password_verify($_POST['password'], $client['password_hash'])) {
        return ['status' => 'error', 'message' => 'Invalid credentials'];
    }
    
    $_SESSION['client_id'] = $client['client_id'];
    $_SESSION['client_email'] = $_POST['email'];
    
    // Обновляем время последнего входа
    $pdo->prepare("
        UPDATE construction_clients 
        SET last_login = CURRENT_TIMESTAMP 
        WHERE client_id = ?
    ")->execute([$client['client_id']]);
    
    return [
        'status' => 'success',
        'client_id' => $client['client_id'],
        'email' => $_POST['email']
    ];
}

function saveFormSubmission($pdo) {
    if (empty($_SESSION['client_id'])) {
        return ['status' => 'error', 'message' => 'Authentication required'];
    }
    
    $formData = [
        'name' => $_POST['name'] ?? null,
        'phone' => $_POST['phone'] ?? null,
        'email' => $_POST['email'] ?? null,
        'message' => $_POST['message'] ?? null
    ];
    
    $formType = $_POST['form_type'] ?? 'other';
    
    $stmt = $pdo->prepare("
        INSERT INTO client_submissions 
        (client_id, form_type, name, phone, email, message, form_data) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $_SESSION['client_id'],
        $formType,
        $formData['name'],
        $formData['phone'],
        $formData['email'],
        $formData['message'],
        json_encode($formData)
    ]);
    
    return [
        'status' => 'success',
        'submission_id' => $pdo->lastInsertId()
    ];
}

function getClientSubmissions($pdo) {
    if (empty($_SESSION['client_id'])) {
        return ['status' => 'error', 'message' => 'Authentication required'];
    }
    
    $stmt = $pdo->prepare("
        SELECT submission_id, form_type, name, phone, email, message, 
               form_data, submitted_at 
        FROM client_submissions 
        WHERE client_id = ? 
        ORDER BY submitted_at DESC
    ");
    $stmt->execute([$_SESSION['client_id']]);
    
    $submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'status' => 'success',
        'submissions' => $submissions
    ];
}

function updateFormSubmission($pdo) {
    if (empty($_SESSION['client_id'])) {
        return ['status' => 'error', 'message' => 'Authentication required'];
    }
    
    $submissionId = $_POST['submission_id'] ?? 0;
    $formData = [
        'name' => $_POST['name'] ?? null,
        'phone' => $_POST['phone'] ?? null,
        'email' => $_POST['email'] ?? null,
        'message' => $_POST['message'] ?? null
    ];
    
    // Проверяем принадлежность формы клиенту
    $checkStmt = $pdo->prepare("
        SELECT submission_id 
        FROM client_submissions 
        WHERE submission_id = ? AND client_id = ?
    ");
    $checkStmt->execute([$submissionId, $_SESSION['client_id']]);
    
    if ($checkStmt->rowCount() === 0) {
        return ['status' => 'error', 'message' => 'Submission not found'];
    }
    
    $updateStmt = $pdo->prepare("
        UPDATE client_submissions 
        SET name = ?, phone = ?, email = ?, message = ?, form_data = ? 
        WHERE submission_id = ?
    ");
    
    $updateStmt->execute([
        $formData['name'],
        $formData['phone'],
        $formData['email'],
        $formData['message'],
        json_encode($formData),
        $submissionId
    ]);
    
    return ['status' => 'success'];
}

function checkClientSession($pdo) {
    if (!empty($_SESSION['client_id'])) {
        $stmt = $pdo->prepare("
            SELECT email 
            FROM construction_clients 
            WHERE client_id = ?
        ");
        $stmt->execute([$_SESSION['client_id']]);
        $client = $stmt->fetch();
        
        if ($client) {
            return [
                'status' => 'success',
                'authenticated' => true,
                'client_id' => $_SESSION['client_id'],
                'email' => $client['email']
            ];
        }
    }
    
    return ['status' => 'success', 'authenticated' => false];
}

function handleClientLogout() {
    $_SESSION = [];
    session_destroy();
    return ['status' => 'success'];
}
?>