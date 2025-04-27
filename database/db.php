<?php
$host = 'db';
$username = 'quizuser';
$password = 'quizpass';
$database = 'quizapp';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
