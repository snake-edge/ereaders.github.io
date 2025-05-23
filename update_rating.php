<?php
// Start the session to access session variables
session_start();

// Include the database connection
require_once 'db.php'; // Assuming db.php contains your database connection code

// Check if book_id and rating are provided
if (isset($_POST['book_id']) && isset($_POST['rating']) && is_numeric($_POST['book_id']) && is_numeric($_POST['rating'])) {
    $book_id = (int)$_POST['book_id'];
    $rating = (int)$_POST['rating'];

    // Ensure the rating is between 1 and 5
    if ($rating < 1 || $rating > 5) {
        echo json_encode(['error' => 'Invalid rating. Rating must be between 1 and 5.']);
        exit;
    }

    // Debug: Check the incoming values
    error_log("book_id: $book_id, rating: $rating");

    // Prepare the SQL to check if the book exists
    $sql = "SELECT id FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id); // "i" indicates an integer type
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        error_log("Book with ID $book_id exists.");

        // Book exists, now check if the user is logged in
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            // Log the user ID
            error_log("User ID: $user_id");

            // Prepare the SQL to check if the user already rated this book
            $sql = "SELECT id FROM ratings WHERE book_id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $book_id, $user_id); // Bind two integers
            $stmt->execute();
            $result = $stmt->get_result();

            // If a rating exists, update it
            if ($result->num_rows > 0) {
                error_log("Rating already exists for book ID $book_id and user ID $user_id.");
                
                // Prepare the update query
                $sql = "UPDATE ratings SET rating = ? WHERE book_id = ? AND user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iii", $rating, $book_id, $user_id); // Bind the parameters as integers
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo json_encode(['success' => 'Rating updated successfully']);
                } else {
                    echo json_encode(['error' => 'Failed to update rating']);
                }
            } else {
                error_log("No rating found, inserting new rating.");

                // Prepare the insert query for a new rating
                $sql = "INSERT INTO ratings (book_id, user_id, rating) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iii", $book_id, $user_id, $rating); // Bind the parameters as integers
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo json_encode(['success' => 'Rating inserted successfully']);
                } else {
                    echo json_encode(['error' => 'Failed to insert rating']);
                }
            }
        } else {
            // Log and return user not logged in error
            error_log("User not logged in.");
            echo json_encode(['error' => 'User not logged in']);
        }
    } else {
        // Log and return invalid book ID error
        error_log("Invalid book ID: $book_id");
        echo json_encode(['error' => 'Invalid book ID']);
    }

    // Close the statement
    $stmt->close();
} else {
    // Log missing parameters and invalid input
    error_log("Invalid input: book_id or rating is missing or not numeric.");
    echo json_encode(['error' => 'Invalid book ID or rating']);
}

// Close the connection
$conn->close();
?>
