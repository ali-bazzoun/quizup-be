<?php
$host = 'db';
$dbname = 'quizapp';
$username = 'quizuser';
$password = 'quizpass';

$tries = 5;

while ($tries > 0) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "✅ Connected successfully to the database!\n";
        break;
    } catch (PDOException $e) {
        echo "⏳ Waiting for database connection... ($tries tries left)\n";
        sleep(3);  // wait 3 seconds before retrying
        $tries--;
    }
}

if ($tries === 0) {
    die('❌ Could not connect to the database after multiple attempts.');
}


require_once 'database/create_table.php'; // or use include_once if you prefer

