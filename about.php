<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/config.php';
require_once 'includes/auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: landing.php');
    exit;
}

// Get current user
$currentUser = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - HITFLIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="img/Logo.png">
</head>

<body>
    
  <?php
  include "navbar.php";
  include_once "connection.php";
  ?>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header Section -->
                <div class="text-center mb-5">
                    <h1 class="display-4 text-warning mb-3">
                        <i class="fas fa-info-circle"></i> About HITFLIX
                    </h1>
                    <p class="lead text-light">Your Ultimate Movie Entertainment Platform</p>
                </div>

                <!-- Mission Section -->
                <div class="card bg-dark border-warning mb-5">
                    <div class="card-body p-5">
                        <h2 class="text-warning mb-4"><i class="fas fa-bullseye"></i> Our Mission</h2>
                        <p class="fs-5 text-light">
                            At HITFLIX, we believe that discovering great movies and TV shows should be an exciting adventure, not a chore. 
                            Our mission is to revolutionize how movie enthusiasts discover, explore, and enjoy cinema by combining 
                            cutting-edge technology with the timeless joy of storytelling.
                        </p>
                        <p class="text-light">
                            We're passionate about creating a community where film lovers can come together to share their love for cinema, 
                            challenge their knowledge, and always find something amazing to watch.
                        </p>
                    </div>
                </div>

                <!-- What We Offer Section -->
                <div class="row mb-5">
                    <div class="col-12">
                        <h2 class="text-warning mb-4"><i class="fas fa-star"></i> What We Offer</h2>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card bg-dark border-secondary h-100">
                            <div class="card-body">
                                <h5 class="text-warning"><i class="fas fa-dice"></i> Smart Discovery</h5>
                                <p class="text-light">
                                    Our intelligent recommendation system helps you discover movies perfectly matched to your mood, 
                                    preferences, and available time. No more endless scrolling through options.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card bg-dark border-secondary h-100">
                            <div class="card-body">
                                <h5 class="text-warning"><i class="fas fa-gamepad"></i> Interactive Games</h5>
                                <p class="text-light">
                                    Test your movie knowledge with our engaging games including movie guessing challenges, 
                                    quote identification, and trivia contests that make movie discovery fun.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card bg-dark border-secondary h-100">
                            <div class="card-body">
                                <h5 class="text-warning"><i class="fas fa-check-square"></i> Progress Tracking</h5>
                                <p class="text-light">
                                    Never lose your place in TV series again. Our comprehensive tracking system helps you 
                                    monitor your viewing progress across all your favorite shows.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card bg-dark border-secondary h-100">
                            <div class="card-body">
                                <h5 class="text-warning"><i class="fas fa-users"></i> Social Features</h5>
                                <p class="text-light">
                                    Plan movie nights with friends, create voting polls for group decisions, and share your 
                                    favorite discoveries with fellow movie enthusiasts.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Our Story Section -->
                <div class="card bg-dark border-warning mb-5">
                    <div class="card-body p-5">
                        <h2 class="text-warning mb-4"><i class="fas fa-book"></i> Our Story</h2>
                        <p class="text-light">
                            HITFLIX was born from a simple frustration: spending more time deciding what to watch than actually watching. 
                            Our team of movie enthusiasts and tech innovators came together with a vision to solve this universal problem.
                        </p>
                        <p class="text-light">
                            Launched in 2024, we've already helped thousands of users discover their next favorite movies and shows. 
                            We combine the vast database of The Movie Database (TMDB) with our own intelligent algorithms to provide 
                            personalized recommendations that actually work.
                        </p>
                        <p class="text-light">
                            What started as a weekend project has evolved into a comprehensive entertainment platform that celebrates 
                            the art of cinema while making it more accessible and enjoyable for everyone.
                        </p>
                    </div>
                </div>

                <!-- Technology Section -->
                <div class="row mb-5">
                    <div class="col-md-8">
                        <h2 class="text-warning mb-4"><i class="fas fa-cogs"></i> Powered by Innovation</h2>
                        <p class="text-light">
                            HITFLIX leverages cutting-edge technology to deliver the best possible user experience:
                        </p>
                        <ul class="text-light">
                            <li><strong>Advanced Filtering:</strong> Multi-parameter search that considers genre, mood, runtime, and more</li>
                            <li><strong>Real-time Data:</strong> Always up-to-date movie and TV show information from TMDB</li>
                            <li><strong>Smart Algorithms:</strong> Machine learning-powered recommendations that improve with use</li>
                            <li><strong>Cross-platform Design:</strong> Seamless experience across desktop, tablet, and mobile devices</li>
                            <li><strong>Fast Performance:</strong> Optimized for speed with instant search and quick loading times</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <i class="fas fa-code display-1 text-warning mb-3"></i>
                            <p class="text-light">Built with modern web technologies for the best possible performance and user experience.</p>
                        </div>
                    </div>
                </div>

                <!-- Values Section -->
                <div class="card bg-dark border-warning mb-5">
                    <div class="card-body p-5">
                        <h2 class="text-warning mb-4"><i class="fas fa-heart"></i> Our Values</h2>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <h5 class="text-warning">🎬 Passion for Cinema</h5>
                                <p class="text-light">We're genuine movie lovers who understand what makes a great film experience.</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h5 class="text-warning">🚀 Innovation First</h5>
                                <p class="text-light">We constantly push boundaries to create better ways to discover entertainment.</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h5 class="text-warning">👥 Community Focused</h5>
                                <p class="text-light">Building a welcoming space where all movie enthusiasts can connect and share.</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h5 class="text-warning">🔒 Privacy Respected</h5>
                                <p class="text-light">Your data and privacy are protected with industry-leading security measures.</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h5 class="text-warning">🆓 Free & Accessible</h5>
                                <p class="text-light">Great movie discovery should be available to everyone, without barriers.</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h5 class="text-warning">🌟 Quality Focus</h5>
                                <p class="text-light">We prioritize quality over quantity in every feature we build.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Future Vision Section -->
                <div class="text-center mb-5">
                    <h2 class="text-warning mb-4"><i class="fas fa-telescope"></i> Looking Ahead</h2>
                    <p class="fs-5 text-light">
                        We're just getting started. Our roadmap includes exciting features like AI-powered mood detection, 
                        virtual movie nights, enhanced social features, and partnerships with streaming platforms to make 
                        watching as easy as discovering.
                    </p>
                    <p class="text-light">
                        Join us on this journey to revolutionize how the world discovers and enjoys movies and TV shows.
                    </p>
                </div>

                <!-- Call to Action -->
                <div class="text-center">
                    <div class="card bg-warning text-dark">
                        <div class="card-body p-4">
                            <h3><i class="fas fa-rocket"></i> Ready to Discover Your Next Favorite Movie?</h3>
                            <p class="mb-3">Join thousands of movie lovers who have already found their perfect entertainment with HITFLIX.</p>
                            <a href="index.php" class="btn btn-dark btn-lg me-3">
                                <i class="fas fa-home"></i> Go to Dashboard
                            </a>
                            <a href="contact.php" class="btn btn-outline-dark btn-lg">
                                <i class="fas fa-envelope"></i> Get in Touch
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
