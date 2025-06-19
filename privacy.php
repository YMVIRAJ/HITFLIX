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
    <title>Privacy Policy - HITFLIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="img/Logo.png">
</head>

<body>
    
  <?php
//   include "navbar.php";
//   include_once "connection.php";
  ?>
<nav class="navbar bg-body-dark navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="?section=dashboard"><img src="img/logo.png" style="max-width: 45px;" alt="">
                <span style="font-weight: bolder;">HITFLIX</span>
                <span style="font-size: large;">PRIME</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end bg-dark" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title text-light" id="offcanvasNavbarLabel">
                        <span>HITFLIX</span>
                        <span style="font-size: small;">PRIME</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-light" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link <?= $active_section === 'dashboard' ? 'active' : '' ?>" href="?section=dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link <?= $active_section === 'random' ? 'active' : '' ?>" href="?section=random"><i class="fas fa-dice"></i> Random Movie</a></li>
                        <li class="nav-item"><a class="nav-link <?= $active_section === 'tonight' ? 'active' : '' ?>" href="?section=tonight"><i class="fas fa-bullseye"></i> Tonight's Pick</a></li>
                        <li class="nav-item"><a class="nav-link <?= $active_section === 'guess' ? 'active' : '' ?>" href="?section=guess"><i class="fas fa-question-circle"></i> Guess Game</a></li>
                        <li class="nav-item"><a class="nav-link <?= $active_section === 'quote' ? 'active' : '' ?>" href="?section=quote"><i class="fas fa-quote-left"></i> Movie Quotes</a></li>
                        <li class="nav-item"><a class="nav-link <?= $active_section === 'actors' ? 'active' : '' ?>" href="?section=actors"><i class="fas fa-star"></i> Top Actors</a></li>
                        <li class="nav-item"><a class="nav-link <?= $active_section === 'tracker' ? 'active' : '' ?>" href="?section=tracker"><i class="fas fa-check-square"></i> Series Tracker</a></li>
                        <li class="nav-item"><a class="nav-link <?= $active_section === 'planner' ? 'active' : '' ?>" href="?section=planner"><i class="fas fa-calendar"></i> Movie Night</a></li>
                        <li class="nav-item"><a class="nav-link <?= $active_section === 'search' ? 'active' : '' ?>" href="?section=search"><i class="fas fa-search"></i> Search Movies</a></li>
                        <li class="nav-item">
                            <hr class="text-muted my-2">
                        </li>
                        <li class="nav-item"><a class="nav-link" href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                        <li class="nav-item"><a class="nav-link" href="privacy.php"><i class="fas fa-shield-alt"></i> Privacy Policy</a></li>
                    </ul>
                    <hr class="dashboard-description">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-light">
                            <small>Welcome, <?= htmlspecialchars($currentUser['name']) ?>!</small>
                        </div>
                        <a href="auth/logout.php" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header Section -->
                <div class="text-center mb-5">
                    <h1 class="display-4 text-warning mb-3">
                        <i class="fas fa-shield-alt"></i> Privacy Policy
                    </h1>
                    <p class="lead text-light">Your privacy is important to us. This policy explains how we collect, use, and protect your information.</p>
                    <div class="alert alert-info">
                        <small><strong>Last Updated:</strong> June 18, 2025</small>
                    </div>
                </div>

                <!-- Quick Summary -->
                <div class="card bg-warning text-dark mb-5">
                    <div class="card-body p-4">
                        <h3><i class="fas fa-eye"></i> Quick Summary</h3>
                        <p class="mb-2">
                            <strong>HITFLIX respects your privacy.</strong> We collect minimal information needed to provide our movie discovery service, 
                            use it to improve your experience, and never sell your personal data to third parties. 
                            We use Google AdSense for advertising and comply with all data protection regulations.
                        </p>
                        <p class="mb-0">
                            <strong>Your rights:</strong> You can access, modify, or delete your data at any time by contacting us.
                        </p>
                    </div>
                </div>

                <!-- Table of Contents -->
                <div class="card bg-dark border-secondary mb-5">
                    <div class="card-body">
                        <h4 class="text-warning mb-3"><i class="fas fa-list"></i> Table of Contents</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><a href="#information-collected" class="text-light">1. Information We Collect</a></li>
                                    <li><a href="#how-we-use" class="text-light">2. How We Use Your Information</a></li>
                                    <li><a href="#sharing" class="text-light">3. Information Sharing</a></li>
                                    <!-- <li><a href="#adsense" class="text-light">4. Google AdSense & Advertising</a></li> -->
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><a href="#data-security" class="text-light">4. Data Security</a></li>
                                    <!-- <li><a href="#your-rights" class="text-light">6. Your Rights</a></li> -->
                                    <li><a href="#cookies" class="text-light">5. Cookies & Tracking</a></li>
                                    <li><a href="#contact-privacy" class="text-light">6. Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 1. Information We Collect -->
                <div id="information-collected" class="card bg-dark border-warning mb-4">
                    <div class="card-body">
                        <h3 class="text-warning mb-3">1. Information We Collect</h3>
                        
                        <h5 class="text-warning">Information You Provide:</h5>
                        <ul class="text-light">
                            <li><strong>Account Information:</strong> Name, email address, and password when you register</li>
                            <li><strong>Profile Data:</strong> Movie preferences, watchlists, and viewing history within our platform</li>
                            <li><strong>Communication Data:</strong> Messages you send through our contact form or customer support</li>
                            <li><strong>Optional Information:</strong> Newsletter subscription preferences</li>
                        </ul>

                        <h5 class="text-warning mt-4">Information Automatically Collected:</h5>
                        <ul class="text-light">
                            <li><strong>Usage Data:</strong> Pages visited, features used, time spent on the platform</li>
                            <li><strong>Device Information:</strong> Browser type, operating system, IP address, device identifiers</li>
                            <li><strong>Log Data:</strong> Server logs including access times, errors, and performance metrics</li>
                            <li><strong>Cookies & Local Storage:</strong> Preferences, session data, and analytics information</li>
                        </ul>

                        <h5 class="text-warning mt-4">Third-Party Information:</h5>
                        <ul class="text-light">
                            <li><strong>Movie Data:</strong> Information from The Movie Database (TMDB) API to provide movie recommendations</li>
                            <li><strong>Analytics Data:</strong> Google Analytics data for improving our service</li>
                            <li><strong>Advertising Data:</strong> Google AdSense data for relevant ad serving</li>
                        </ul>
                    </div>
                </div>

                <!-- 2. How We Use Your Information -->
                <div id="how-we-use" class="card bg-dark border-warning mb-4">
                    <div class="card-body">
                        <h3 class="text-warning mb-3">2. How We Use Your Information</h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-warning">Service Provision:</h5>
                                <ul class="text-light">
                                    <li>Provide movie and TV show recommendations</li>
                                    <li>Maintain your watchlists and viewing progress</li>
                                    <li>Enable interactive games and features</li>
                                    <li>Personalize your experience</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-warning">Communication:</h5>
                                <ul class="text-light">
                                    <li>Respond to your inquiries and support requests</li>
                                    <li>Send service-related notifications</li>
                                    <li>Provide newsletters (with your consent)</li>
                                    <li>Notify about important updates or changes</li>
                                </ul>
                            </div>
                        </div>

                        <h5 class="text-warning mt-4">Improvement & Analytics:</h5>
                        <ul class="text-light">
                            <li>Analyze usage patterns to improve our services</li>
                            <li>Conduct research and analytics on user behavior</li>
                            <li>Develop new features and enhance existing ones</li>
                            <li>Optimize performance and fix technical issues</li>
                        </ul>

                        <!-- <h5 class="text-warning mt-4">Legal & Security:</h5>
                        <ul class="text-light">
                            <li>Comply with legal obligations and law enforcement requests</li>
                            <li>Protect against fraud, abuse, and security threats</li>
                            <li>Enforce our terms of service</li>
                            <li>Protect the rights and safety of our users</li>
                        </ul> -->
                    </div>
                </div>

                <!-- 3. Information Sharing -->
                <div id="sharing" class="card bg-dark border-warning mb-4">
                    <div class="card-body">
                        <h3 class="text-warning mb-3">3. Information Sharing</h3>
                        
                        <div class="alert alert-success">
                            <strong><i class="fas fa-shield-alt"></i> We do not sell your personal information to third parties.</strong>
                        </div>

                        <h5 class="text-warning">We may share your information in these limited circumstances:</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-warning">Service Providers:</h6>
                                <ul class="text-light">
                                    <li>Hosting and infrastructure providers</li>
                                    <li>Email service providers</li>
                                    <li>Analytics providers (Google Analytics)</li>
                                    <li>Customer support tools</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-warning">Legal Requirements:</h6>
                                <ul class="text-light">
                                    <li>Legal compliance and court orders</li>
                                    <li>Law enforcement requests</li>
                                    <li>Protection of rights and safety</li>
                                    <li>Business transfers (mergers, acquisitions)</li>
                                </ul>
                            </div>
                        </div>

                        <h5 class="text-warning mt-4">Data Processing Agreements:</h5>
                        <p class="text-light">
                            All third-party service providers are bound by strict data processing agreements and are only 
                            authorized to use your information as necessary to provide services to us. They cannot use your 
                            information for their own purposes.
                        </p>
                    </div>
                </div>

                <!-- 4. Google AdSense & Advertising -->
                <!-- <div id="adsense" class="card bg-dark border-warning mb-4">
                    <div class="card-body">
                        <h3 class="text-warning mb-3">4. Google AdSense & Advertising</h3>
                        
                        <div class="alert alert-info">
                            <strong><i class="fas fa-info-circle"></i> Important:</strong> HITFLIX uses Google AdSense to display advertisements. 
                            This section explains how advertising affects your privacy.
                        </div>

                        <h5 class="text-warning">How Google AdSense Works:</h5>
                        <ul class="text-light">
                            <li><strong>Cookie Usage:</strong> Google AdSense uses cookies to serve ads based on your interests and previous visits</li>
                            <li><strong>Data Collection:</strong> Google may collect information about your visits to our site and other sites</li>
                            <li><strong>Personalized Ads:</strong> Ads may be personalized based on your browsing history and interests</li>
                            <li><strong>Third-Party Cookies:</strong> Advertising partners may also place cookies on your device</li>
                        </ul>

                        <h5 class="text-warning mt-4">Your Advertising Choices:</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-warning">Opt-Out Options:</h6>
                                <ul class="text-light">
                                    <li>Visit <a href="https://www.google.com/settings/ads" target="_blank" class="text-warning">Google Ads Settings</a></li>
                                    <li>Use <a href="https://optout.aboutads.info/" target="_blank" class="text-warning">NAI Opt-Out Tool</a></li>
                                    <li>Disable cookies in your browser settings</li>
                                    <li>Use browser extensions to block ads</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-warning">What This Means:</h6>
                                <ul class="text-light">
                                    <li>Opting out means less relevant ads</li>
                                    <li>You'll still see ads, but they won't be personalized</li>
                                    <li>Your choice applies across devices when signed in</li>
                                    <li>You can change your preferences anytime</li>
                                </ul>
                            </div>
                        </div>

                        <h5 class="text-warning mt-4">Data Retention for Advertising:</h5>
                        <p class="text-light">
                            Google retains advertising data according to their own privacy policy. We recommend reviewing 
                            <a href="https://policies.google.com/privacy" target="_blank" class="text-warning">Google's Privacy Policy</a> 
                            for detailed information about their data practices.
                        </p>
                    </div>
                </div> -->

                <!-- 5. Data Security -->
                <div id="data-security" class="card bg-dark border-warning mb-4">
                    <div class="card-body">
                        <h3 class="text-warning mb-3">4. Data Security</h3>
                        
                        <h5 class="text-warning">Security Measures:</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="text-light">
                                    <li><strong>Encryption:</strong> All data transmitted is encrypted using SSL/TLS</li>
                                    <li><strong>Password Security:</strong> Passwords are hashed using industry-standard algorithms</li>
                                    <li><strong>Access Controls:</strong> Limited access to personal data on a need-to-know basis</li>
                                    <li><strong>Regular Updates:</strong> Security patches and updates are applied regularly</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="text-light">
                                    <li><strong>Monitoring:</strong> Continuous monitoring for security threats</li>
                                    <li><strong>Backups:</strong> Regular encrypted backups of your data</li>
                                    <li><strong>Incident Response:</strong> Procedures in place for security incidents</li>
                                    <li><strong>Staff Training:</strong> Regular security training for our team</li>
                                </ul>
                            </div>
                        </div>

                        <div class="alert alert-warning mt-3">
                            <strong><i class="fas fa-exclamation-triangle"></i> Important:</strong> While we implement strong security measures, 
                            no internet transmission is 100% secure. Please use strong passwords and keep your account information confidential.
                        </div>

                        <h5 class="text-warning mt-4">Data Retention:</h5>
                        <ul class="text-light">
                            <li><strong>Account Data:</strong> Retained while your account is active and for 30 days after deletion</li>
                            <li><strong>Usage Data:</strong> Retained for up to 2 years for analytics purposes</li>
                            <li><strong>Communication Records:</strong> Retained for 3 years for customer service purposes</li>
                            <li><strong>Legal Requirements:</strong> Some data may be retained longer if required by law</li>
                        </ul>
                    </div>
                </div>

                <!-- 6. Your Rights -->
                <!-- <div id="your-rights" class="card bg-dark border-warning mb-4">
                    <div class="card-body">
                        <h3 class="text-warning mb-3">6. Your Rights</h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-warning">Data Access Rights:</h5>
                                <ul class="text-light">
                                    <li><strong>Access:</strong> Request a copy of your personal data</li>
                                    <li><strong>Portability:</strong> Receive your data in a machine-readable format</li>
                                    <li><strong>Correction:</strong> Update or correct inaccurate information</li>
                                    <li><strong>Deletion:</strong> Request deletion of your personal data</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-warning">Control Rights:</h5>
                                <ul class="text-light">
                                    <li><strong>Restriction:</strong> Limit how we process your data</li>
                                    <li><strong>Objection:</strong> Object to certain types of processing</li>
                                    <li><strong>Withdrawal:</strong> Withdraw consent at any time</li>
                                    <li><strong>Complaint:</strong> File complaints with data protection authorities</li>
                                </ul>
                            </div>
                        </div>

                        <h5 class="text-warning mt-4">How to Exercise Your Rights:</h5>
                        <div class="card bg-secondary">
                            <div class="card-body">
                                <p class="text-light mb-2">
                                    To exercise any of these rights, please contact us at:
                                </p>
                                <ul class="text-light mb-0">
                                    <li><strong>Email:</strong> privacy@hitflix.com</li>
                                    <li><strong>Contact Form:</strong> <a href="contact.php" class="text-warning">Use our contact page</a></li>
                                    <li><strong>Response Time:</strong> We'll respond within 30 days</li>
                                </ul>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <strong><i class="fas fa-info-circle"></i> Verification:</strong> For security purposes, we may need to verify 
                            your identity before processing requests related to your personal data.
                        </div>
                    </div>
                </div> -->

                <!-- 7. Cookies & Tracking -->
                <div id="cookies" class="card bg-dark border-warning mb-4">
                    <div class="card-body">
                        <h3 class="text-warning mb-3">5. Cookies & Tracking Technologies</h3>
                        
                        <h5 class="text-warning">Types of Cookies We Use:</h5>
                        <div class="table-responsive">
                            <table class="table table-dark table-striped">
                                <thead>
                                    <tr>
                                        <th>Cookie Type</th>
                                        <th>Purpose</th>
                                        <th>Duration</th>
                                        <th>Required</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Essential</strong></td>
                                        <td>Account login, security, basic functionality</td>
                                        <td>Session / 30 days</td>
                                        <td><span class="badge bg-danger">Yes</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Functional</strong></td>
                                        <td>Remember preferences, settings, watchlists</td>
                                        <td>1 year</td>
                                        <td><span class="badge bg-warning text-dark">Optional</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Analytics</strong></td>
                                        <td>Google Analytics, usage statistics</td>
                                        <td>2 years</td>
                                        <td><span class="badge bg-warning text-dark">Optional</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Advertising</strong></td>
                                        <td>Google AdSense, personalized ads</td>
                                        <td>Varies</td>
                                        <td><span class="badge bg-warning text-dark">Optional</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h5 class="text-warning mt-4">Managing Cookies:</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-warning">Browser Settings:</h6>
                                <ul class="text-light">
                                    <li>Block all cookies (may break functionality)</li>
                                    <li>Block third-party cookies only</li>
                                    <li>Clear cookies regularly</li>
                                    <li>Set cookies to expire when browser closes</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-warning">Opt-Out Tools:</h6>
                                <ul class="text-light">
                                    <li><a href="https://tools.google.com/dlpage/gaoptout" target="_blank" class="text-warning">Google Analytics Opt-out</a></li>
                                    <li><a href="https://www.google.com/settings/ads" target="_blank" class="text-warning">Google Ads Settings</a></li>
                                    <li>Browser ad-blocking extensions</li>
                                    <li>Privacy-focused browsers</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 8. Contact Us -->
                <div id="contact-privacy" class="card bg-warning text-dark mb-4">
                    <div class="card-body">
                        <h3><i class="fas fa-envelope"></i> Privacy-Related Questions?</h3>
                        <p class="mb-3">
                            If you have any questions about this Privacy Policy , 
                            we're here to help.
                        </p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Contact Information:</h5>
                                <ul class="mb-0">
                                    <!-- <li><strong>Privacy Email:</strong> privacy@hitflix.com</li> -->
                                    <li><strong>General Contact:</strong> hitflixprime@gmail.com</li>
                                    <li><strong>Contact Form:</strong> <a href="contact.php" class="text-dark">contact.php</a></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5>Response Times:</h5>
                                <ul class="mb-0">
                                    <!-- <li><strong>Privacy Requests:</strong> Within 30 days</li> -->
                                    <li><strong>General Questions:</strong> Within 24-48 hours</li>
                                    <li><strong>Urgent Issues:</strong> Same business day</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Updates Notice -->
                <div class="alert alert-info">
                    <h5><i class="fas fa-calendar-alt"></i> Policy Updates</h5>
                    <p class="mb-2">
                        We may update this Privacy Policy from time to time. When we make changes, we will:
                    </p>
                    <ul class="mb-2">
                        <li>Update the "Last Updated" date at the top of this page</li>
                        <li>Notify you via email if changes are significant</li>
                        <li>Display a notice on our website</li>
                        <li>Give you 30 days notice before major changes take effect</li>
                    </ul>
                    <p class="mb-0">
                        <strong>Your continued use of HITFLIX after changes indicates acceptance of the updated policy.</strong>
                    </p>
                </div>

                <!-- Back to Top -->
                <div class="text-center mt-5">
                    <a href="#top" class="btn btn-outline-warning">
                        <i class="fas fa-arrow-up"></i> Back to Top
                    </a>
                    <a href="index.php" class="btn btn-warning">
                        <i class="fas fa-home"></i> Return to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>
