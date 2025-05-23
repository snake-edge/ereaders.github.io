<?php
// Include the database connection file
require_once 'db.php'; // Make sure this establishes $conn (MySQLi)

// Get the book ID and user ID from the request
$book_id = isset($_GET['book_id']) ? (int) $_GET['book_id'] : 0;
$user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0; // Or use session if preferred

// Prepare SQL query to fetch book details and user's own rating only
$sql = "
    SELECT 
        b.id,
        b.title,
        b.author,
        b.published_date,
        b.cover_image,
        b.description,
        b.link,
        IFNULL(r.rating, 0) AS user_rating
    FROM books b
    LEFT JOIN ratings r ON b.id = r.book_id AND r.user_id = ?
    WHERE b.id = ?
";

// Use prepared statements
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
    echo json_encode($book);
} else {
    echo json_encode([]);
}

$stmt->close();
$conn->close();
?>
