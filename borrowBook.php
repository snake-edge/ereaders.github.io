<?php 
include 'db_connect.php';

function borrowBook($id, $bookId) {
    global $conn;

    // Check if book is already borrowed
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM borrowed_books WHERE book_id = ? AND returned_at IS NULL");
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result['count'] > 0) {
        echo json_encode(["success" => false, "message" => "Book is already borrowed."]);
        return;
    }

    $dueDate = date('Y-m-d', strtotime('+7 days'));

    $stmt = $conn->prepare("INSERT INTO borrowed_books (id, book_id, due_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $id, $bookId, $dueDate);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Book borrowed successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to borrow book."]);
    }
}

function returnBook($borrowId) {
    global $conn;
    $stmt = $conn->prepare("UPDATE borrowed_books SET returned_at = NOW() WHERE borrow_id = ?");
    $stmt->bind_param("i", $borrowId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Book returned successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to return book."]);
    }
}

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $bookId = $_POST['book_id'];
    borrowBook($id, $bookId);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);
    returnBook($put_vars['borrow_id']);
}
?>