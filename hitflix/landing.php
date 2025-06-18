<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/config.php';
require_once 'includes/auth.php';

// If already logged in, redirect to main app
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HITFLIX - Your Ultimate Movie Entertainment Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg landing-navbar" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">HITFLIX</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">FAQ</a>
                    </li>
                </ul>
                <a href="auth/login.php" class="btn-signin">Sign In</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Carousel -->
<section class="landing-hero">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

        <!-- Carousel Items -->
        <div class="carousel-inner">
            
            <!-- Slide 1 - Queen's Gambit -->
            <div class="carousel-item active">
                <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('https://image.tmdb.org/t/p/original/34OGjFEbHj0E3lE2w0iTUVq0CBz.jpg');">
                </div>
            </div>

            <!-- Slide 2 - Fantastic 4 -->
            <div class="carousel-item active">
                <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('https://image.tmdb.org/t/p/original/azhhngEdDatterotJqVbTB2O1lH.jpg');">
                </div>
            </div>

            <!-- Slide 3 - How to train your dragon -->
            <div class="carousel-item">
                <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('https://image.tmdb.org/t/p/original/7HqLLVjdjhXS0Qoz1SgZofhkIpE.jpg');">
                </div>
            </div>
            
            <!-- Slide 4 - The Dark Knight -->
            <div class="carousel-item">
                <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('https://image.tmdb.org/t/p/original/y2DB71C4nyIdMrANijz8mzvQtk6.jpg');">
                </div>
            </div>
            
            <!-- Slide 5 - Inception -->
            <div class="carousel-item">
                <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('https://image.tmdb.org/t/p/original/s3TBrRGB1iav7gFOCNx3H31MoES.jpg');">
                </div>
            </div>
            
            <!-- Slide 5 - God father -->
            <div class="carousel-item">
                <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('https://image.tmdb.org/t/p/original/tmU7GeKVybMWFButWEGl2M4GeiP.jpg');">
                </div>
            </div>
            
            <!-- Slide 5 - Iron man -->
            <div class="carousel-item">
                <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('https://image.tmdb.org/t/p/original/iVped1djsF0tvGkvnHbzsE3ZPTF.jpg');">
                </div>
            </div>
            
            <!-- Slide 5 - Avatar -->
            <div class="carousel-item">
                <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('https://image.tmdb.org/t/p/original/kU98MbVVgi72wzceyrEbClZmMFe.jpg');">
                </div>
            </div>
            
            <!-- Slide 5 - Spiderman -->
            <div class="carousel-item">
                <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('https://image.tmdb.org/t/p/original/w1oD1MzHjnBJc5snKupIQaSBLIh.jpg');">
                </div>
            </div>
            
            <!-- Slide 5 - Hulk -->
            <div class="carousel-item">
                <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('https://image.tmdb.org/t/p/original/jPu8yiadqgzwFPGKJmGo637ASVP.jpg');">
                </div>
            </div>
        </div>

    </div>

    <!-- Hero Content (stays on top) -->
    <div class="landing-hero-content">
        <h1>Unlimited movies, games, and entertainment.</h1>
        <p>Discover, play, and explore the world of cinema like never before.</p>
        <a href="auth/register.php" class="btn-get-started">Get Started</a>
    </div>
</section>


    <!-- Features Section -->
    <section class="landing-features" id="features">
        <div class="container">
            <!-- Feature 1 -->
            <div class="row landing-feature-row">
                <div class="col-lg-6">
                    <div class="landing-feature-text">
                        <h2>Discover amazing movies</h2>
                        <p>Our intelligent recommendation system helps you find your next favorite film. From hidden gems to blockbusters, explore an endless world of cinema.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="landing-feature-image">
                        <div class="landing-movie-grid">
                            <div class="landing-movie-card"><b>Action</b></div>
                            <div class="landing-movie-card"><b>Comedy</b></div>
                            <div class="landing-movie-card"><b>Drama</b></div>
                            <div class="landing-movie-card"><b>Sci-Fi</b></div>
                            <div class="landing-movie-card"><b>Thriller</b></div>
                            <div class="landing-movie-card"><b>Romance</b></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="row landing-feature-row">
                <div class="col-lg-6 order-lg-2">
                    <div class="landing-feature-text">
                        <h2>Interactive movie games</h2>
                        <p>Test your movie knowledge with our engaging games. From guessing movies by quotes to identifying films from screenshots - challenge yourself and friends.</p>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="landing-feature-image">
                        <div class="text-center">
                            <i class="fas fa-gamepad" style="font-size: 150px; color: var(--primary-color);"></i>
                            <div class="mt-3">
                                <div class="badge bg-warning text-dark fs-6 me-2">Guess the Movie</div>
                                <div class="badge bg-success fs-6 me-2">Quote Challenge</div>
                                <div class="badge bg-info fs-6">Movie Trivia</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="row landing-feature-row">
                <div class="col-lg-6">
                    <div class="landing-feature-text">
                        <h2>Smart filtering & search</h2>
                        <p>Find exactly what you're looking for with advanced filters. Search by genre, year, rating, or mood. Our intelligent system understands what you want to watch.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="landing-feature-image">
                        <div class="text-center">
                            <i class="fas fa-search" style="font-size: 120px; color: var(--primary-color); margin-bottom: 20px;"></i>
                            <div>
                                <div class="badge bg-primary fs-6 me-2 mb-2">Genre Filter</div>
                                <div class="badge bg-secondary fs-6 me-2 mb-2">Year Range</div>
                                <div class="badge bg-success fs-6 me-2 mb-2">Rating Filter</div>
                                <div class="badge bg-warning text-dark fs-6 mb-2">Mood Based</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="landing-faq" id="faq">
        <div class="container">
            <h2>Frequently Asked Questions</h2>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            What is HITFLIX?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            HITFLIX is an interactive movie entertainment platform that combines movie discovery, games, and social features. Explore movies, test your knowledge, and connect with fellow film enthusiasts.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            How do I get started?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Simply click "Get Started" to create your free account. Once registered, you'll have access to all features including movie discovery, games, and personalized recommendations.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            What kind of games are available?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We offer various interactive games including movie quote challenges, "guess the movie" games, trivia contests, and recommendation challenges. New games are added regularly!
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            Is HITFLIX free to use?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes! HITFLIX is completely free to use. Create an account and enjoy all features without any subscription fees or hidden costs.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="container">
            <div class="landing-footer-content">
                <p>&copy; 2025 HITFLIX. Your Ultimate Movie Entertainment Platform.</p>
                <p class="mt-2">
                    <a href="auth/register.php" class="text-decoration-none me-3" style="color: var(--primary-color);">Get Started</a>
                    <a href="auth/login.php" class="text-decoration-none" style="color: var(--primary-color);">Sign In</a>
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        // Initialize landing page functionality
        document.addEventListener('DOMContentLoaded', function() {
            initLandingPage();
        });
    </script>
</body>
</html>