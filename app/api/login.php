<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/utils/logging.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email'], $data['password'])) {
	http_response_code(400);
	echo json_encode(['error' => 'Email and password are required']);
	exit;
}

$email = $data['email'];
$password = $data['password'];

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        log_metric("login_success: $email");
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email']
            ]
        ]);
    } else {
        log_metric("login_failed: $email");
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
} catch (PDOException $e) {
    log_error("Login error: " . $e->getMessage(), 'db_errors.log');
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
}

?>