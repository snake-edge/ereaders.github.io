// Mock data for books and users
let books = [
  {
    id: "1",
    title: "To Kill a Mockingbird",
    author: "Harper Lee",
    genre: "Fiction",
    publicationYear: 1960,
    description: "The story of a young girl confronting racism in the American South.",
    coverImage: "https://images.unsplash.com/photo-1488590528505-98d2b5aba04b?w=300&h=400&fit=crop"
  },
  {
    id: "2",
    title: "1984",
    author: "George Orwell",
    genre: "Science Fiction",
    publicationYear: 1949,
    description: "A dystopian novel set in a totalitarian society.",
    coverImage: "https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=300&h=400&fit=crop"
  },
  {
    id: "3",
    title: "The Great Gatsby",
    author: "F. Scott Fitzgerald",
    genre: "Fiction",
    publicationYear: 1925,
    description: "A story set in the Jazz Age, exploring themes of wealth and the American Dream.",
    coverImage: "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=300&h=400&fit=crop"
  }
];

let users = [
  {
    id: "1",
    fullName: "Admin User",
    email: "admin@example.com",
    password: "password123" // In a real application, this would be hashed
  },
  {
    id: "2",
    fullName: "Jane Smith",
    email: "jane@example.com",
    password: "password123"
  },
  {
    id: "3",
    fullName: "John Doe",
    email: "john@example.com",
    password: "password123"
  }
];

// DOM Elements
const sidebar = document.getElementById('sidebar');
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');
const mainContent = document.getElementById('mainContent');
const navButtons = document.querySelectorAll('.nav-button');
const pages = document.querySelectorAll('.page');

// Book Elements
const booksTableBody = document.getElementById('booksTableBody');
const bookSearch = document.getElementById('bookSearch');
const addBookBtn = document.getElementById('addBookBtn');
const bookForm = document.getElementById('bookForm');
const bookFormTitle = document.getElementById('bookFormTitle');
const bookSubmitText = document.getElementById('bookSubmitText');
const backToBooksList = document.getElementById('backToBooksList');
const cancelBookBtn = document.getElementById('cancelBookBtn');
const coverImageInput = document.getElementById('coverImage');
const coverPreview = document.getElementById('coverPreview');

// User Elements
const usersTableBody = document.getElementById('usersTableBody');
const userSearch = document.getElementById('userSearch');
const addUserBtn = document.getElementById('addUserBtn');
const userForm = document.getElementById('userForm');
const userFormTitle = document.getElementById('userFormTitle');
const userSubmitText = document.getElementById('userSubmitText');
const backToUsersList = document.getElementById('backToUsersList');
const cancelUserBtn = document.getElementById('cancelUserBtn');
const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');
const passwordHelp = document.getElementById('passwordHelp');

// Toast Element
const toast = document.getElementById('toast');

// Current state
let currentPage = 'dashboard';
let currentBookId = null;
let currentUserId = null;
let isEditingBook = false;
let isEditingUser = false;

// Event Listeners
document.addEventListener('DOMContentLoaded', init);

// Initialize the application
function init() {
  setupEventListeners();
  loadBooks();
  loadUsers();
}

// Setup all event listeners
function setupEventListeners() {
  // Sidebar toggle for mobile
  mobileMenuBtn.addEventListener('click', toggleSidebar);
  toggleSidebarBtn.addEventListener('click', toggleSidebar);
  
  // Navigation
  navButtons.forEach(button => {
    button.addEventListener('click', () => {
      const page = button.getAttribute('data-page');
      navigateTo(page);
    });
  });
  
  // Book search
  bookSearch.addEventListener('input', filterBooks);
  
  // Book actions
  addBookBtn.addEventListener('click', showAddBookForm);
  backToBooksList.addEventListener('click', () => navigateTo('books'));
  cancelBookBtn.addEventListener('click', () => navigateTo('books'));
  bookForm.addEventListener('submit', saveBook);
  
  // Cover image preview
  coverImageInput.addEventListener('input', updateCoverPreview);
  
  // User search
  userSearch.addEventListener('input', filterUsers);
  
  // User actions
  addUserBtn.addEventListener('click', showAddUserForm);
  backToUsersList.addEventListener('click', () => navigateTo('users'));
  cancelUserBtn.addEventListener('click', () => navigateTo('users'));
  userForm.addEventListener('submit', saveUser);
  
  // Password toggle
  togglePassword.addEventListener('click', togglePasswordVisibility);
}

