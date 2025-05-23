// Book data
const bookData = [
  {
    id: 1,
    title: "The Great Gatsby",
    author: "F. Scott Fitzgerald",
    coverImage: "http://localhost/my_project/assets/gatsby.jpg", // Replace with a valid URL
    description: "Set in the Jazz Age on Long Island, the novel depicts narrator Nick Carraway's interactions with mysterious millionaire Jay Gatsby and Gatsby's obsession to reunite with his former lover, Daisy Buchanan.",
    rating: 4.5,
    genre: ["Fiction", "Classic"],
    isNew: true,
    isRecommended: true,
    isBorrowed: false
  },
  {
    id: 2,
    title: "To Kill a Mockingbird",
    author: "Harper Lee",
    coverImage: "http://localhost/my_project/assets/mockingbird.jpg", // Replace with a valid URL
    description: "The unforgettable novel of a childhood in a sleepy Southern town and the crisis of conscience that rocked it.",
    rating: 4.8,
    genre: ["Fiction", "Classic"],
    isNew: false,
    isRecommended: true,
    isBorrowed: true
  },
  {
    id: 3,
    title: "1984",
    author: "George Orwell",
    coverImage: "http://localhost/my_project/assets/1984.jpg", // Replace with a valid URL
    description: "The story follows Winston Smith, a government employee whose job is to rewrite history to meet the demands of the Party.",
    rating: 4.6,
    genre: ["Science Fiction", "Dystopian"],
    isNew: false,
    isRecommended: true,
    isBorrowed: false
  },
  {
    id: 4,
    title: "Brave New World",
    author: "Aldous Huxley",
    coverImage: "http://localhost/my_project/assets/brave_new_world.jpg", // Replace with a valid URL
    description: "Far in the future, the World Controllers have created the ideal society.",
    rating: 4.3,
    genre: ["Science Fiction", "Dystopian"],
    isNew: true,
    isRecommended: false,
    isBorrowed: false
  },
  {
    id: 5,
    title: "Pride and Prejudice",
    author: "Jane Austen",
    coverImage: "http://localhost/my_project/assets/pride_and_prejudice.jpg", // Replace with a valid URL
    description: "The story follows Elizabeth Bennet as she deals with issues of manners, upbringing, morality, education, and marriage.",
    rating: 4.7,
    genre: ["Fiction", "Romance", "Classic"],
    isNew: false,
    isRecommended: true,
    isBorrowed: false
  },
  {
    id: 6,
    title: "The Hobbit",
    author: "J.R.R. Tolkien",
    coverImage: "http://localhost/my_project/assets/hobbit.jpg", // Replace with a valid URL
    description: "Bilbo Baggins is a hobbit who enjoys a comfortable, unambitious life.",
    rating: 4.8,
    genre: ["Fantasy", "Adventure"],
    isNew: false,
    isRecommended: true,
    isBorrowed: false
  },
  {
    id: 7,
    title: "The Catcher in the Rye",
    author: "J.D. Salinger",
    coverImage: "http://localhost/my_project/assets/catcher_in_the_rye.jpg", // Replace with a valid URL
    description: "The story of Holden Caulfield, a teenage boy who has been expelled from prep school.",
    rating: 4.2,
    genre: ["Fiction", "Coming of Age"],
    isNew: false,
    isRecommended: false,
    isBorrowed: true
  },
  {
    id: 8,
    title: "Sapiens: A Brief History of Humankind",
    author: "Yuval Noah Harari",
    coverImage: "http://localhost/my_project/assets/sapiens.jpg", // Replace with a valid URL
    description: "A groundbreaking narrative of humanity's creation and evolution.",
    rating: 4.7,
    genre: ["Non-Fiction", "History", "Science"],
    isNew: true,
    isRecommended: true,
    isBorrowed: false
  },
  {
    id: 9,
    title: "The Lord of the Rings",
    author: "J.R.R. Tolkien",
    coverImage: "http://localhost/my_project/assets/lotr.jpg", // Replace with a valid URL
    description: "An epic high-fantasy novel that tells the story of the One Ring.",
    rating: 4.9,
    genre: ["Fantasy", "Adventure"],
    isNew: false,
    isRecommended: true,
    isBorrowed: false
  },
  {
    id: 10,
    title: "The Alchemist",
    author: "Paulo Coelho",
    coverImage: "http://localhost/my_project/assets/alchemist.jpg", // Replace with a valid URL
    description: "Paulo Coelho's masterpiece tells the mystical story of Santiago.",
    rating: 4.5,
    genre: ["Fiction", "Fantasy", "Philosophy"],
    isNew: false,
    isRecommended: false,
    isBorrowed: false
  },
  {
    id: 11,
    title: "Dune",
    author: "Frank Herbert",
    coverImage: "http://localhost/my_project/assets/dune.jpg", // Replace with a valid URL
    description: "Set on the desert planet Arrakis, Dune is the story of Paul Atreides.",
    rating: 4.6,
    genre: ["Science Fiction", "Fantasy"],
    isNew: true,
    isRecommended: true,
    isBorrowed: false
  },
  {
    id: 12,
    title: "The Girl with the Dragon Tattoo",
    author: "Stieg Larsson",
    coverImage: "http://localhost/my_project/assets/dragon_tattoo.jpg", // Replace with a valid URL
    description: "Murder mystery, family saga, love story, and financial intrigue combine into one novel.",
    rating: 4.3,
    genre: ["Mystery", "Thriller"],
    isNew: false,
    isRecommended: true,
    isBorrowed: true
  }
];

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', () => {
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
  
  initSidebar();
  initSearch();
  initThemeToggle();
  initNavigation();
  populateBooks();
  initModals();
  initForms();
});

