<?php
include '../../database/db_connection.php';

// Create a question
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quiz_id = $_POST['quiz_id'];
    $question_text = $_POST['question_text'];
    $correct_answer = $_POST['correct_answer'];

    $sql = "INSERT INTO questions (quiz_id, question_text, correct_answer) VALUES ('$quiz_id', '$question_text', '$correct_answer')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Question created successfully']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
}

// Read a question
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM questions WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $question = $result->fetch_assoc();
        echo json_encode($question);
    } else {
        echo json_encode(['message' => 'Question not found']);
    }
}

// Update a question
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $question_text = $_POST['question_text'];
    $correct_answer = $_POST['correct_answer'];

    $sql = "UPDATE questions SET question_text='$question_text', correct_answer='$correct_answer' WHERE id = '$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Question updated successfully']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
}

// Delete a question
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM questions WHERE id = '$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Question deleted successfully']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
}
?>
