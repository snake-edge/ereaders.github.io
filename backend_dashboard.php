<?php
// Database connection
$connection = new mysqli('localhost', 'username', 'password', 'database_name');

// Check if the connection is successful
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Query to fetch all books
$sql = "SELECT * FROM books";
$result = $connection->query($sql);

// Check if there are books in the database
if ($result->num_rows > 0) {
    echo "<div class='book-list'>";
    
    // Loop through all books and display them
    while($book = $result->fetch_assoc()) {
        // Fetch the cover image URL from the database
        $coverImage = $book['cover_image'];

        // Check if cover image is set, if not use a default image
        if (empty($coverImage)) {
            $coverImage = 'https://source.unsplash.com/random/300x500?book';  // Default image
        }

        // Display the book cover, title, and other information
        echo "<div class='book'>";
        echo "<img src='$coverImage' alt='Book Cover' class='book-cover' style='width:200px;height:auto;'>";  // Book cover image
        echo "<h3>" . $book['title'] . "</h3>";  // Book title
        echo "<p>Author: " . $book['author'] . "</p>";  // Book author
        echo "<p>Genre: " . $book['genre'] . "</p>";  // Book genre
        echo "<p>Publication Year: " . $book['publication_year'] . "</p>";  // Publication year
        echo "</div>";
    }
    
    echo "</div>";
} else {
    echo "No books found.";
}

// Close the connection
$connection->close();
?>
