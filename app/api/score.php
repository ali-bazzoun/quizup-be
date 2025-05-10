<?php
// score.php

require_once '../database/db_connection.php';
require_once '../utils/logging.php';

// Create a Score
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    $userId = $_POST['user_id'];
    $quizId = $_POST['quiz_id'];
    $score = $_POST['score'];

    $stmt = $conn->prepare("INSERT INTO scores (user_id, quiz_id, score) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $userId, $quizId, $score);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Score created successfully"]);
    } else {
        echo json_encode(["message" => "Error creating score: " . $stmt->error]);
    }
}

// Read Score by ID
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $scoreId = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM scores WHERE id = ?");
    $stmt->bind_param("i", $scoreId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $score = $result->fetch_assoc();
        echo json_encode($score);
    } else {
        echo json_encode(["message" => "Score not found"]);
    }
}

// Update Score
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['id'])) {
    $scoreId = $_GET['id'];
    $score = $_POST['score'];

    $stmt = $conn->prepare("UPDATE scores SET score = ? WHERE id = ?");
    $stmt->bind_param("ii", $score, $scoreId);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Score updated successfully"]);
    } else {
        echo json_encode(["message" => "Error updating score: " . $stmt->error]);
    }
}

// Delete Score
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['id'])) {
    $scoreId = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM scores WHERE id = ?");
    $stmt->bind_param("i", $scoreId);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Score deleted successfully"]);
    } else {
        echo json_encode(["message" => "Error deleting score: " . $stmt->error]);
    }
}

?>
