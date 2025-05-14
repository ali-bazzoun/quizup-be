<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Util/Logging.php';

function setup_database()
{
    $pdo = Database::get_connection();

    $tables = [
        'users' => "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",

        'quizzes' => "CREATE TABLE IF NOT EXISTS quizzes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL UNIQUE,
            description TEXT,
            image_path VARCHAR(255)
        )",

        'questions' => "CREATE TABLE IF NOT EXISTS questions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            quiz_id INT NOT NULL,
            text TEXT NOT NULL,
            FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
        )",

        'options' => "CREATE TABLE IF NOT EXISTS options (
            id INT AUTO_INCREMENT PRIMARY KEY,
            question_id INT NOT NULL,
            text VARCHAR(255) NOT NULL,
            is_correct BOOLEAN NOT NULL,
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

    foreach ($tables as $name => $sql)
    {
        $stmt = $pdo->query("SHOW TABLES LIKE '$name'");
        if ($stmt->rowCount() > 0) 
            $done = 1;
        else 
            $pdo->exec($sql);
    }
}
