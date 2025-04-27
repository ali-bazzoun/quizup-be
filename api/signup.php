<?php
header('Content-Type: application/json');
include '../../database/db_connection.php'; // your mysqli connection

// Helper: Hash password
function generatePasswordHash($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive input
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic validation (you can make it stricter)
    if (empty($username) || empty($email) || empty($password)) {
        http_response_code(400); // Bad Request
        echo json_encode(['message' => 'All fields are required']);
        exit;
    }

    $passwordHash = generatePasswordHash($password);

    // Use prepared statement
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $passwordHash);

    if ($stmt->execute()) {
        http_response_code(201); // Created
        echo json_encode(['message' => 'User created successfully']);
    } else {
        http_response_code(500); // Server error
        echo json_encode(['message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