// Sidebar initialization
function initSidebar() {
  const sidebar = document.getElementById('sidebar');
  const collapseBtn = document.getElementById('collapse-btn');
  
  collapseBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
  });
  
  // Genre dropdown toggle
  const genreDropdownBtn = document.getElementById('genre-dropdown-btn');
  const genreDropdown = document.getElementById('genre-dropdown');
  
  genreDropdownBtn.addEventListener('click', () => {
    genreDropdownBtn.classList.toggle('active');
    genreDropdown.classList.toggle('active');
  });
}

// Search functionality
function initSearch() {
  const searchInput = document.getElementById('search-input');
  const clearSearchBtn = document.getElementById('clear-search');
  
  searchInput.addEventListener('input', () => {
    if (searchInput.value.trim() !== '') {
      clearSearchBtn.classList.remove('hidden');
      performSearch(searchInput.value);
    } else {
      clearSearchBtn.classList.add('hidden');
      populateBooks(); // Reset to show all books
    }
  });
  
  clearSearchBtn.addEventListener('click', () => {
    searchInput.value = '';
    clearSearchBtn.classList.add('hidden');
    populateBooks(); // Reset to show all books
  });
}

// Perform search on books
function performSearch(query) {
  query = query.toLowerCase();
  
  const filteredBooks = bookData.filter(book => 
    book.title.toLowerCase().includes(query) || 
    book.author.toLowerCase().includes(query) || 
    book.genre.some(g => g.toLowerCase().includes(query))
  );
  
  // Clear existing books
  const allBooksGrid = document.getElementById('all-books');
  allBooksGrid.innerHTML = '';
  
  // Show search results in all books section
  if (filteredBooks.length === 0) {
    allBooksGrid.innerHTML = `<div class="no-results">No books found matching "${query}"</div>`;
  } else {
    filteredBooks.forEach(book => {
      allBooksGrid.appendChild(createBookCard(book));
    });
  }
}

