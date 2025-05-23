<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in and has a valid user_id in the session
if (isset($_SESSION['user_id'])) {
    // Return the user_id in JSON format
    echo json_encode(['user_id' => $_SESSION['user_id']]);
} else {
    // If the user is not logged in, return an error message
    echo json_encode(['error' => 'User not logged in']);
}
?>
