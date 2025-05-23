<?php
require_once 'db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;

    if ($book_id > 0) {
        // Check if the book is already favorited
        $check = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND book_id = ?");
        $check->bind_param("ii", $user_id, $book_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            // Remove from favorites
            $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND book_id = ?");
            $stmt->bind_param("ii", $user_id, $book_id);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'removed']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to remove from favorites']);
            }
        } else {
            // Add to favorites
            $stmt = $conn->prepare("INSERT INTO favorites (user_id, book_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $book_id);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'added']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add to favorites']);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid book ID']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>
