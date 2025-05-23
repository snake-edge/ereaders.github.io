<?php 
require 'db_connect.php'; // Include database connection

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Enable CORS (Optional)
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add_book':
        if (!isset($_POST['title'], $_POST['author'], $_POST['genre'], $_POST['year'], $_POST['cover'], $_POST['status'], $_POST['description'])) {
            echo json_encode(['error' => 'Missing required fields']);
            exit;
        }

        $title = htmlspecialchars($_POST['title']);
        $author = htmlspecialchars($_POST['author']);
        $genre = htmlspecialchars($_POST['genre']);
        $year = intval($_POST['year']);
        $cover = htmlspecialchars($_POST['cover']);
        $status = htmlspecialchars($_POST['status']);
        $description = htmlspecialchars($_POST['description']);

        $stmt = $conn->prepare("INSERT INTO books (title, author, genre, year, cover, status, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $title, $author, $genre, $year, $cover, $status, $description);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Book added successfully']);
        } else {
            echo json_encode(['error' => 'Failed to add book']);
        }
        
        $stmt->close();
        break;

    case 'get_books':
        $result = $conn->query("SELECT * FROM books");
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        break;

    case 'add_user':
        if (!isset($_POST['fullname'], $_POST['email'], $_POST['password'])) {
            echo json_encode(['error' => 'Missing required fields']);
            exit;
        }

        $fullname = htmlspecialchars($_POST['fullname']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = password_hash(password: $_POST['password'], PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fullname, $email, $password);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'User added successfully']);
        } else {
            echo json_encode(['error' => 'Failed to add user']);
        }
        
        $stmt->close();
        break;

    case 'get_users':
        $result = $conn->query("SELECT id, fullname, email FROM users");
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
}

$conn->close();
?>
