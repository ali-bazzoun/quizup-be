<?php
include '../../database/db_connection.php';

// Create an option
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question_id = $_POST['question_id'];
    $option_text = $_POST['option_text'];

    $sql = "INSERT INTO options (question_id, option_text) VALUES ('$question_id', '$option_text')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Option created successfully']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
}

// Read an option
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM options WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $option = $result->fetch_assoc();
        echo json_encode($option);
    } else {
        echo json_encode(['message' => 'Option not found']);
    }
}

// Update an option
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $option_text = $_POST['option_text'];

    $sql = "UPDATE options SET option_text='$option_text' WHERE id = '$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Option updated successfully']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
}

// Delete an option
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM options WHERE id = '$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Option deleted successfully']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
}
?>
