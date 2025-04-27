<?php
include('logging.php');

$servername = "localhost";
$username = "quizuser";
$password = "quizpass";
$database = "quizapp";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    log_error("Database connection failed: " . $conn->connect_error);
    log_metric("db_connection_success: 0");
    die("Could not connect to the database. Please try again later.");
} else {
    log_metric("db_connection_success: 1");
}

$tables = [
	"CREATE TABLE IF NOT EXISTS users (
		id INT AUTO_INCREMENT PRIMARY KEY,
		username VARCHAR(50) NOT NULL UNIQUE,
		email VARCHAR(255) NOT NULL UNIQUE,
		password_hash VARCHAR(255) NOT NULL,
		updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	)",

	"CREATE TABLE IF NOT EXISTS quizzes (
		id INT AUTO_INCREMENT PRIMARY KEY,
		title VARCHAR(255) NOT NULL UNIQUE,
		quiz_description TEXT,
		image_path VARCHAR(255)
	)",

	"CREATE TABLE IF NOT EXISTS questions (
		id INT AUTO_INCREMENT PRIMARY KEY,
		quiz_id INT NOT NULL,
		question_text TEXT NOT NULL,
		correct_answer TEXT NOT NULL,
		FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
	)",

	"CREATE TABLE IF NOT EXISTS options (
		id INT AUTO_INCREMENT PRIMARY KEY,
		question_id INT NOT NULL,
		option_text VARCHAR(255) NOT NULL,
		FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
	)",

	"CREATE TABLE IF NOT EXISTS scores (
		id INT AUTO_INCREMENT PRIMARY KEY,
		user_id INT NOT NULL,
		quiz_id INT NOT NULL,
		score INT NOT NULL,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
		FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
	)"
];

$all_tables_created = true;

foreach ($tables as $index => $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Table " . ($index + 1) . " (" . $tableNames[$index] . ") created successfully.<br>";
    } else {
        echo "Error creating table " . ($index + 1) . " (" . $tableNames[$index] . "): " . $conn->error . "<br>";
        log_error("Failed to create table " . $tableNames[$index] . ": " . $conn->error);
        $all_tables_created = false;
    }
}

if ($all_tables_created) {
    log_metric("tables_creation_success: 1");
} else {
    log_metric("tables_creation_success: 0");
}

$conn->close();
?>
