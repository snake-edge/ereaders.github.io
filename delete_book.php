<?php
// Include the database connection
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the book ID from POST
    $book_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($book_id > 0) {
        // First, get the current cover image to delete the file
        $getImageQuery = "SELECT cover_image FROM books WHERE id = ?";
        $stmtImage = $conn->prepare($getImageQuery);
        $stmtImage->bind_param("i", $book_id);
        $stmtImage->execute();
        $imageResult = $stmtImage->get_result();

        if ($imageResult && $imageResult->num_rows > 0) {
            $imageRow = $imageResult->fetch_assoc();
            $coverImagePath = 'uploads/' . $imageRow['cover_image'];

            // Delete the image file if it exists
            if (file_exists($coverImagePath)) {
                unlink($coverImagePath);
            }
        }

        // Delete related ratings first
        $deleteRatingsQuery = "DELETE FROM ratings WHERE book_id = ?";
        $stmtRatings = $conn->prepare($deleteRatingsQuery);
        $stmtRatings->bind_param("i", $book_id);
        $stmtRatings->execute();

        // Now delete the book
        $deleteBookQuery = "DELETE FROM books WHERE id = ?";
        $stmtBook = $conn->prepare($deleteBookQuery);
        $stmtBook->bind_param("i", $book_id);

        if ($stmtBook->execute()) {
            header("Location: admin.php?deleted=1");
            exit;
        } else {
            header("Location: admin.php?error=" . urlencode("Error deleting book: " . $stmtBook->error));
            exit;
        }
    } else {
        header("Location: admin.php?error=Invalid+book+ID");
        exit;
    }
} else {
    header("Location: admin.php?error=Invalid+request+method");
    exit;
}

$conn->close();
?>
