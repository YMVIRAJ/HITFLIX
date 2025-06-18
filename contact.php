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

// Handle form submission success message
$success_message = '';
$error_message = '';

if (isset($_SESSION['contact_success'])) {
    $success_message = $_SESSION['contact_success'];
    unset($_SESSION['contact_success']);
}

if (isset($_SESSION['contact_error'])) {
    $error_message = $_SESSION['contact_error'];
    unset($_SESSION['contact_error']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - HITFLIX</title>
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
                        <i class="fas fa-envelope"></i> Contact Us
                    </h1>
                    <p class="lead text-light">We'd love to hear from you! Get in touch with the HITFLIX team.</p>
                </div>

                <!-- Success/Error Messages -->
                <?php if ($success_message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success_message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error_message): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error_message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Contact Form -->
                    <div class="col-lg-8 mb-5">
                        <div class="card bg-dark border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h3 class="mb-0"><i class="fas fa-paper-plane"></i> Send us a Message</h3>
                            </div>
                            <div class="card-body p-4">
                                <form action="process_contact.php" method="POST" id="contactForm">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label text-light">Full Name *</label>
                                            <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                                   value="<?= htmlspecialchars($currentUser['name']) ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label text-light">Email Address *</label>
                                            <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                                   value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="subject" class="form-label text-light">Subject *</label>
                                        <select class="form-select form-select-lg" id="subject" name="subject" required>
                                            <option value="">Choose a subject...</option>
                                            <option value="General Question">General Question</option>
                                            <option value="Feature Request">Feature Request</option>
                                            <option value="Bug Report">Bug Report</option>
                                            <option value="Account Issue">Account Issue</option>
                                            <option value="Privacy Concern">Privacy Concern</option>
                                            <option value="Partnership Inquiry">Partnership Inquiry</option>
                                            <option value="Media Inquiry">Media Inquiry</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="message" class="form-label text-light">Message *</label>
                                        <textarea class="form-control" id="message" name="message" rows="6" 
                                                  placeholder="Tell us how we can help you..." required></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                                            <label class="form-check-label text-light" for="newsletter">
                                                Subscribe to our newsletter for movie recommendations and updates
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-warning btn-lg">
                                        <i class="fas fa-paper-plane"></i> Send Message
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="col-lg-4">
                        <!-- Quick Contact -->
                        <div class="card bg-dark border-secondary mb-4">
                            <div class="card-body">
                                <h5 class="text-warning mb-3"><i class="fas fa-info-circle"></i> Quick Contact</h5>
                                <div class="mb-3">
                                    <i class="fas fa-envelope text-warning me-2"></i>
                                    <span class="text-light">hitflixprime@gmail.com.com</span>
                                </div>
                                <div class="mb-3">
                                    <i class="fas fa-clock text-warning me-2"></i>
                                    <span class="text-light">Response within 24 hours</span>
                                </div>
                                <div class="mb-3">
                                    <i class="fas fa-globe text-warning me-2"></i>
                                    <span class="text-light">Available worldwide</span>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Quick Links -->
                        <div class="card bg-dark border-secondary mb-4">
                            <div class="card-body">
                                <h5 class="text-warning mb-3"><i class="fas fa-question-circle"></i> Common Questions</h5>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item bg-transparent border-0 px-0">
                                        <small class="text-light">
                                            <strong>How do I reset my password?</strong><br>
                                            Visit the login page and click "Forgot Password"
                                        </small>
                                    </div>
                                    <div class="list-group-item bg-transparent border-0 px-0">
                                        <small class="text-light">
                                            <strong>Is HITFLIX free to use?</strong><br>
                                            Yes! All features are completely free
                                        </small>
                                    </div>
                                    <div class="list-group-item bg-transparent border-0 px-0">
                                        <small class="text-light">
                                            <strong>How do I suggest new features?</strong><br>
                                            Use the contact form with "Feature Request" subject
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="card bg-dark border-secondary">
                            <div class="card-body text-center">
                                <h5 class="text-warning mb-3"><i class="fas fa-share-alt"></i> Follow Us</h5>
                                <p class="text-light mb-3">Stay updated with the latest features and movie recommendations</p>
                                <div class="d-flex justify-content-center gap-3">
                                    <a href="https://www.youtube.com/@HitFlix23" class="btn btn-outline-warning btn-sm">
                                        <i class="fab fa-youtube"></i> YouTube
                                    </a>
                                    <a href="https://web.facebook.com/hitflix.prime?_rdc=2&_rdr#" class="btn btn-outline-warning btn-sm">
                                        <i class="fab fa-facebook"></i> Facebook
                                    </a>
                                    <a href="https://www.tiktok.com/@hitflix23?_t=8o6fRuiLqK8&_r=1" class="btn btn-outline-warning btn-sm">
                                        <i class="fab fa-tiktok"></i> TikTok
                                    </a>
                                    <a href="https://www.instagram.com/hitflix_prime/" class="btn btn-outline-warning btn-sm">
                                        <i class="fab fa-instagram"></i> Instagram
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Contact Options -->
                <div class="row mt-5">
                    <div class="col-12">
                        <h3 class="text-warning mb-4"><i class="fas fa-headset"></i> Other Ways to Reach Us</h3>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-dark border-secondary h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-bug fa-3x text-warning mb-3"></i>
                                <h5 class="text-warning">Report a Bug</h5>
                                <p class="text-light">Found something not working? Help us fix it by reporting bugs directly.</p>
                                <button class="btn btn-outline-warning" onclick="setSubject('Bug Report')">
                                    Report Bug
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-dark border-secondary h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-lightbulb fa-3x text-warning mb-3"></i>
                                <h5 class="text-warning">Suggest Features</h5>
                                <p class="text-light">Have an idea for a new feature? We'd love to hear your suggestions!</p>
                                <button class="btn btn-outline-warning" onclick="setSubject('Feature Request')">
                                    Suggest Feature
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card bg-dark border-secondary h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-handshake fa-3x text-warning mb-3"></i>
                                <h5 class="text-warning">Business Inquiries</h5>
                                <p class="text-light">Interested in partnerships or business opportunities? Get in touch!</p>
                                <button class="btn btn-outline-warning" onclick="setSubject('Partnership Inquiry')">
                                    Business Contact
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Function to set subject and scroll to form
        function setSubject(subject) {
            document.getElementById('subject').value = subject;
            document.getElementById('contactForm').scrollIntoView({ behavior: 'smooth' });
            document.getElementById('message').focus();
        }

        // Form validation
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value.trim();

            if (!name || !email || !subject || !message) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }

            if (message.length < 10) {
                e.preventDefault();
                alert('Please provide a more detailed message (at least 10 characters).');
                return false;
            }
        });
    </script>
</body>

</html>