// Theme toggle functionality
function initThemeToggle() {
  const themeToggle = document.getElementById('theme-toggle');
  const sunIcon = document.getElementById('sun-icon');
  const moonIcon = document.getElementById('moon-icon');
  const darkModeSetting = document.getElementById('dark-mode-setting');
  
  // Check for saved theme preference or use system preference
  const savedTheme = localStorage.getItem('theme');
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  
  if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
    document.documentElement.classList.add('dark');
    sunIcon.classList.add('hidden');
    moonIcon.classList.remove('hidden');
    if (darkModeSetting) darkModeSetting.checked = true;
  }
  
  themeToggle.addEventListener('click', toggleTheme);
  
  // Also listen for changes on the settings page
  if (darkModeSetting) {
    darkModeSetting.addEventListener('change', () => {
      if (darkModeSetting.checked) {
        enableDarkMode();
      } else {
        disableDarkMode();
      }
    });
  }

  function toggleTheme() {
    if (document.documentElement.classList.contains('dark')) {
      disableDarkMode();
    } else {
      enableDarkMode();
    }
  }

  function enableDarkMode() {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
    sunIcon.classList.add('hidden');
    moonIcon.classList.remove('hidden');
    if (darkModeSetting) darkModeSetting.checked = true;
  }

  function disableDarkMode() {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
    moonIcon.classList.add('hidden');
    sunIcon.classList.remove('hidden');
    if (darkModeSetting) darkModeSetting.checked = false;
  }
}

// Navigation functionality
function initNavigation() {
  const navItems = document.querySelectorAll('.nav-item[data-section]');
  const pageSections = document.querySelectorAll('.section');
  const pageTitle = document.getElementById('page-title');
  
  navItems.forEach(item => {
    item.addEventListener('click', (e) => {
      e.preventDefault();
      
      // Remove active class from all nav items
      navItems.forEach(navItem => {
        navItem.classList.remove('active');
      });
      
      // Add active class to clicked nav item
      item.classList.add('active');
      
      // Hide all sections
      pageSections.forEach(section => {
        section.classList.remove('active');
      });
      
      // Show selected section
      const sectionId = `${item.dataset.section}-section`;
      document.getElementById(sectionId).classList.add('active');
      
      // Update page title
      pageTitle.textContent = item.querySelector('span').textContent;
    });
  });
  
  // Handle genre filter clicks
  const genreItems = document.querySelectorAll('.dropdown-item[data-genre]');
  
  genreItems.forEach(item => {
    item.addEventListener('click', (e) => {
      e.preventDefault();
      
      const genre = item.dataset.genre;
      filterBooksByGenre(genre);
      
      // Update page title
      pageTitle.textContent = `${capitalizeFirstLetter(genre)} Books`;
      
      // Show dashboard section
      pageSections.forEach(section => {
        section.classList.remove('active');
      });
      document.getElementById('dashboard-section').classList.add('active');
      
      // Update active nav item
      navItems.forEach(navItem => {
        navItem.classList.remove('active');
      });
      document.querySelector('.nav-item[data-section="dashboard"]').classList.add('active');
    });
  });
}

// Filter books by genre
function filterBooksByGenre(genre) {
  const filteredBooks = bookData.filter(book => 
    book.genre.some(g => g.toLowerCase() === genre.toLowerCase())
  );
  
  // Clear existing books
  const allBooksGrid = document.getElementById('all-books');
  allBooksGrid.innerHTML = '';
  
  // Show filtered books in all books section
  if (filteredBooks.length === 0) {
    allBooksGrid.innerHTML = `<div class="no-results">No books found in the "${genre}" genre.</div>`;
  } else {
    filteredBooks.forEach(book => {
      allBooksGrid.appendChild(createBookCard(book));
    });
  }
}

// Create a book card element
function createBookCard(book) {
  const bookCard = document.createElement('div');
  bookCard.className = 'book-card';
  bookCard.setAttribute('data-book-id', book.id);
  
  const statusClass = book.isBorrowed ? 'borrowed' : 'available';
  const statusText = book.isBorrowed ? 'Read' : 'Available';
  
  bookCard.innerHTML = `
    <div class="book-cover-wrapper">
      <img src="${book.coverImage}" alt="${book.title}" class="book-cover">
      <div class="book-cover-overlay">
        <div class="book-status ${statusClass}">${statusText}</div>
      </div>
    </div>
    <div class="book-details">
      <h3 class="book-title">${book.title}</h3>
      <p class="book-author">by ${book.author}</p>
      <div class="book-rating">
        ${getRatingStars(book.rating)}
      </div>
    </div>
  `;
  
  // Add click event to show book details
  bookCard.addEventListener('click', () => {
    showBookDetails(book);
  });
  
  return bookCard;
}