// Toggle sidebar visibility
function toggleSidebar() {
  sidebar.classList.toggle('active');
}

// Navigate to a specific page
function navigateTo(page) {
  currentPage = page;
  
  // Hide all pages
  pages.forEach(p => p.classList.add('hidden'));
  
  // Show the selected page
  const selectedPage = document.getElementById(`${page}-page`);
  if (selectedPage) selectedPage.classList.remove('hidden');
  
  // Update active button
  navButtons.forEach(button => {
    const buttonPage = button.getAttribute('data-page');
    if (buttonPage === page || 
        (page === 'addBook' || page === 'editBook') && buttonPage === 'books' ||
        (page === 'addUser' || page === 'editUser') && buttonPage === 'users') {
      button.classList.add('active');
    } else {
      button.classList.remove('active');
    }
  });
  
  // Close mobile sidebar after navigation
  if (window.innerWidth < 769) {
    sidebar.classList.remove('active');
  }
}

// Load books into the table
function loadBooks() {
  booksTableBody.innerHTML = '';
  
  if (books.length === 0) {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td colspan="6" class="text-center py-8 text-gray-500">
        No books found. Try adding a new book.
      </td>
    `;
    booksTableBody.appendChild(row);
    return;
  }
  
  books.forEach(book => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>
        <img src="${book.coverImage}" alt="Cover of ${book.title}" class="w-16 h-20 object-cover rounded">
      </td>
      <td class="font-medium">${book.title}</td>
      <td>${book.author}</td>
      <td>${book.genre}</td>
      <td>${book.publicationYear}</td>
      <td>
        <div class="flex space-x-2">
          <button class="action-button edit-button" data-id="${book.id}" title="Edit">
            <i class="fas fa-edit"></i>
          </button>
          <button class="action-button delete-button" data-id="${book.id}" title="Delete">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </td>
    `;
    
    // Add event listeners to the action buttons
    const editButton = row.querySelector('.edit-button');
    editButton.addEventListener('click', () => editBook(book.id));
    
    const deleteButton = row.querySelector('.delete-button');
    deleteButton.addEventListener('click', () => deleteBook(book.id));
    
    booksTableBody.appendChild(row);
  });
}

// Filter books based on search input
function filterBooks() {
  const searchTerm = bookSearch.value.toLowerCase();
  const filteredBooks = books.filter(book => 
    book.title.toLowerCase().includes(searchTerm) ||
    book.author.toLowerCase().includes(searchTerm) ||
    book.genre.toLowerCase().includes(searchTerm)
  );
  
  booksTableBody.innerHTML = '';
  
  if (filteredBooks.length === 0) {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td colspan="6" class="text-center py-8 text-gray-500">
        No books found. Try a different search or add a new book.
      </td>
    `;
    booksTableBody.appendChild(row);
    return;
  }
  
  filteredBooks.forEach(book => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>
        <img src="${book.coverImage}" alt="Cover of ${book.title}" class="w-16 h-20 object-cover rounded">
      </td>
      <td class="font-medium">${book.title}</td>
      <td>${book.author}</td>
      <td>${book.genre}</td>
      <td>${book.publicationYear}</td>
      <td>
        <div class="flex space-x-2">
          <button class="action-button edit-button" data-id="${book.id}" title="Edit">
            <i class="fas fa-edit"></i>
          </button>
          <button class="action-button delete-button" data-id="${book.id}" title="Delete">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </td>
    `;
    
    // Add event listeners to the action buttons
    const editButton = row.querySelector('.edit-button');
    editButton.addEventListener('click', () => editBook(book.id));
    
    const deleteButton = row.querySelector('.delete-button');
    deleteButton.addEventListener('click', () => deleteBook(book.id));
    
    booksTableBody.appendChild(row);
  });
}

