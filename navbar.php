<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
</head>

<body>

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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>