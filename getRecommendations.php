
<?php
include 'db_connect.php';

function getRecommendations($id) {
    global $conn;

    // Basic recommendation logic (improve later with collaborative filtering)
    $stmt = $conn->prepare("SELECT * FROM books ORDER BY RAND() LIMIT 5");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    } else {
        echo json_encode([]);
    }
}

// Get recommendations if user ID is provided
if (isset($_GET['id'])) {
    getRecommendations($_GET['id']);
}
?>