// Show the add book form
function showAddBookForm() {
  bookFormTitle.textContent = 'Add New Book';
  bookSubmitText.textContent = 'Save Book';
  bookForm.reset();
  currentBookId = null;
  isEditingBook = false;
  updateCoverPreview();
  
  // Show the book form page
  pages.forEach(p => p.classList.add('hidden'));
  document.getElementById('book-form-page').classList.remove('hidden');
}

// Edit a book
function editBook(id) {
  const book = books.find(b => b.id === id);
  if (!book) return;
  
  bookFormTitle.textContent = 'Edit Book';
  bookSubmitText.textContent = 'Update Book';
  
  document.getElementById('title').value = book.title;
  document.getElementById('author').value = book.author;
  document.getElementById('genre').value = book.genre;
  document.getElementById('publicationYear').value = book.publicationYear;
  document.getElementById('description').value = book.description;
  document.getElementById('coverImage').value = book.coverImage;
  
  currentBookId = id;
  isEditingBook = true;
  updateCoverPreview();
  
  // Show the book form page
  pages.forEach(p => p.classList.add('hidden'));
  document.getElementById('book-form-page').classList.remove('hidden');
}

// Update the cover preview
function updateCoverPreview() {
  const imageUrl = document.getElementById('coverImage').value;
  if (imageUrl.trim() !== '') {
    coverPreview.innerHTML = `<img src="${imageUrl}" alt="Cover Preview">`;
  } else {
    coverPreview.innerHTML = '';
  }
}

// Save a book
function saveBook(event) {
  event.preventDefault();
  
  const title = document.getElementById('title').value;
  const author = document.getElementById('author').value;
  const genre = document.getElementById('genre').value;
  const publicationYear = parseInt(document.getElementById('publicationYear').value);
  const description = document.getElementById('description').value;
  const coverImage = document.getElementById('coverImage').value;
  
  if (isEditingBook) {
    // Update existing book
    const index = books.findIndex(b => b.id === currentBookId);
    if (index !== -1) {
      books[index] = {
        ...books[index],
        title,
        author,
        genre,
        publicationYear,
        description,
        coverImage
      };
      
      showToast(`Book "${title}" has been successfully updated.`);
    }
  } else {
    // Add new book
    const newBook = {
      id: Date.now().toString(),
      title,
      author,
      genre,
      publicationYear,
      description,
      coverImage
    };
    
    books.push(newBook);
    showToast(`Book "${title}" has been successfully added.`);
  }
  
  loadBooks();
  navigateTo('books');
}

// Delete a book
function deleteBook(id) {
  if (confirm('Are you sure you want to delete this book?')) {
    const index = books.findIndex(b => b.id === id);
    if (index !== -1) {
      const title = books[index].title;
      books.splice(index, 1);
      loadBooks();
      showToast(`Book "${title}" has been successfully deleted.`);
    }
  }
}

// Load users into the table
function loadUsers() {
  usersTableBody.innerHTML = '';
  
  if (users.length === 0) {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td colspan="3" class="text-center py-8 text-gray-500">
        No users found. Try adding a new user.
      </td>
    `;
    usersTableBody.appendChild(row);
    return;
  }
  
  users.forEach(user => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td class="font-medium">${user.fullName}</td>
      <td>${user.email}</td>
      <td>
        <div class="flex space-x-2">
          <button class="action-button edit-button" data-id="${user.id}" title="Edit">
            <i class="fas fa-edit"></i>
          </button>
          <button class="action-button delete-button" data-id="${user.id}" title="Delete">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </td>
    `;
    
    // Add event listeners to the action buttons
    const editButton = row.querySelector('.edit-button');
    editButton.addEventListener('click', () => editUser(user.id));
    
    const deleteButton = row.querySelector('.delete-button');
    deleteButton.addEventListener('click', () => deleteUser(user.id));
    
    usersTableBody.appendChild(row);
  });
}

