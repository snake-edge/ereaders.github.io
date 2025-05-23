<?php
include 'db_connect.php';

function getBooks() {
    global $conn;

    $stmt = $conn->query("SELECT book_id, title, author, genre, publication_year, cover_image, availability_status FROM books");

    if ($stmt) {
        echo json_encode($stmt->fetch_all(MYSQLI_ASSOC));
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to fetch books."]);
    }
}

function getBookById($bookId) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Book not found."]);
    }
}

// Determine which function to call
if (isset($_GET['book_id'])) {
    getBookById($_GET['book_id']);
} else {
    getBooks();
}
?>