// Helper function to get rating stars
function getRatingStars(rating) {
  const fullStars = Math.floor(rating);
  const halfStar = rating % 1 >= 0.5 ? '<i data-lucide="star-half" class="star"></i>' : '';
  return `${'<i data-lucide="star" class="star"></i>'.repeat(fullStars)} ${halfStar} <span style="margin-left:4px;">${rating}</span>`;
}

// Initialize modals
function initModals() {
  const modal = document.getElementById('book-modal');
  const closeModal = document.getElementById('close-modal');
  
  closeModal.addEventListener('click', () => {
    modal.classList.remove('active');
  });
  
  // Close modal when clicking outside content
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.remove('active');
    }
  });
  
  // Close modal on escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      modal.classList.remove('active');
    }
  });
}

// Show book details in modal
function showBookDetails(book) {
  const modal = document.getElementById('book-modal');
  const modalBookTitle = document.getElementById('modal-book-title');
  const modalBookAuthor = document.getElementById('modal-book-author');
  const modalBookRating = document.getElementById('modal-book-rating');
  const modalBookGenres = document.getElementById('modal-book-genres');
  const modalBookDescription = document.getElementById('modal-book-description');
  const borrowBtn = document.getElementById('borrow-btn');
  
  // Set book details
  modalBookTitle.textContent = book.title;
  modalBookAuthor.textContent = `by ${book.author}`;
  
  // Set rating
  modalBookRating.innerHTML = getRatingStars(book.rating);
  
  // Set genres
  modalBookGenres.innerHTML = '';
  book.genre.forEach(genre => {
    const genreTag = document.createElement('span');
    genreTag.className = 'genre-tag';
    genreTag.textContent = genre;
    modalBookGenres.appendChild(genreTag);
  });
  
  // Set description
  modalBookDescription.textContent = book.description;
  
  // Update borrow button
  if (book.isBorrowed) {
    borrowBtn.textContent = 'Marked as Read';
    borrowBtn.className = 'btn borrowed-btn';
    borrowBtn.disabled = true;
  } else {
    borrowBtn.textContent = 'Mark as Read';
    borrowBtn.className = 'btn primary-btn';
    borrowBtn.disabled = false;
    
    // Add borrow functionality
    borrowBtn.onclick = () => {
      book.isBorrowed = true;
      showToast(`Marked as Read "${book.title}"`);
      modal.classList.remove('active');
      populateBooks(); // Refresh book displays
    };
  }
  
  // Show modal
  modal.classList.add('active');
}

// Populate books in all grids
function populateBooks() {
  // Recently added books (dashboard)
  const newBooksGrid = document.querySelector('#dashboard-section .book-grid');
  newBooksGrid.innerHTML = '';
  bookData
    .filter(book => book.isNew)
    .slice(0, 4)
    .forEach(book => {
      newBooksGrid.appendChild(createBookCard(book));
    });
  
  // Recommended books (dashboard)
  const recommendedBooksGrid = document.getElementById('recommended-books');
  recommendedBooksGrid.innerHTML = '';
  bookData
    .filter(book => book.isRecommended)
    .slice(0, 4)
    .forEach(book => {
      recommendedBooksGrid.appendChild(createBookCard(book));
    });
  
  // All books (dashboard)
  const allBooksGrid = document.getElementById('all-books');
  allBooksGrid.innerHTML = '';
  bookData.forEach(book => {
    allBooksGrid.appendChild(createBookCard(book));
  });
  
  // My books (my library page)
  const myBooksGrid = document.getElementById('my-books');
  myBooksGrid.innerHTML = '';
  bookData
    .filter(book => book.isBorrowed)
    .forEach(book => {
      myBooksGrid.appendChild(createBookCard(book));
    });
  
  // Available books (borrow/return page)
  const availableBooksGrid = document.getElementById('available-books');
  availableBooksGrid.innerHTML = '';
  bookData
    .filter(book => !book.isBorrowed)
    .forEach(book => {
      availableBooksGrid.appendChild(createBookCard(book));
    });
  
  // Borrowed books (borrow/return page)
  const borrowedBooksGrid = document.getElementById('borrowed-books');
  borrowedBooksGrid.innerHTML = '';
  bookData
    .filter(book => book.isBorrowed)
    .forEach(book => {
      borrowedBooksGrid.appendChild(createBookCard(book));
    });
  
  // New releases page
  const newReleasesGrid = document.getElementById('new-releases');
  newReleasesGrid.innerHTML = '';
  bookData
    .filter(book => book.isNew)
    .forEach(book => {
      newReleasesGrid.appendChild(createBookCard(book));
    });
  
  // Recommended page
  const recommendedPageGrid = document.getElementById('recommended-page-books');
  recommendedPageGrid.innerHTML = '';
  bookData
    .filter(book => book.isRecommended)
    .forEach(book => {
      recommendedPageGrid.appendChild(createBookCard(book));
    });
}

