<?php
include '../../database/db_connection.php';

// Create a quiz
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $quiz_description = $_POST['quiz_description'];
    $image_path = $_POST['image_path'];

    $sql = "INSERT INTO quizzes (title, quiz_description, image_path) VALUES ('$title', '$quiz_description', '$image_path')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Quiz created successfully']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
}

// Read a quiz
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM quizzes WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $quiz = $result->fetch_assoc();
        echo json_encode($quiz);
    } else {
        echo json_encode(['message' => 'Quiz not found']);
    }
}

// Update a quiz
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $title = $_POST['title'];
    $quiz_description = $_POST['quiz_description'];
    $image_path = $_POST['image_path'];

    $sql = "UPDATE quizzes SET title='$title', quiz_description='$quiz_description', image_path='$image_path' WHERE id = '$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Quiz updated successfully']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
}

// Delete a quiz
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM quizzes WHERE id = '$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Quiz deleted successfully']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
}
?>
