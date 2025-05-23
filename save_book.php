<?php
// Include the database connection
require_once 'db.php';

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $description = $conn->real_escape_string($_POST['description']);
    $link = $conn->real_escape_string($_POST['link']);
    $published_date = $conn->real_escape_string($_POST['published_date']);
    $book_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    $cover_image_name = '';

    // If a file was uploaded
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['cover_image']['tmp_name'];
        $original_name = basename($_FILES['cover_image']['name']);
        $extension = pathinfo($original_name, PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $extension;
        $destination = 'uploads/' . $new_filename;

        if (move_uploaded_file($tmp_name, $destination)) {
            $cover_image_name = $new_filename;

            // If editing, remove the old image
            if ($book_id > 0) {
                $query = "SELECT cover_image FROM books WHERE id = $book_id";
                $result = $conn->query($query);
                if ($result && $result->num_rows > 0) {
                    $oldImage = $result->fetch_assoc()['cover_image'];
                    $oldPath = 'uploads/' . $oldImage;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
            }
        }
    }

    if ($book_id > 0) {
        // Update existing book
        $sql = "UPDATE books SET 
                    title = '$title', 
                    author = '$author', 
                    description = '$description', 
                    link = '$link', 
                    published_date = '$published_date'";
        if ($cover_image_name) {
            $sql .= ", cover_image = '$cover_image_name'";
        }
        $sql .= " WHERE id = $book_id";
    } else {
        // Insert new book
        $sql = "INSERT INTO books (title, author, description, link, published_date, cover_image)
                VALUES ('$title', '$author', '$description', '$link', '$published_date', '$cover_image_name')";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php?success=1");
        exit;
    } else {
        header("Location: admin.php?error=" . urlencode("Error saving book: " . $conn->error));
        exit;
    }
} else {
    header("Location: admin.php?error=Invalid+request+method");
    exit;
}

$conn->close();
?>