// Filter users based on search input
function filterUsers() {
  const searchTerm = userSearch.value.toLowerCase();
  const filteredUsers = users.filter(user => 
    user.fullName.toLowerCase().includes(searchTerm) ||
    user.email.toLowerCase().includes(searchTerm)
  );
  
  usersTableBody.innerHTML = '';
  
  if (filteredUsers.length === 0) {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td colspan="3" class="text-center py-8 text-gray-500">
        No users found. Try a different search or add a new user.
      </td>
    `;
    usersTableBody.appendChild(row);
    return;
  }
  
  filteredUsers.forEach(user => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td class="font-medium">${user.fullName}</td>
      <td>${user.email}</td>
      <td>
        <div class="flex space-x-2">
          <button class="action-button edit-button" data-id="${user.id}" title="Edit">
            <i class="fas fa-edit"></i>
          </button>
          <button class="action-button delete-button" data-id="${user.id}" title="Delete">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </td>
    `;
    
    // Add event listeners to the action buttons
    const editButton = row.querySelector('.edit-button');
    editButton.addEventListener('click', () => editUser(user.id));
    
    const deleteButton = row.querySelector('.delete-button');
    deleteButton.addEventListener('click', () => deleteUser(user.id));
    
    usersTableBody.appendChild(row);
  });
}

// Show the add user form
function showAddUserForm() {
  userFormTitle.textContent = 'Add New User';
  userSubmitText.textContent = 'Save User';
  userForm.reset();
  currentUserId = null;
  isEditingUser = false;
  passwordHelp.classList.add('hidden');
  passwordInput.required = true;
  
  // Show the user form page
  pages.forEach(p => p.classList.add('hidden'));
  document.getElementById('user-form-page').classList.remove('hidden');
}

// Edit a user
function editUser(id) {
  const user = users.find(u => u.id === id);
  if (!user) return;
  
  userFormTitle.textContent = 'Edit User';
  userSubmitText.textContent = 'Update User';
  
  document.getElementById('fullName').value = user.fullName;
  document.getElementById('email').value = user.email;
  document.getElementById('password').value = '';
  
  currentUserId = id;
  isEditingUser = true;
  passwordHelp.classList.remove('hidden');
  passwordInput.required = false;
  
  // Show the user form page
  pages.forEach(p => p.classList.add('hidden'));
  document.getElementById('user-form-page').classList.remove('hidden');
}

// Toggle password visibility
function togglePasswordVisibility() {
  const type = passwordInput.type === 'password' ? 'text' : 'password';
  passwordInput.type = type;
  
  // Update the eye icon
  const icon = togglePassword.querySelector('i');
  if (type === 'text') {
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
}

// Save a user
function saveUser(event) {
  event.preventDefault();
  
  const fullName = document.getElementById('fullName').value;
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  
  if (isEditingUser) {
    // Update existing user
    const index = users.findIndex(u => u.id === currentUserId);
    if (index !== -1) {
      users[index] = {
        ...users[index],
        fullName,
        email,
        // Only update password if provided
        ...(password ? { password } : {})
      };
      
      showToast(`User "${fullName}" has been successfully updated.`);
    }
  } else {
    // Add new user
    const newUser = {
      id: Date.now().toString(),
      fullName,
      email,
      password
    };
    
    users.push(newUser);
    showToast(`User "${fullName}" has been successfully added.`);
  }
  
  loadUsers();
  navigateTo('users');
}

// Delete a user
function deleteUser(id) {
  if (confirm('Are you sure you want to delete this user?')) {
    const index = users.findIndex(u => u.id === id);
    if (index !== -1) {
      const fullName = users[index].fullName;
      users.splice(index, 1);
      loadUsers();
      showToast(`User "${fullName}" has been successfully deleted.`);
    }
  }
}

// Show a toast message
function showToast(message, type = 'success') {
  toast.textContent = message;
  toast.className = 'toast';
  
  if (type === 'error') {
    toast.classList.add('error');
  }
  
  toast.classList.remove('hidden');
  
  setTimeout(() => {
    toast.classList.add('hidden');
  }, 3000);
}
