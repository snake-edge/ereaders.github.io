<?php
require_once 'db.php';

$book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

$response = ['success' => false];

if ($book_id && $user_id) {
    $stmt = $conn->prepare("DELETE FROM ratings WHERE book_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $book_id, $user_id);

    if ($stmt->execute()) {
        $response['success'] = true;
    }

    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>
