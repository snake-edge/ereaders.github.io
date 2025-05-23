<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Readers Library</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="icon" href="assets/logo.png" type="image/x-icon" />
</head>
<body>
    <header>
        <div class="logo">
            E-Readers <i class="fas fa-book-open"></i>
        </div>
        <nav class="desktop-nav">
            <a href="index.php" class="active">Home</a>
            <a href="#about">About Us</a>
            <a href="#contact">Contact</a>
            <a href="login.php">Login</a>
            <a href="register.php" class="register-btn">Register</a>
        </nav>
        <button class="mobile-menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="mobile-nav">
            <a href="index.html" class="active">Home</a>
            <a href="#about">About Us</a>
            <a href="#contact">Contact</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </div>
    </header>
    
    <section class="hero">
        <div class="hero-content">
            <h1 class="fade-in">Discover Your Next Great Read</h1>
            <p class="fade-in">Access thousands of books with personalized recommendations tailored to your reading preferences.</p>
            <div class="button-group fade-in">
                <a href="register.html" class="btn btn-primary">Get Started</a>
                <a href="#about" class="btn btn-secondary">Learn More</a>
            </div>
        </div>
    </section>

    <section class="features" id="about">
        <div class="container">
            <h2 class="section-title">Why Choose E-Readers</h2>
            <p class="section-subtitle">Our platform offers unique features to enhance your reading experience.</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3 class="feature-title">Extensive Library</h3>
                    <p class="feature-description">Access thousands of books across various genres and languages.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">Personalized Recommendations</h3>
                    <p class="feature-description">Get book suggestions tailored to your reading preferences and history.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bookmark"></i>
                    </div>
                    <h3 class="feature-title">Reading Tracking</h3>
                    <p class="feature-description">Track your reading progress and set goals for your literary journey.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-swatchbook"></i>
                    </div>
                    <h3 class="feature-title">Curated Collections</h3>
                    <p class="feature-description">Discover hand-picked collections from literary experts and influencers.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <h2 class="cta-title">Ready to Start Your Reading Journey?</h2>
            <p class="cta-description">Join thousands of readers who have already discovered their next favorite book through E-Readers.</p>
            <a href="register.html" class="btn btn-light">Sign Up Now - It's Free</a>
        </div>
    </section>

    <section class="contact" id="contact">
        <div class="container">
            <h2 class="section-title">Contact Us</h2>
            <p class="section-subtitle">Have questions or suggestions? We'd love to hear from you!</p>
            
            <div class="contact-info">
                <div class="contact-method">
                    <i class="fas fa-envelope"></i>
                    <p>Email: support@ereaders.com</p>
                </div>
                <div class="contact-method">
                    <i class="fas fa-phone"></i>
                    <p>Phone: +123-456-7890</p>
                </div>
            </div>
        </div>
    </section>
    
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>E-Readers SPUSM</h3>
                    <p>Your destination for personalized book recommendations and a curated reading experience.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <span id="current-year"></span> E-Readers SPUSM. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.gpteng.co/gptengineer.js" type="module"></script>
    <script src="script.js"></script>
</body>
</html>
