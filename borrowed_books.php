<?php
require_once 'db.php';

$sql = "SELECT bb.id, b.title, b.author, bb.borrowed_on, bb.due_date, bb.status, u.first_name, u.last_name, b.cover_image
        FROM borrowed_books bb
        JOIN books b ON bb.book_id = b.id
        JOIN users u ON bb.user_id = u.id
        ORDER BY bb.borrowed_on DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Borrowed Books</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-blue-800 dark:bg-gray-800 text-white flex flex-col">
          <div class="p-6 text-2xl font-bold">LibraTrack</div>
          <nav class="flex-1 px-4 space-y-4 mt-6">
            <a href="dashboard.php" class="block py-2 px-4 rounded hover:bg-blue-700 dark:hover:bg-gray-700">🏠 Dashboard</a>
            <a href="borrowed_books.php" class="block py-2 px-4 rounded hover:bg-blue-700 dark:hover:bg-gray-700">📚 Borrowed Books</a>
            <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700 dark:hover:bg-gray-700">🔍 Search Books</a>
            <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700 dark:hover:bg-gray-700">⚙️ Account Settings</a>
          </nav>
          <div class="p-4 border-t border-blue-600 dark:border-gray-700">
            <button class="w-full bg-red-600 hover:bg-red-500 text-white py-2 rounded">Logout</button>
          </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <h1 class="text-2xl font-bold mb-6">Borrowed Books</h1>

            <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-blue-800 dark:bg-gray-700 text-white">
                            <th class="text-left py-3 px-6">Book Title</th>
                            <th class="text-left py-3 px-6">Author</th>
                            <th class="text-left py-3 px-6">Borrower</th>
                            <th class="text-left py-3 px-6">Borrowed On</th>
                            <th class="text-left py-3 px-6">Due Date</th>
                            <th class="text-left py-3 px-6">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <td class="py-3 px-6">
                                        <a href="javascript:void(0);" onclick="openModal(<?= $row['id'] ?>, '<?= htmlspecialchars($row['title']) ?>', '<?= htmlspecialchars($row['author']) ?>', '<?= htmlspecialchars($row['borrowed_on']) ?>', '<?= htmlspecialchars($row['due_date']) ?>', '<?= htmlspecialchars($row['status']) ?>', '<?= htmlspecialchars($row['cover_image']) ?>')" class="text-blue-600 dark:text-blue-400 hover:underline">
                                            <?= htmlspecialchars($row["title"]) ?>
                                        </a>
                                    </td>
                                    <td class="py-3 px-6"><?= htmlspecialchars($row["author"]) ?></td>
                                    <td class="py-3 px-6"><?= htmlspecialchars($row["first_name"]) ?> <?= htmlspecialchars($row["last_name"]) ?></td>
                                    <td class="py-3 px-6"><?= htmlspecialchars($row["borrowed_on"]) ?></td>
                                    <td class="py-3 px-6"><?= htmlspecialchars($row["due_date"]) ?></td>
                                    <td class="py-3 px-6 font-semibold <?= $row["status"] == "Due Soon" ? "text-yellow-500" : ($row["status"] == "On Time" ? "text-green-500" : "text-red-500") ?>">
                                        <?= htmlspecialchars($row["status"]) ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="py-4 px-6 text-center">No borrowed books found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>

    </div>

    <!-- Modal -->
    <div id="book-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold mb-4" id="modal-title"></h2>
            <div class="flex justify-center mb-4">
                <img id="modal-cover" src="" alt="Book Cover" class="w-48 h-72 object-cover rounded-md">
            </div>
            <p id="modal-author" class="text-lg font-semibold mb-2"></p>
            <p><strong>Borrowed On:</strong> <span id="modal-borrowed-on"></span></p>
            <p><strong>Due Date:</strong> <span id="modal-due-date"></span></p>
            <p><strong>Status:</strong> <span id="modal-status"></span></p>
            <button onclick="closeModal()" class="mt-4 bg-red-500 text-white py-2 px-4 rounded-md">Close</button>
        </div>
    </div>

    <script>
        function openModal(id, title, author, borrowedOn, dueDate, status, coverImage) {
            document.getElementById("modal-title").innerText = title;
            document.getElementById("modal-author").innerText = "Author: " + author;
            document.getElementById("modal-borrowed-on").innerText = borrowedOn;
            document.getElementById("modal-due-date").innerText = dueDate;
            document.getElementById("modal-status").innerText = status;
            document.getElementById("modal-cover").src = coverImage;
            document.getElementById("book-modal").classList.remove("hidden");
        }

        function closeModal() {
            document.getElementById("book-modal").classList.add("hidden");
        }
    </script>

    <?php $conn->close(); ?>
</body>
</html>
