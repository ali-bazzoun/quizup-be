<?php
include_once(__DIR__ . '/../config/database.php');
include_once(__DIR__ . '/../utils/logging.php');

$tables = [
    'users' => "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    'quizzes' => "CREATE TABLE IF NOT EXISTS quizzes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL UNIQUE,
        quiz_description TEXT,
        image_path VARCHAR(255)
    )",

    'questions' => "CREATE TABLE IF NOT EXISTS questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        quiz_id INT NOT NULL,
        question_text TEXT NOT NULL,
        correct_answer TEXT NOT NULL,
        FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
    )",

    'options' => "CREATE TABLE IF NOT EXISTS options (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question_id INT NOT NULL,
        option_text VARCHAR(255) NOT NULL,
        FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
    )",

    'scores' => "CREATE TABLE IF NOT EXISTS scores (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        quiz_id INT NOT NULL,
        score INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
    )"
];

$all_tables_verified = true;

foreach ($tables as $name => $sql) {
    try {
        $stmt = $pdo->prepare("SHOW TABLES LIKE :table");
        $stmt->execute(['table' => $name]);

        if ($stmt->rowCount() > 0) {
            echo "Table '$name' already exists.<br>";
            log_metric("table_exists: $name");
        } else {
            $pdo->exec($sql);
            echo "Table '$name' created successfully.<br>";
            log_metric("table_created: $name");
        }
    } catch (PDOException $e) {
        echo "Error processing table '$name': " . $e->getMessage() . "<br>";
        log_error("Failed to process table $name: " . $e->getMessage());
        $all_tables_verified = false;
    }
}

log_metric("tables_setup_status: " . ($all_tables_verified ? "1" : "0"));

?>
