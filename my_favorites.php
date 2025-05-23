<?php
require_once 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the users table
$sql_user = "SELECT full_name, email FROM users WHERE id = $user_id";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();


// Fetch favorite books for the user
$sql_favorites = "SELECT books.*, favorites.id AS favorite_id,
                  COALESCE(AVG(ratings.rating), 0) AS average_rating
                  FROM books
                  JOIN favorites ON books.id = favorites.book_id
                  LEFT JOIN ratings ON books.id = ratings.book_id
                  WHERE favorites.user_id = $user_id
                  GROUP BY books.id, favorites.id";

$result_favorites = $conn->query($sql_favorites);

$favorites = [];
if ($result_favorites->num_rows > 0) {
    while ($row = $result_favorites->fetch_assoc()) {
        $favorites[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en" class="transition-colors duration-300">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Favorites</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="icon" href="assets/logo.png" type="image/x-icon" />
</head>
<body class="bg-[#d3d3d3] text-gray-800 dark:text-gray-100 font-sans transition-colors duration-300">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#004d00] text-white flex flex-col">
    <div class="p-6 text-2xl font-bold" style="color: #FFD700; font-family: 'Inter', sans-serif;">E-readers</div>
      <nav class="flex-1 px-4 space-y-4 mt-6">
        <a href="dashboard.php" class="block py-2 px-4 rounded hover:bg-blue-700 dark:hover:bg-gray-700">🏠 Dashboard</a>
        <a href="my_favorites.php" class="block py-2 px-4 rounded hover:bg-blue-700 dark:hover:bg-gray-700">❤️ My Favorites</a>
    <!-- Logout Button -->
        <button 
          class="w-full bg-red-600 hover:bg-red-500 text-white py-2 rounded" 
          onclick="window.location.href='login.php';">
          Logout
        </button>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
      <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold" style="color: #004d00;">My Favorite Books</h1>
      </div>

<!-- Favorite Books List -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  <?php if (count($favorites) > 0): ?>
    <?php foreach ($favorites as $book): ?>
      <div class="bg-white shadow-md rounded-lg p-4 hover:shadow-lg transition hover:scale-105 transition-transform duration-300">
        <!-- Book Title -->
        <div class="text-lg font-semibold text-green-800 cursor-pointer" onclick="viewBookDetails(<?= $book['id'] ?>)">
          <?= htmlspecialchars($book['title']) ?>
        </div>

        <!-- Book Author -->
        <div class="text-gray-700 cursor-pointer" onclick="viewBookDetails(<?= $book['id'] ?>)">
          by <?= htmlspecialchars($book['author']) ?>
        </div>

        <!-- Display the rating stars -->
        <div class="flex items-center mt-2">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <span 
              class="star text-xl cursor-pointer <?= $book['average_rating'] >= $i ? 'text-yellow-500' : 'text-gray-400' ?>"
              data-book-id="<?= $book['id'] ?>"
              data-rating="<?= $i ?>"
              onclick="rateBook(event, this)">
              ★
            </span>
          <?php endfor; ?>
          <span class="ml-2 text-gray-600">(<?= round($book['average_rating'], 1) ?>)</span>
        </div>

        <!-- Book Cover Image -->
        <div class="relative group mb-2">
          <img src="uploads/<?= htmlspecialchars($book['cover_image']) ?>" alt="Book Cover" class="w-full h-60 object-cover rounded-md mt-2 cursor-pointer transition duration-300 group-hover:brightness-50" onclick="viewBookDetails(<?= $book['id'] ?>)">
          <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 cursor-pointer" onclick="viewBookDetails(<?= $book['id'] ?>)">
            <span class="text-white text-lg font-semibold bg-black bg-opacity-50 px-4 py-2 rounded">View Details</span>
          </div>
          <div class="absolute top-2 right-2">
            <button 
              class="favorite-btn text-2xl transition duration-200 <?= $book['favorite_id'] ? 'text-red-600' : 'text-gray-400' ?>" 
              data-book-id="<?= $book['id'] ?>" 
              title="<?= $book['favorite_id'] ? 'Remove from Favorites' : 'Add to Favorites' ?>"
              onclick="toggleFavorite(event, this)">
              <?= $book['favorite_id'] ? '❤️' : '🤍' ?>
            </button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="col-span-full text-center text-gray-500">You have no favorite books.</p>
  <?php endif; ?>
</div>


<!-- Book Detail Modal -->
<div id="book-modal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
  <div class="bg-white text-gray-900 p-6 rounded-xl shadow-xl w-1/2 flex relative">
    <div class="flex-none w-64 h-96 mr-6">
      <img id="modal-cover" src="" alt="Book Cover" class="w-full h-full object-cover rounded-md">
    </div>
    <div class="flex-1">
      <h2 id="modal-title" class="text-2xl font-bold mb-4"></h2>
      <p><strong>Author:</strong> <span id="modal-author"></span></p>
      <p><strong>Date published:</strong> <span id="modal-date"></span></p>
      <p><strong>Description:</strong> <span id="modal-description"></span></p>
      <p><strong>Link:</strong> <a id="modal-link" href="#" target="_blank" class="text-blue-600 hover:underline">More Info</a></p>
      <!-- Rating stars in modal -->
      <div class="flex items-center mt-4">
      <p><strong>Rate it: </strong></p>
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <span 
            class="star text-xl cursor-pointer text-gray-400"
            data-book-id="<?= $bookId ?>"
            data-rating="<?= $i ?>"
            onclick="rateBook(event, this)">
            ★
          </span>
        <?php endfor; ?>
        <span class="ml-2 text-gray-600" id="modal-rating">(0)</span>
        <button id="remove-rating" class="mt-2 text-sm text-red-600 hover:underline">Remove Rating</button>
      </div>
      <button id="close-modal" class="mt-4 bg-red-600 hover:bg-red-500 text-white py-2 px-4 rounded">Close</button>
    </div>
  </div>
</div>

<script>
 const currentUserId = <?= $_SESSION['user_id'] ?? 0 ?>;
 function viewBookDetails(bookId) {
  fetch(`get_book_details.php?book_id=${bookId}&user_id=${currentUserId}`)
    .then(response => response.json())
    .then(book => {
      document.getElementById('modal-title').textContent = book.title;
      document.getElementById('modal-cover').src = `uploads/${book.cover_image}`;
      document.getElementById('modal-author').textContent = book.author;
      document.getElementById('modal-date').textContent = book.published_date;
      document.getElementById('modal-description').textContent = book.description;
      document.getElementById('modal-link').href = book.link;
      document.getElementById('modal-link').textContent = book.link ? "Click here" : "No link available";
      document.getElementById('book-modal').classList.remove('hidden');

      // Update rating stars in the modal based on user's rating
      const userRating = parseInt(book.user_rating);
      const stars = document.querySelectorAll('#book-modal .star');
      stars.forEach(star => {
        const starValue = parseInt(star.getAttribute('data-rating'));
        if (starValue <= userRating) {
          star.classList.add('text-yellow-500');
          star.classList.remove('text-gray-400');
        } else {
          star.classList.remove('text-yellow-500');
          star.classList.add('text-gray-400');
        }
        star.setAttribute('data-book-id', book.id);
      });

      document.getElementById('modal-rating').textContent = '';
    });
}


    function toggleFavorite(event, btn) {
      event.stopPropagation();
      const bookId = btn.getAttribute('data-book-id');

      fetch('toggle_favorite.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `book_id=${bookId}`
      })
      .then(res => res.json())  // Parse the response as JSON
      .then(response => {
        if (response.status === 'added') {
          btn.textContent = '❤️';  // Filled heart when added
          btn.classList.remove('text-gray-400');
          btn.classList.add('text-red-600');
          btn.title = 'Remove from Favorites';
        } else if (response.status === 'removed') {
          btn.textContent = '🤍';  // Empty heart when removed
          btn.classList.remove('text-red-600');
          btn.classList.add('text-gray-400');
          btn.title = 'Add to Favorites';
        } else if (response.status === 'error') {
          console.error('Error:', response.message);
          alert('An error occurred: ' + response.message);  // Show error message to user
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
    }

    document.getElementById('close-modal').addEventListener('click', function () {
  // Close the modal
  document.getElementById('book-modal').classList.add('hidden');
  
  // Refresh the page
  location.reload();
});


    function rateBook(event, starElement) {
  event.stopPropagation();
  const bookId = starElement.getAttribute('data-book-id');
  const rating = starElement.getAttribute('data-rating');

  fetch('rate_book.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `book_id=${bookId}&rating=${rating}`
  })
  .then(res => res.json())  // Parse the response as JSON
  .then(response => {
    if (response.status === 'success') {
      // Update stars on the modal based on the rating
      const stars = starElement.parentElement.querySelectorAll('.star');
      stars.forEach(star => {
        if (parseInt(star.getAttribute('data-rating')) <= rating) {
          star.classList.add('text-yellow-500');
          star.classList.remove('text-gray-400');
        } else {
          star.classList.remove('text-yellow-500');
          star.classList.add('text-gray-400');
        }
      });
      document.getElementById('modal-rating').textContent = `(${parseFloat(rating).toFixed(1)})`;
    } else {
      console.error('Error:', response.message);
      alert('An error occurred: ' + response.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

document.getElementById('remove-rating').addEventListener('click', function () {
  const bookId = document.querySelector('#book-modal .star').getAttribute('data-book-id');

  fetch(`remove_rating.php?book_id=${bookId}&user_id=${currentUserId}`, {
    method: 'POST'
  })
  .then(response => response.json())
  .then(result => {
    if (result.success) {
      // Reset star UI
      const stars = document.querySelectorAll('#book-modal .star');
      stars.forEach(star => {
        star.classList.remove('text-yellow-500');
        star.classList.add('text-gray-400');
      });
      document.getElementById('modal-rating').textContent = '';
      alert('Rating removed successfully!');
    } else {
      alert('Failed to remove rating.');
    }
  });
});

</script>
</body>
</html>
