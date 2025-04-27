<?php
require 'config.php'; // Database connection
require 'vendor/autoload.php'; // For password hashing (if using bcrypt or similar)

use \Firebase\JWT\JWT;

function registerUser($username, $email, $password) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return "Username or Email already taken.";
    }
    
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $username, $email, $passwordHash);
    $stmt->execute();
    
    return "User registered successfully!";
}

function loginUser($email, $password) {
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return "Invalid email or password.";
    }
    
    $user = $result->fetch_assoc();
    $storedPasswordHash = $user['password_hash'];
    
    if (!password_verify($password, $storedPasswordHash)) {
        // Incorrect password
        return "Invalid email or password.";
    }
    
    $key = "secretkey";
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600;  // JWT token expires in 1 hour
    $payload = array(
        "iss" => "yourdomain.com",
        "iat" => $issuedAt,
        "exp" => $expirationTime,
        "user_id" => $user['id']
    );
    
    $jwt = JWT::encode($payload, $key);
    
    return $jwt;
}

function getConnection() {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>
