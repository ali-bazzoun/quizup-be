<?php
header('Content-Type: application/json');
include '../../database/db_connection.php'; // your mysqli connection

// Helper: Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive input
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode(['message' => 'Username and password are required']);
        exit;
    }

    // Prepared statement
    $stmt = $conn->prepare("SELECT id, username, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if (verifyPassword($password, $user['password_hash'])) {
            http_response_code(200);
            echo json_encode(['message' => 'Login successful', 'user_id' => $user['id']]);
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(['message' => 'Invalid credentials']);
        }
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['message' => 'User not found']);
    }

    $stmt->close();
}

$conn->close();
?>
