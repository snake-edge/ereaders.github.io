<?php
session_start();
require_once 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = $_POST['book_id'];
$rating = $_POST['rating'];

// If the rating is 0, remove the rating
if ($rating == 0) {
    // Delete the user's rating from the database
    $sql_remove_rating = "DELETE FROM ratings WHERE user_id = $user_id AND book_id = $book_id";
    if ($conn->query($sql_remove_rating) === TRUE) {
        echo json_encode(['status' => 'removed']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to remove rating']);
    }
} else {
    // Insert or update the user's rating in the database
    $sql_check_existing = "SELECT * FROM ratings WHERE user_id = $user_id AND book_id = $book_id";
    $result = $conn->query($sql_check_existing);
    
    if ($result->num_rows > 0) {
        // Update the existing rating
        $sql_update_rating = "UPDATE ratings SET rating = $rating WHERE user_id = $user_id AND book_id = $book_id";
        if ($conn->query($sql_update_rating) === TRUE) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update rating']);
        }
    } else {
        // Insert a new rating
        $sql_insert_rating = "INSERT INTO ratings (user_id, book_id, rating) VALUES ($user_id, $book_id, $rating)";
        if ($conn->query($sql_insert_rating) === TRUE) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert rating']);
        }
    }
}

$conn->close();
?>
