<?php
// Start session for maintaining state
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

// Set default active section
$active_section = $_GET['section'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HITFLIX - Movie Fun Hub</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

  <link rel="icon" href="img/Logo.png" />
</head>

<body>

  <?php
  include "navbar.php";
  include_once "connection.php";
  ?>

  <main class="container py-4">

    <!-- Dashboard Section - Show All Options -->
    <div id="dashboard" class="section <?= $active_section === 'dashboard' ? 'active-section' : '' ?>">
      <div class="welcome-header">
        <h1 class="welcome-title">Welcome to HITFLIX</h1>
        <p class="dashboard-description fs-5">Your Ultimate Movie Entertainment Hub</p>
        <p class="text-light">Hello, <?= htmlspecialchars($currentUser['name']) ?>! What would you like to explore today?</p>
      </div>

      <div class="sections-grid">

        <a href="?section=random" class="dashboard-card">
          <div class="card-body">
            <i class="fas fa-dice dashboard-icon"></i>
            <h3 class="dashboard-title">Random Movie Generator</h3>
            <p class="dashboard-description">Can't decide what to watch? Let our generator pick the perfect movie for you from thousands of options!</p>
          </div>
        </a>

        <a href="?section=tonight" class="dashboard-card">
          <div class="card-body">
            <i class="fas fa-bullseye dashboard-icon"></i>
            <h3 class="dashboard-title">What to Watch Tonight</h3>
            <p class="dashboard-description">Get personalized movie recommendations based on your mood, preferred genre, and viewing time.</p>
          </div>
        </a>

        <a href="?section=guess" class="dashboard-card">
          <div class="card-body">
            <i class="fas fa-question-circle dashboard-icon"></i>
            <h3 class="dashboard-title">Guess the Movie</h3>
            <p class="dashboard-description">Test your movie knowledge! Can you identify the movie from a blurred poster or cryptic clues?</p>
          </div>
        </a>

        <a href="?section=quote" class="dashboard-card">
          <div class="card-body">
            <i class="fas fa-quote-left dashboard-icon"></i>
            <h3 class="dashboard-title">Movie Quote of the Day</h3>
            <p class="dashboard-description">Discover memorable quotes from classic and modern films. Get inspired by cinema's greatest lines!</p>
          </div>
        </a>

        <a href="?section=actors" class="dashboard-card">
          <div class="card-body">
            <i class="fas fa-star dashboard-icon"></i>
            <h3 class="dashboard-title">Top Actors</h3>
            <p class="dashboard-description">Explore the most popular and talented actors in Hollywood. Discover their best movies and latest projects.</p>
          </div>
        </a>

        <a href="?section=tracker" class="dashboard-card">
          <div class="card-body">
            <i class="fas fa-check-square dashboard-icon"></i>
            <h3 class="dashboard-title">Series Completion Tracker</h3>
            <p class="dashboard-description">Never lose track of your TV series progress. Monitor which episodes you've watched across all your shows.</p>
          </div>
        </a>

        <a href="?section=planner" class="dashboard-card">
          <div class="card-body">
            <i class="fas fa-calendar dashboard-icon"></i>
            <h3 class="dashboard-title">Movie Night Planner</h3>
            <p class="dashboard-description">Plan the perfect movie night with friends. Create polls, vote on movies, and organize group viewing sessions.</p>
          </div>
        </a>

        <a href="?section=search" class="dashboard-card">
          <div class="card-body">
            <i class="fas fa-search dashboard-icon"></i>
            <h3 class="dashboard-title">Search Movies</h3>
            <p class="dashboard-description">Find any movie by title, genre, actor, director, or year. Comprehensive search across our vast movie database.</p>
          </div>
        </a>

      </div>
    </div>


    <!-- Random Movie Section -->
    <div id="random" class="section <?= $active_section === 'random' ? 'active-section' : '' ?>">
      <h2><i class="fas fa-dice text-warning"></i> Random Movie Generator</h2>
      <p class="dashboard-description">Discover your next favorite movie with our random generator!</p>
      <button class="btn btn-warning btn-lg mt-3" onclick="getRandomMovie()">
        <i class="fas fa-sync-alt"></i> Get Random Movie
      </button>
      <div id="randomMovie" class="mt-4"></div>
    </div>

    <!-- Tonight's Pick Section -->
    <div id="tonight" class="section <?= $active_section === 'tonight' ? 'active-section' : '' ?>">
      <h2><i class="fas fa-bullseye text-warning"></i> What to Watch Tonight</h2>
      <p class="dashboard-description">Find the perfect movie based on your mood and preferences</p>

      <form id="tonightForm" class="row g-3 mt-3">
        <div class="col-md-3">
          <label class="form-label">Movie or Show?</label>
          <select class="form-select" id="movieortv" onchange="handleMovieTVChange()">
            <option value="">Surprise Me</option>
            <option value="movie">Movie</option>
            <option value="tv">TV Show</option>
          </select>
        </div>

        <div class="col-md-2">
          <label for="genre" class="form-label">Genre</label>
          <select class="form-select" id="genre">
            <option value="">Any Genre</option>

            <!-- Movie ID, TV ID (comma separated) -->
            <option value="28,10759">Action</option>
            <option value="12,10759">Adventure</option>
            <!-- <option value="16,16">Animation</option> -->
            <option value="35,35">Comedy</option>
            <option value="80,80">Crime</option>
            <option value="99,99">Documentary</option>
            <option value="18,18">Drama</option>
            <option value="10751,10751">Family</option>
            <option value="14,10765">Fantasy</option>
            <option value="36,36">History</option>
            <option value="27,27">Horror</option>
            <option value="9648,9648">Mystery</option>
            <option value="53,53">Thriller</option>
            <option value="10402,10402">Music</option>
            <option value="10749,10749">Romance</option>
            <option value="878,10765">Sci-Fi</option>
            <option value="10770,10770">TV Movie</option>
            <option value="10752,10768">War</option>
            <option value="37,37">Western</option>

            <!-- Special categories you had -->
            <option value="anime">Anime</option> <!-- Using Animation IDs -->
            <option value="cartoon">Cartoon</option> <!-- Using Animation IDs -->

            <!-- TV-only genres (movie ID left blank) -->
            <option value=",10762">Kids</option>
            <!-- <option value=",10763">News</option>
    <option value=",10764">Reality</option>
    <option value=",10766">Soap</option>
    <option value=",10767">Talk</option> -->
          </select>
        </div>

        <div class="col-md-2">
          <label for="runtime" class="form-label">Runtime</label>
          <select class="form-select" id="runtime">
            <option value="">Any Length</option>
            <option value="short">Under 90 min</option>
            <option value="medium">90–120 min</option>
            <option value="long">Over 120 min</option>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">Year</label>
          <input type="number" id="year" class="form-control" placeholder="e.g. 2023" min="1900" max="2025">
        </div>

        <div class="col-md-3">
          <label class="form-label">Language</label>
          <select class="form-select" id="language">
            <option value="">Any Language</option>
            <option value="en">English</option>
            <option value="ja">Japanese</option>
            <option value="ko">Korean</option>
            <option value="hi">Hindi</option>
            <option value="fr">French</option>
            <option value="es">Spanish</option>
            <option value="de">German</option>
          </select>
        </div>

        <div class="col-12">
          <button type="button" class="btn btn-warning btn-lg" onclick="getTonightPick()">
            <i class="fas fa-search"></i> Find Perfect Movie
          </button>
        </div>
      </form>

      <div id="tonightResult" class="mt-4"></div>
    </div>

    <!-- Guess Game Section -->
    <div id="guess" class="section <?= $active_section === 'guess' ? 'active-section' : '' ?>">
      <h2><i class="fas fa-question-circle text-warning"></i> Guess the Movie</h2>
      <p class="dashboard-description">Test your movie knowledge! Can you guess the movie from a blurred poster?</p>

      <div class="text-center mt-4">
        <button class="btn btn-warning btn-lg mb-4" onclick="loadGuessMovie()">
          <i class="fas fa-play"></i> Start New Game
        </button>

        <div class="row">
          <div class="col-md-6">
            <h5>Mystery Movie</h5>
            <div id="guessContainer" class="mt-3"></div>
          </div>
          <div class="col-md-6">
            <h5>Your Guess</h5>
            <div id="guessResult" class="mt-3"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Movie Quotes Section -->
    <div id="quote" class="section <?= $active_section === 'quote' ? 'active-section' : '' ?>">
      <h2 class="text-light"><i class="fas fa-quote-left text-warning"></i> Movie Quote of the Day</h2>
      <p class="dashboard-description">Discover memorable quotes from classic and modern films</p>

      <div id="quoteBox" class="card bg-dark text-white p-4 my-4 border-warning">
        <div class="card-body">
          <h4 id="quoteMovie" class="text-warning mb-3">Loading...</h4>
          <blockquote class="blockquote mb-0">
            <p id="quoteText" class="fs-5">Loading inspirational quote...</p>
            <footer class="blockquote-footer mt-3">
              <cite id="quoteCharacter" class="dashboard-description">Loading...</cite>
            </footer>
          </blockquote>
        </div>
      </div>

      <button class="btn btn-warning btn-lg" onclick="loadQuote()">
        <i class="fas fa-sync-alt"></i> New Quote
      </button>
    </div>

    <!-- Top Actors Section -->
    <div id="actors" class="section <?= $active_section === 'actors' ? 'active-section' : '' ?>">
      <h2><i class="fas fa-star text-warning"></i> Top Actors</h2>
      <p class="dashboard-description">Discover the most popular actors in Hollywood right now</p>

      <div class="row mb-3">
        <div class="col-md-6">
          <button class="btn btn-outline-warning me-2" onclick="loadActors('popular')">Popular</button>
          <button class="btn btn-outline-warning" onclick="loadActors('trending')">Trending</button>
        </div>
      </div>

      <div id="actorList" class="mt-4"></div>
    </div>

    <!-- Series Tracker Section -->
    <div id="tracker" class="section <?= $active_section === 'tracker' ? 'active-section' : '' ?>">
      <h2><i class="fas fa-check-square text-warning"></i> Series Completion Tracker</h2>
      <p class="dashboard-description">Track your progress through TV series and seasons</p>

      <div class="row">
        <div class="col-md-6">
          <div class="card bg-dark border-warning">
            <div class="card-header">
              <h5><i class="fas fa-plus"></i> Add New Series</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <input type="text" id="seriesSearch" class="form-control" placeholder="Search for a TV series...">
              </div>
              <button class="btn btn-warning" onclick="searchSeries()">
                <i class="fas fa-search"></i> Search
              </button>
            </div>
          </div>

          <div id="seriesSearchResults" class="mt-3"></div>
        </div>

        <div class="col-md-6">
          <div class="card bg-dark border-warning">
            <div class="card-header">
              <h5><i class="fas fa-list"></i> My Series</h5>
            </div>
            <div class="card-body">
              <div id="mySeries">
                <p class="dashboard-description">No series added yet. Search and add some!</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Movie Night Planner Section -->
    <div id="planner" class="section <?= $active_section === 'planner' ? 'active-section' : '' ?>">
      <h2><i class="fas fa-calendar text-warning"></i> Movie Night Planner</h2>
      <p class="dashboard-description">Plan movie nights with friends and let everyone vote!</p>

      <div class="row">
        <div class="col-md-6">
          <div class="card bg-dark border-warning">
            <div class="card-header">
              <h5><i class="fas fa-plus"></i> Create Movie Poll</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label">Poll Title</label>
                <input type="text" id="pollTitle" class="form-control" placeholder="e.g., Friday Night Movies">
              </div>
              <div class="mb-3">
                <label class="form-label">Add Movie</label>
                <div class="input-group">
                  <input type="text" id="movieSearch" class="form-control" placeholder="Search for movies...">
                  <button class="btn btn-outline-warning" onclick="searchMoviesForPoll()">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
              <div id="movieSearchResults" class="mb-3"></div>
              <div id="selectedMovies" class="mb-3"></div>
              <button class="btn btn-warning" onclick="createPoll()">
                <i class="fas fa-vote-yea"></i> Create Poll
              </button>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card bg-dark border-warning">
            <div class="card-header">
              <h5><i class="fas fa-poll"></i> Active Polls</h5>
            </div>
            <div class="card-body">
              <div id="activePolls">
                <p class="dashboard-description">No active polls. Create one to get started!</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Search Movies Section -->
    <div id="search" class="section <?= $active_section === 'search' ? 'active-section' : '' ?>">
      <h2><i class="fas fa-search text-warning"></i> Search Movies</h2>
      <p class="dashboard-description">Find any movie you're looking for</p>

      <div class="row">
        <div class="col-md-8 mx-auto">
          <div class="input-group input-group-lg mb-4">
            <input type="text" id="searchInput" class="form-control" placeholder="Enter movie title..." onkeypress="if(event.key==='Enter') searchMovies()">
            <button class="btn btn-warning" onclick="searchMovies()">
              <i class="fas fa-search"></i> Search
            </button>
          </div>
        </div>
      </div>

      <div id="searchResults" class="mt-4"></div>
    </div>

  </main>

  <footer class="bg-dark text-center py-4 mt-5">
    <div class="container">
      <p class="mb-0 dashboard-description">&copy; 2025 HITFLIX - Your Ultimate Movie Companion</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/script.js"></script>
</body>

</html>