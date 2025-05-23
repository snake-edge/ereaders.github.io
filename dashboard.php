<?php
session_start();
require_once 'db.php';

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

// Get search query if present
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch books, join with favorites, and get the average rating
$sql_books = "SELECT books.*, favorites.id AS favorite_id, 
              COALESCE(AVG(ratings.rating), 0) AS average_rating
              FROM books
              LEFT JOIN favorites ON books.id = favorites.book_id AND favorites.user_id = $user_id
              LEFT JOIN ratings ON books.id = ratings.book_id";

if ($search_query) {
    $sql_books .= " WHERE books.title LIKE '%$search_query%' OR books.author LIKE '%$search_query%'";
}

$sql_books .= " GROUP BY books.id ORDER BY books.title";

$result_books = $conn->query($sql_books);

$books = [];
if ($result_books->num_rows > 0) {
    while ($row = $result_books->fetch_assoc()) {
        $books[] = $row;
    }
}

if (isset($_GET['search'])) {
    header('Content-Type: application/json');
    echo json_encode($books);
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en" class="transition-colors duration-300">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Dashboard</title>
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
        <!-- Dashboard Link -->
        <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700 dark:hover:bg-gray-700">🏠 Dashboard</a>
        
        <!-- My Favorites Link -->
        <a href="my_favorites.php" class="block py-2 px-4 rounded hover:bg-blue-700 dark:hover:bg-gray-700">❤️ My Favorites</a>

        <!-- Logout Button -->
        <button 
          class="w-full bg-red-600 hover:bg-red-500 text-white py-2 rounded" 
          onclick="window.location.href='login.php';">
          Logout
        </button>
      </nav>
    </aside>

    <!-- Main -->
    <main class="flex-1 p-8">
      <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold" style="color: #004d00;">
      Welcome, <?= htmlspecialchars($user['full_name']) ?>!
    </h1>
        <div class="flex items-center gap-4">
          <span class="text-gray-600 dark:text-gray-300">Last login: April 14, 2025</span>
        </div>
      </div>

    <!-- Search Bar -->
    <div class="mb-6">
      <input 
        type="text" 
        id="search-input" 
        placeholder="Search for books or authors..." 
        class="p-2 border rounded-md w-full text-black"
        onkeyup="searchBooks()"
      />
    </div>

<!-- Books List -->
<div id="book-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  <?php if (count($books) > 0): ?>
    <?php foreach ($books as $book): ?>
      <div class="bg-white shadow-md rounded-lg p-4 hover:shadow-lg hover:scale-105 transition-transform duration-300">
        <div class="text-lg font-semibold text-green-800 cursor-pointer" onclick="viewBookDetails(<?= $book['id'] ?>)">
          <?= htmlspecialchars($book['title']) ?>
        </div>
        <div class="text-gray-700 cursor-pointer" onclick="viewBookDetails(<?= $book['id'] ?>)">
          by <?= htmlspecialchars($book['author']) ?>
        </div>

<!-- Display the rating stars -->
<div class="flex items-center mt-2">
  <p class="mr-2 font-semibold text-gray-700">User Ratings:</p>
  <?php for ($i = 1; $i <= 5; $i++): ?>
    <span 
      class="star text-xl <?= $book['average_rating'] >= $i ? 'text-yellow-500' : 'text-gray-400' ?>"
      data-book-id="<?= $book['id'] ?>"
      data-rating="<?= $i ?>">
      ★
    </span>
  <?php endfor; ?>
  <span class="ml-2 text-gray-600">(<?= round($book['average_rating'], 1) ?>)</span>
</div>


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
    <p class="col-span-full text-center text-gray-500">No books found.</p>
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
  function searchBooks() {
  const searchQuery = document.getElementById('search-input').value;

  // Always make the request, even if searchQuery is empty
  fetch(`dashboard.php?search=${encodeURIComponent(searchQuery)}`)
    .then(response => response.json())
    .then(data => {
      displayBooks(data);
    })
    .catch(error => {
      console.error('Error fetching books:', error);
    });
}

function displayBooks(books) {
  const bookList = document.getElementById('book-list');
  bookList.innerHTML = ''; // Clear existing books

  if (books.length === 0) {
    bookList.innerHTML = '<p class="col-span-full text-center text-gray-500">No books found.</p>';
    return;
  }

  books.forEach(book => {
    const bookElement = document.createElement('div');
    bookElement.classList.add('bg-white', 'shadow-md', 'rounded-lg', 'p-4', 'hover:shadow-lg', 'transition', 'hover:scale-105', 'transition-transform', 'duration-300');

    // Title (with view-details class)
    const bookTitle = document.createElement('div');
    bookTitle.classList.add('text-lg', 'font-semibold', 'text-green-800', 'cursor-pointer', 'view-details');
    bookTitle.textContent = book.title;
    bookTitle.setAttribute('data-book-id', book.id);

    // Author (with view-details class)
    const bookAuthor = document.createElement('div');
    bookAuthor.classList.add('text-gray-700', 'cursor-pointer', 'view-details');
    bookAuthor.textContent = `by ${book.author}`;
    bookAuthor.setAttribute('data-book-id', book.id);

// Rating stars
const ratingDiv = document.createElement('div');
ratingDiv.classList.add('flex', 'items-center', 'mt-2');

// Label before stars
const label = document.createElement('span');
label.textContent = 'User Ratings: ';
label.classList.add('mr-2', 'text-base', 'text-gray-700', 'font-semibold'); // added font-semibold
ratingDiv.appendChild(label);

// Stars
for (let i = 1; i <= 5; i++) {
  const star = document.createElement('span');
  star.classList.add('star', 'text-xl', 'cursor-pointer');
  star.classList.add(book.average_rating >= i ? 'text-yellow-500' : 'text-gray-400');
  star.setAttribute('data-book-id', book.id);
  star.setAttribute('data-rating', i);
  star.textContent = '★';
  star.addEventListener('click', (event) => rateBook(event, star));
  ratingDiv.appendChild(star);
}

    const ratingText = document.createElement('span');
    ratingText.classList.add('ml-2', 'text-gray-600');
    ratingText.textContent = `(${parseFloat(book.average_rating).toFixed(1)})`;
    ratingDiv.appendChild(ratingText);

    // Book Cover with overlay
    const coverDiv = document.createElement('div');
    coverDiv.classList.add('relative', 'group', 'mb-2');

    const bookCover = document.createElement('img');
    bookCover.src = `uploads/${book.cover_image}`;
    bookCover.alt = 'Book Cover';
    bookCover.classList.add('w-full', 'h-60', 'object-cover', 'rounded-md', 'mt-2', 'cursor-pointer', 'transition', 'duration-300', 'group-hover:brightness-50', 'view-details');
    bookCover.setAttribute('data-book-id', book.id);

    const viewDetailsDiv = document.createElement('div');
    viewDetailsDiv.classList.add('absolute', 'inset-0', 'flex', 'items-center', 'justify-center', 'opacity-0', 'group-hover:opacity-100', 'transition', 'duration-300', 'cursor-pointer', 'view-details');
    viewDetailsDiv.setAttribute('data-book-id', book.id);

    const viewDetailsText = document.createElement('span');
    viewDetailsText.classList.add('text-white', 'text-lg', 'font-semibold', 'bg-black', 'bg-opacity-50', 'px-4', 'py-2', 'rounded');
    viewDetailsText.textContent = 'View Details';
    viewDetailsDiv.appendChild(viewDetailsText);

    coverDiv.appendChild(bookCover);
    coverDiv.appendChild(viewDetailsDiv);

    // Favorite button
    const favoriteButton = document.createElement('button');
    favoriteButton.classList.add('favorite-btn', 'text-2xl', 'transition', 'duration-200');
    favoriteButton.classList.add(book.favorite_id ? 'text-red-600' : 'text-gray-400');
    favoriteButton.setAttribute('data-book-id', book.id);
    favoriteButton.title = book.favorite_id ? 'Remove from Favorites' : 'Add to Favorites';
    favoriteButton.textContent = book.favorite_id ? '❤️' : '🤍';
    favoriteButton.addEventListener('click', (event) => toggleFavorite(event, favoriteButton));

    const favoriteDiv = document.createElement('div');
    favoriteDiv.classList.add('absolute', 'top-2', 'right-2');
    favoriteDiv.appendChild(favoriteButton);
    coverDiv.appendChild(favoriteDiv);

    // Combine all elements
    bookElement.appendChild(bookTitle);
    bookElement.appendChild(bookAuthor);
    bookElement.appendChild(ratingDiv);
    bookElement.appendChild(coverDiv);
    bookList.appendChild(bookElement);
  });

  // Reattach click listeners for view details (delegation alternative)
  document.querySelectorAll('.view-details').forEach(el => {
    el.addEventListener('click', () => {
      const bookId = el.getAttribute('data-book-id');
      viewBookDetails(bookId);
    });
  });
}


  function round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
  }

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