// Initialize form submissions
function initForms() {
  // Edit Profile Form
  const editProfileBtn = document.getElementById('edit-profile-btn');
  const editProfileForm = document.getElementById('edit-profile-form');
  const cancelEditBtn = document.getElementById('cancel-edit-btn');
  const profileForm = document.getElementById('profile-form');

  if (editProfileBtn && editProfileForm && cancelEditBtn && profileForm) {
    // Show edit form when button is clicked
    editProfileBtn.addEventListener('click', () => {
      editProfileForm.classList.remove('hidden');
    });

    // Hide edit form when cancel is clicked
    cancelEditBtn.addEventListener('click', () => {
      editProfileForm.classList.add('hidden');
    });

    // Handle form submission
    profileForm.addEventListener('submit', (e) => {
      e.preventDefault();
      
      // Get form values
      const name = document.getElementById('profile-name').value;
      const email = document.getElementById('profile-email').value;
      const password = document.getElementById('profile-password').value;
      
      // Here you would typically send this data to a server
      console.log('Profile updated:', { name, email, password });
      
      // Hide the form after submission
      editProfileForm.classList.add('hidden');
      
      // Show success message
      showToast('Profile updated successfully!');
      
      // Clear password field
      document.getElementById('profile-password').value = '';
    });
  }
  
  // Feedback form
  const feedbackForm = document.getElementById('feedback-form');
  if (feedbackForm) {
    feedbackForm.addEventListener('submit', (e) => {
      e.preventDefault();
      showToast('Thank you for your feedback!');
      feedbackForm.reset();
    });
  }
  
  // Admin login form
  const adminLoginForm = document.getElementById('admin-login-form');
  if (adminLoginForm) {
    adminLoginForm.addEventListener('submit', (e) => {
      e.preventDefault();
      showToast('Admin login successful!');
    });
  }
}

// Helper functions
function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

// Toast notification system
function showToast(message) {
  // Create toast element if it doesn't exist
  if (!document.getElementById('toast-container')) {
    const toastContainer = document.createElement('div');
    toastContainer.id = 'toast-container';
    toastContainer.style.cssText = `
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 2000;
    `;
    document.body.appendChild(toastContainer);
  }
  
  const toastContainer = document.getElementById('toast-container');
  
  // Create toast
  const toast = document.createElement('div');
  toast.style.cssText = `
    background-color: var(--primary);
    color: var(--primary-foreground);
    padding: 12px 20px;
    border-radius: var(--radius);
    margin-top: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    animation: slideIn 0.3s ease forwards;
    max-width: 300px;
  `;
  toast.textContent = message;
  
  // Add slide in animation
  const style = document.createElement('style');
  style.textContent = `
    @keyframes slideIn {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
    
    @keyframes slideOut {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(100%);
        opacity: 0;
      }
    }
  `;
  document.head.appendChild(style);
  
  // Add to container
  toastContainer.appendChild(toast);
  
  // Remove after 3 seconds
  setTimeout(() => {
    toast.style.animation = 'slideOut 0.3s ease forwards';
    setTimeout(() => {
      toast.remove();
    }, 300);
  }, 3000);
}