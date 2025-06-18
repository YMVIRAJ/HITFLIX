<?php
// HITFLIX Configuration File

// Start output buffering to prevent header issues
if (!ob_get_level()) {
    ob_start();
}

// TMDB API Configuration
define('TMDB_API_KEY', getenv('TMDB_API_KEY') ?: '23602ebc8fa28c1425b72a06949f4e34');
define('TMDB_BASE_URL', 'https://api.themoviedb.org/3');
define('TMDB_IMAGE_BASE_URL', 'https://image.tmdb.org/t/p/w500');

// Application Configuration
define('APP_NAME', 'HITFLIX');
define('APP_VERSION', '1.0.0');
define('APP_DESCRIPTION', 'Your Ultimate Movie Entertainment Platform');

// Session Configuration (only if session not started)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
}

// Error Reporting (disable in production)
error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 0);

// Utility Functions
function sanitize_input($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function validate_year($year) {
    return is_numeric($year) && $year >= 1900 && $year <= date('Y') + 5;
}

function format_runtime($minutes) {
    if ($minutes < 60) {
        return $minutes . ' min';
    }
    $hours = floor($minutes / 60);
    $mins = $minutes % 60;
    return $hours . 'h ' . $mins . 'm';
}

function get_rating_class($rating) {
    if ($rating >= 8) return 'rating-high';
    if ($rating >= 6) return 'rating-medium';
    return 'rating-low';
}

// Genre mapping for better user experience
//     $GENRE_MAP = [
//     // Movie genres
//     28 => 'Action',
//     12 => 'Adventure',
//     16 => 'Animation',
//     35 => 'Comedy',
//     80 => 'Crime',
//     99 => 'Documentary',
//     18 => 'Drama',
//     10751 => 'Family',
//     14 => 'Fantasy',
//     36 => 'History',
//     27 => 'Horror',
//     10402 => 'Music',
//     9648 => 'Mystery',
//     10749 => 'Romance',
//     878 => 'Science Fiction',
//     10770 => 'TV Movie',
//     53 => 'Thriller',
//     10752 => 'War',
//     37 => 'Western',

//     // TV-specific genres
//     10759 => 'Action & Adventure',
//     10762 => 'Kids',
//     10763 => 'News',
//     10764 => 'Reality',
//     10765 => 'Sci-Fi & Fantasy',
//     10766 => 'Soap',
//     10767 => 'Talk',
//     10768 => 'War & Politics'
// ];

// In config.php - Combined Movie & TV Genre IDs
$GENRES = [
    'Action' => [
        'movie_id' => 28,
        'tv_id' => 10759
    ],
    'Adventure' => [
        'movie_id' => 12,
        'tv_id' => 10759  // Same as Action in TV
    ],
    'Animation' => [
        'movie_id' => 16,
        'tv_id' => 16
    ],
    'Comedy' => [
        'movie_id' => 35,
        'tv_id' => 35
    ],
    'Crime' => [
        'movie_id' => 80,
        'tv_id' => 80
    ],
    'Documentary' => [
        'movie_id' => 99,
        'tv_id' => 99
    ],
    'Drama' => [
        'movie_id' => 18,
        'tv_id' => 18
    ],
    'Family' => [
        'movie_id' => 10751,
        'tv_id' => 10751
    ],
    'Fantasy' => [
        'movie_id' => 14,
        'tv_id' => 10765  // Part of Sci-Fi & Fantasy in TV
    ],
    'History' => [
        'movie_id' => 36,
        'tv_id' => 36
    ],
    'Horror' => [
        'movie_id' => 27,
        'tv_id' => 27
    ],
    'Music' => [
        'movie_id' => 10402,
        'tv_id' => 10402
    ],
    'Mystery' => [
        'movie_id' => 9648,
        'tv_id' => 9648
    ],
    'Romance' => [
        'movie_id' => 10749,
        'tv_id' => 10749
    ],
    'Science Fiction' => [
        'movie_id' => 878,
        'tv_id' => 10765  // Part of Sci-Fi & Fantasy in TV
    ],
    'Thriller' => [
        'movie_id' => 53,
        'tv_id' => 53
    ],
    'War' => [
        'movie_id' => 10752,
        'tv_id' => 10768  // War & Politics in TV
    ],
    'Western' => [
        'movie_id' => 37,
        'tv_id' => 37
    ],
    // TV-Only Genres (optional)
    'Reality' => [
        'movie_id' => null,  // Not for movies
        'tv_id' => 10764
    ],
    'Talk' => [
        'movie_id' => null,  // Not for movies
        'tv_id' => 10767
    ]
];

// Content type mapping
$CONTENT_TYPE_MAP = [
    'movie' => 'Movie',
    'tv' => 'TV Series'
];

// Language mapping
$LANGUAGE_MAP = [
    'en' => 'English',
    'es' => 'Spanish',
    'fr' => 'French',
    'de' => 'German',
    'it' => 'Italian',
    'ja' => 'Japanese',
    'ko' => 'Korean',
    'zh' => 'Chinese',
    'hi' => 'Hindi',
    'ar' => 'Arabic',
    'ru' => 'Russian',
    'pt' => 'Portuguese'
];

// TMDB API Helper Functions
function make_tmdb_request($endpoint, $params = []) {
    $params['api_key'] = TMDB_API_KEY;
    $url = TMDB_BASE_URL . '/' . ltrim($endpoint, '/') . '?' . http_build_query($params);
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'user_agent' => 'HITFLIX/1.0'
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        return ['error' => 'Failed to fetch data from TMDB API'];
    }
    
    $data = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'Invalid JSON response from TMDB API'];
    }
    
    return $data;
}

function get_image_url($path, $size = 'w500') {
    if (empty($path)) return '';
    return "https://image.tmdb.org/t/p/$size$path";
}

// Set timezone
date_default_timezone_set('UTC');

// Security Headers (only if not already sent)
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY'); 
    header('X-XSS-Protection: 1; mode=block');
}
?>
