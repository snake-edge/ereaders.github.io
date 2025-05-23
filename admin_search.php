<?php
// admin_dashboard.php
require_once 'db.php';

// Check if a search query is submitted
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $conn->real_escape_string($_POST['search']);
}

// Fetch books based on search query (if any)
$sql_books = "SELECT * FROM books WHERE title LIKE '%$search_query%' OR author LIKE '%$search_query%' ORDER BY title";
$result_books = $conn->query($sql_books);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 p-8">
  <!-- Books Table -->
  <div id="books-table">
    <table class="min-w-full bg-gray-100 shadow-md rounded-lg">
      <thead>
        <tr class="bg-green-600 text-white">
          <th class="py-2 px-4">Title</th>
          <th class="py-2 px-4">Author</th>
          <th class="py-2 px-4">Published Date</th>
          <th class="py-2 px-4">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($book = $result_books->fetch_assoc()): ?>
        <tr class="border-t border-gray-300">
          <td class="py-2 px-4"><?= htmlspecialchars($book['title']) ?></td>
          <td class="py-2 px-4"><?= htmlspecialchars($book['author']) ?></td>
          <td class="py-2 px-4"><?= htmlspecialchars($book['published_date']) ?></td>
          <td class="py-2 px-4">
            <button onclick='openEditModal(<?= htmlspecialchars(json_encode($book)) ?>)' class="text-yellow-500 hover:underline mr-2">Edit</button>
            <form action="delete_book.php" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this book?')">
              <input type="hidden" name="id" value="<?= $book['id'] ?>">
              <button type="submit" class="text-red-500 hover:underline">Delete</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Add Book Modal -->
  <div id="add-book-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <form id="add-book-form" action="save_book.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg w-1/2 flex">
      
      <!-- Left side: Cover Image -->
      <div class="w-1/3 pr-4">
        <label class="block mb-2 text-center text-gray-800">Cover Image</label>
        <div class="mb-4 text-center">
          <img id="add-book-cover-image" src="" alt="Cover image preview" class="max-w-full max-h-48 mx-auto object-contain mb-2 hidden">
        </div>
        <input type="file" name="cover_image" class="w-full mb-4 bg-gray-200 text-gray-800">
      </div>
      
      <!-- Right side: Book Details Form -->
      <div class="w-2/3">
        <h2 class="text-2xl font-bold mb-4">Add Book</h2>

        <label class="block mb-2">Title</label>
        <input type="text" name="title" class="w-full p-2 border rounded mb-4 bg-gray-200 text-gray-800" required>

        <label class="block mb-2">Author</label>
        <input type="text" name="author" class="w-full p-2 border rounded mb-4 bg-gray-200 text-gray-800" required>

        <label class="block mb-2">Published Date</label>
        <input type="date" name="published_date" class="w-full p-2 border rounded mb-4 bg-gray-200 text-gray-800" required>

        <label class="block mb-2">Description</label>
        <textarea name="description" class="w-full p-2 border rounded mb-4 bg-gray-200 text-gray-800"></textarea>

        <label class="block mb-2">Link</label>
        <input type="url" name="link" class="w-full p-2 border rounded mb-4 bg-gray-200 text-gray-800">

        <div class="flex justify-end gap-2">
          <button type="button" onclick="closeModal('add-book-modal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
          <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Save</button>
        </div>
      </div>
    </form>
  </div>

  <!-- Edit Book Modal -->
  <div id="edit-book-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <form id="edit-book-form" action="save_book.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg w-1/2 flex">
      
      <!-- Left side: Cover Image -->
      <div class="w-1/3 pr-4">
        <label class="block mb-2 text-center text-gray-800">Cover Image</label>
        <div id="edit-book-cover-image-container" class="mb-4 text-center">
          <img id="edit-book-cover-image" src="" alt="Current cover image" class="max-w-full max-h-48 mx-auto object-contain mb-2 hidden">
        </div>
        <input type="file" name="cover_image" class="w-full mb-4 bg-gray-200 text-gray-800">
      </div>
      
      <!-- Right side: Book Details Form -->
      <div class="w-2/3">
        <input type="hidden" name="id" id="book-id">
        <h2 class="text-2xl font-bold mb-4">Edit Book</h2>

        <label class="block mb-2">Title</label>
        <input type="text" name="title" id="book-title" class="w-full p-2 border rounded mb-4 bg-gray-200 text-gray-800" required>

        <label class="block mb-2">Author</label>
        <input type="text" name="author" id="book-author" class="w-full p-2 border rounded mb-4 bg-gray-200 text-gray-800" required>

        <label class="block mb-2">Published Date</label>
        <input type="date" name="published_date" id="book-date" class="w-full p-2 border rounded mb-4 bg-gray-200 text-gray-800" required>

        <label class="block mb-2">Description</label>
        <textarea name="description" id="book-description" class="w-full p-2 border rounded mb-4 bg-gray-200 text-gray-800"></textarea>

        <label class="block mb-2">Link</label>
        <input type="url" name="link" id="book-link" class="w-full p-2 border rounded mb-4 bg-gray-200 text-gray-800">

        <div class="flex justify-end gap-2">
          <button type="button" onclick="closeModal('edit-book-modal')" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
          <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Save</button>
        </div>
      </div>
    </form>
  </div>

<script>
    // Function to update the table content based on the search
    function updateTable(searchQuery) {
      const formData = new FormData();
      formData.append('search', searchQuery);

      fetch('admin_search.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        document.getElementById('books-table').innerHTML = data;
      });
    }

    // Event listener for the search input
    document.getElementById('search-bar').addEventListener('input', function() {
      const searchQuery = this.value;
      updateTable(searchQuery);
    });

    function openAddModal() {
      document.getElementById('add-book-form').reset();
      document.getElementById('add-book-cover-image').style.display = 'none'; // Hide image preview
      document.getElementById('add-book-modal').classList.remove('hidden');
    }

    function openEditModal(book) {
      // Ensure all modal fields are populated with the book data
      document.getElementById('book-id').value = book.id;
      document.getElementById('book-title').value = book.title;
      document.getElementById('book-author').value = book.author;
      document.getElementById('book-date').value = book.published_date;
      document.getElementById('book-description').value = book.description;
      document.getElementById('book-link').value = book.link;

      const coverImage = book.cover_image ? `uploads/${book.cover_image}` : ''; 
      const coverImageElement = document.getElementById('edit-book-cover-image');
      
      if (coverImage) {
        coverImageElement.src = coverImage;
        coverImageElement.style.display = 'block'; // Show the image
      } else {
        coverImageElement.style.display = 'none'; // Hide the image if none exists
      }

      // Make sure the modal is visible
      document.getElementById('edit-book-modal').classList.remove('hidden');
    }

    function closeModal(modalId) {
      document.getElementById(modalId).classList.add('hidden');
    }
</script>
</body>
</html>
