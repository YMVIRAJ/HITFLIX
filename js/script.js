// HITFLIX - Movie Entertainment Platform
// Configuration
const TMDB_API_KEY = '23602ebc8fa28c1425b72a06949f4e34';
const TMDB_BASE_URL = 'https://api.themoviedb.org/3';
const TMDB_IMAGE_BASE = 'https://image.tmdb.org/t/p/w500';

// Landing Page JavaScript Functions
function initLandingPage() {
    // Navbar scroll effect
    window.addEventListener('scroll', function () {
        const navbar = document.getElementById('navbar');
        if (navbar) {
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
    });

    // Smooth scrolling for navigation links
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
}

// Global variables
let currentGuessMovie = null;
let userSeries = JSON.parse(localStorage.getItem('userSeries')) || [];
let moviePolls = JSON.parse(localStorage.getItem('moviePolls')) || [];
let selectedMoviesForPoll = [];

// Utility Functions
function showLoading(elementId) {
    document.getElementById(elementId).innerHTML = '<div class="text-center"><div class="loading"></div><p class="mt-2">Loading...</p></div>';
}

function showError(elementId, message) {
    document.getElementById(elementId).innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> ${message}</div>`;
}

function formatRating(rating) {
    if (rating >= 8) return '<span class="rating rating-high">⭐ ' + rating.toFixed(1) + '/10</span>';
    if (rating >= 6) return '<span class="rating rating-medium">⭐ ' + rating.toFixed(1) + '/10</span>';
    return '<span class="rating rating-low">⭐ ' + rating.toFixed(1) + '/10</span>';
}

// Random Movie Generator
function getRandomMovie() {
    showLoading('randomMovie');

    const page = Math.floor(Math.random() * 20) + 1;
    const url = `${TMDB_BASE_URL}/movie/popular?api_key=${TMDB_API_KEY}&language=en-US&page=${page}`;

    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch movies');
            return response.json();
        })
        .then(data => {
            const movies = data.results.filter(m => m.poster_path && m.overview);
            if (movies.length === 0) throw new Error('No movies found');

            const random = movies[Math.floor(Math.random() * movies.length)];
            const movieHTML = `
                <div class="movie-card">
                    <div class="row g-0">
                        <div class="col-md-4" style="max-width: 350px;">
                            <img src="${TMDB_IMAGE_BASE}${random.poster_path}" class="movie-poster" alt="${random.title}" />
                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                <h3 class="card-title text-warning">${random.title}</h3>
                                <p class="text-muted mb-2">${random.release_date ? new Date(random.release_date).getFullYear() : 'Unknown Year'}</p>
                                <p class="mb-3">${formatRating(random.vote_average)}</p>
                                <h6 class="text-warning mb-2">Overview:</h6>
                                <p class="card-text">${random.overview}</p>
                                <div class="mt-3">
                                    <button class="btn btn-outline-warning btn-sm" onclick="getDetails(${random.id})">
                                        <i class="fas fa-info-circle"></i> More Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('randomMovie').innerHTML = movieHTML;
        })
        .catch(error => {
            console.error('Error fetching random movie:', error);
            showError('randomMovie', 'Failed to load random movie. Please try again.');
        });
}

// Tonight's Pick with Advanced Filtering
// Add this function to your script.js file to handle the movie/TV selection change
function handleMovieTVChange() {
    const movieOrTv = document.getElementById('movieortv').value;
    const runtimeSelect = document.getElementById('runtime');
    const runtimeLabel = document.querySelector('label[for="runtime"]');

    // Clear existing options
    runtimeSelect.innerHTML = '';

    if (movieOrTv === 'tv') {
        // TV Show options
        runtimeLabel.textContent = 'Seasons';
        runtimeSelect.innerHTML = `
            <option value="">Any Length</option>
            <option value="1">1 Season</option>
            <option value="2">2 Seasons</option>
            <option value="3">3 Seasons</option>
            <option value="4">4 Seasons</option>
            <option value="5">5 Seasons</option>
            <option value="5+">Above 5 Seasons</option>
        `;
    } else {
        // Movie options (default)
        runtimeLabel.textContent = 'Runtime';
        runtimeSelect.innerHTML = `
            <option value="">Any Length</option>
            <option value="short">Under 90 min</option>
            <option value="medium">90–120 min</option>
            <option value="long">Over 120 min</option>
        `;
    }
}

// Updated getTonightPick function to handle TV seasons
function getTonightPick() {
    showLoading('tonightResult');

    const type = document.getElementById('movieortv').value;
    const genreInput = document.getElementById('genre').value;
    const runtime = document.getElementById('runtime').value;
    const year = document.getElementById('year').value;
    const language = document.getElementById('language').value;

    // Build base parameters
    const baseParams = `api_key=${TMDB_API_KEY}&sort_by=popularity.desc&language=en-US&include_adult=false`;
    let movieUrl = `${TMDB_BASE_URL}/discover/movie?${baseParams}`;
    let tvUrl = `${TMDB_BASE_URL}/discover/tv?${baseParams}`;

    // Handle genre selection (new improved version)
    let movieGenreId = '';
    let tvGenreId = '';

    if (genreInput) {
        // Split the comma-separated genre IDs
        const genreIds = genreInput.split(',');
        movieGenreId = genreIds[0] || '';
        tvGenreId = genreIds[1] || '';
    }

    if (type === "tv") {
        // Add exclusions for political and late-night shows
        tvUrl += "&without_keywords=210024,207317,9713,210023,9715";
        tvUrl += "&without_genres=10767,10763,10764,10766,99";
        tvUrl += "&vote_average.gte=6";
    }

    // Handle special genres
    if (genreInput === "anime") {
        movieUrl += "&with_genres=16&with_original_language=ja";
        tvUrl += "&with_genres=16&with_original_language=ja";
    } else if (genreInput === "cartoon") {
        movieUrl += "&with_genres=16&with_original_language=en";
        tvUrl += "&with_genres=16&with_original_language=en";
    } else if (genreInput) {
        // Add the appropriate genre ID based on content type
        if (!type || type === "movie") {
            if (movieGenreId) movieUrl += `&with_genres=${movieGenreId}`;
        }
        if (!type || type === "tv") {
            if (tvGenreId) tvUrl += `&with_genres=${tvGenreId}`;
        }
    }

    // Add language filter (if not already set by special genres)
    if (language && genreInput !== "anime" && genreInput !== "cartoon") {
        movieUrl += `&with_original_language=${language}`;
        tvUrl += `&with_original_language=${language}`;
    }

    // Add year filter
    if (year) {
        movieUrl += `&primary_release_year=${year}`;
        tvUrl += `&first_air_date_year=${year}`;
    }

    // Add runtime filter for movies or season filter for TV shows
    if (runtime) {
        if (!type || type === "movie") {
            // Movie runtime filtering
            switch (runtime) {
                case 'short':
                    movieUrl += "&with_runtime.lte=90";
                    break;
                case 'medium':
                    movieUrl += "&with_runtime.gte=90&with_runtime.lte=120";
                    break;
                case 'long':
                    movieUrl += "&with_runtime.gte=120";
                    break;
            }
        } else if (type === "tv") {
            // TV show season filtering
            switch (runtime) {
                case '1':
                    tvUrl += "&with_status=5"; // Ended status
                    break;
                // Other cases handled in post-processing
            }
        }
    }

    // Determine which requests to make
    let requests = [];

    if (!type || type === "movie") {
        requests.push(
            fetch(movieUrl)
                .then(res => res.json())
                .then(data => ({ type: 'movie', data: data.results || [] }))
        );
    }

    if (!type || type === "tv") {
        requests.push(
            fetch(tvUrl)
                .then(res => res.json())
                .then(data => ({ type: 'tv', data: data.results || [] }))
        );
    }

    // Execute requests and process results (rest of your existing code remains the same)
    Promise.all(requests)
        .then(responses => {
            let allResults = [];

            responses.forEach(response => {
                if (response.data && response.data.length > 0) {
                    const typedResults = response.data.map(item => ({
                        ...item,
                        media_type: response.type
                    }));
                    allResults = allResults.concat(typedResults);
                }
            });

            // Filter out items without poster or overview
            allResults = allResults.filter(item => item.poster_path && item.overview);

            // Apply season filtering for TV shows
            if (runtime && type === "tv") {
                allResults = allResults.filter(item => {
                    if (item.media_type === 'tv') {
                        const seasons = item.number_of_seasons || 1;
                        switch (runtime) {
                            case '1': return seasons === 1;
                            case '2': return seasons === 2;
                            case '3': return seasons === 3;
                            case '4': return seasons === 4;
                            case '5': return seasons === 5;
                            case '5+': return seasons > 5;
                            default: return true;
                        }
                    }
                    return true;
                });
            }

            // Apply runtime filter to movie results
            if (runtime && (!type || type === "movie")) {
                allResults = allResults.filter(item => {
                    if (item.media_type === 'movie' && item.runtime) {
                        switch (runtime) {
                            case 'short': return item.runtime <= 90;
                            case 'medium': return item.runtime >= 90 && item.runtime <= 120;
                            case 'long': return item.runtime >= 120;
                            default: return true;
                        }
                    }
                    return true;
                });
            }

            if (allResults.length === 0) {
                document.getElementById("tonightResult").innerHTML =
                    "<div class='alert alert-warning'><i class='fas fa-exclamation-triangle'></i> No results found with these filters. Try different options!</div>";
                return;
            }

            // Shuffle and select up to 8 items
            const selected = allResults.sort(() => 0.5 - Math.random()).slice(0, 8);
            let html = '<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">';

            selected.forEach(item => {
                const title = item.title || item.name;
                const year = item.release_date || item.first_air_date;
                const mediaType = item.media_type === 'tv' ? 'TV Show' : 'Movie';
                const seasonInfo = item.media_type === 'tv' && item.number_of_seasons ?
                    ` • ${item.number_of_seasons} Season${item.number_of_seasons !== 1 ? 's' : ''}` : '';

                html += `
                    <div class="col">
                        <div class="card movie-card h-100">
                            <img src="${TMDB_IMAGE_BASE}${item.poster_path}" class="card-img-top" alt="${title}">
                            <div class="card-body">
                                <h6 class="card-title text-warning">${title}</h6>
                                <p class="text-muted small">${mediaType} • ${year ? new Date(year).getFullYear() : 'Unknown'}${seasonInfo}</p>
                                <p class="small">${formatRating(item.vote_average)}</p>
                                <p class="card-text small">${item.overview.substring(0, 100)}${item.overview.length > 100 ? '...' : ''}</p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <button class="btn btn-warning btn-sm w-100" onclick="getDetails(${item.id}, '${item.media_type}')">
                                    <i class="fas fa-info"></i> Details
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            document.getElementById("tonightResult").innerHTML = html;
        })
        .catch(error => {
            console.error('Error fetching tonight\'s pick:', error);
            showError('tonightResult', 'Failed to fetch content. Please try again.');
        });
}

// Movie Guessing Game
function loadGuessMovie() {
    showLoading('guessContainer');
    document.getElementById('guessResult').innerHTML = '';

    const page = Math.floor(Math.random() * 10) + 1;

    fetch(`${TMDB_BASE_URL}/movie/popular?api_key=${TMDB_API_KEY}&language=en-US&page=${page}`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch movies');
            return response.json();
        })
        .then(data => {
            const movies = data.results.filter(m => m.poster_path && m.title);
            if (movies.length === 0) throw new Error('No movies found');

            currentGuessMovie = movies[Math.floor(Math.random() * movies.length)];

            document.getElementById("guessContainer").innerHTML = `
                <div class="game-container">
                    <img src="${TMDB_IMAGE_BASE}${currentGuessMovie.poster_path}" 
                         alt="Guess the Movie" 
                         class="img-fluid mb-3 blurred-poster" 
                         style="max-width: 300px; border-radius: 10px;" />
                    <div class="input-group w-75 mx-auto">
                        <input type="text" id="userGuess" class="form-control" placeholder="Enter movie title..." 
                               onkeypress="if(event.key==='Enter') checkGuess()">
                        <button class="btn btn-success" onclick="checkGuess()">
                            <i class="fas fa-check"></i> Guess
                        </button>
                    </div>
                    <p class="text-muted mt-2">Hint: Released in ${new Date(currentGuessMovie.release_date).getFullYear()}</p>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error loading guess movie:', error);
            showError('guessContainer', 'Failed to load game. Please try again.');
        });
}

function checkGuess() {
    const guess = document.getElementById("userGuess").value.trim().toLowerCase();
    const actual = currentGuessMovie?.title.toLowerCase();

    if (!guess || !actual) return;

    const normalize = str => str.toLowerCase().replace(/[^a-z0-9]/gi, '').replace(/\bthe\b/g, '');
    const isCorrect = normalize(actual).includes(normalize(guess)) || normalize(guess).includes(normalize(actual));

    const resultHTML = `
        <div class="game-container">
            <div class="alert ${isCorrect ? 'alert-success' : 'alert-danger'} mb-3">
                <h5>${isCorrect ? '🎉 Correct!' : '❌ Not quite right!'}</h5>
                <p class="mb-0">The movie was: <strong>${currentGuessMovie.title}</strong></p>
            </div>
            <img src="${TMDB_IMAGE_BASE}${currentGuessMovie.poster_path}" 
                 class="img-fluid revealed-poster" 
                 style="max-width: 300px; border-radius: 10px;" />
            <div class="mt-3">
                <p class="text-muted">${currentGuessMovie.overview}</p>
                <p>${formatRating(currentGuessMovie.vote_average)}</p>
            </div>
            <button class="btn btn-warning mt-2" onclick="loadGuessMovie()">
                <i class="fas fa-play"></i> Play Again
            </button>
        </div>
    `;

    document.getElementById("guessResult").innerHTML = resultHTML;

    // Remove blur from the poster in the guess container
    const blurredPoster = document.querySelector('.blurred-poster');
    if (blurredPoster) {
        blurredPoster.classList.remove('blurred-poster');
        blurredPoster.classList.add('revealed-poster');
    }
}

// Movie Quotes
function loadQuote() {
    const quotes = [
        {
            text: "May the Force be with you.",
            movie: "Star Wars (1977)",
            character: "Obi-Wan Kenobi",
            actor: "Alec Guinness"
        },
        {
            text: "I'll be back.",
            movie: "The Terminator (1984)",
            character: "The Terminator",
            actor: "Arnold Schwarzenegger"
        },
        {
            text: "Here's looking at you, kid.",
            movie: "Casablanca (1942)",
            character: "Rick Blaine",
            actor: "Humphrey Bogart"
        },
        {
            text: "You can't handle the truth!",
            movie: "A Few Good Men (1992)",
            character: "Colonel Nathan Jessup",
            actor: "Jack Nicholson"
        },
        {
            text: "Life is like a box of chocolates, you never know what you're gonna get.",
            movie: "Forrest Gump (1994)",
            character: "Forrest Gump",
            actor: "Tom Hanks"
        },
        {
            text: "I see dead people.",
            movie: "The Sixth Sense (1999)",
            character: "Cole Sear",
            actor: "Haley Joel Osment"
        },
        {
            text: "Houston, we have a problem.",
            movie: "Apollo 13 (1995)",
            character: "Jim Lovell",
            actor: "Tom Hanks"
        },
        {
            text: "Keep your friends close, but your enemies closer.",
            movie: "The Godfather Part II (1974)",
            character: "Michael Corleone",
            actor: "Al Pacino"
        },
        {
            text: "I feel the need... the need for speed!",
            movie: "Top Gun (1986)",
            character: "Pete 'Maverick' Mitchell",
            actor: "Tom Cruise"
        },
        {
            text: "Nobody puts Baby in a corner.",
            movie: "Dirty Dancing (1987)",
            character: "Johnny Castle",
            actor: "Patrick Swayze"
        },
        {
            text: "Show me the money!",
            movie: "Jerry Maguire (1996)",
            character: "Rod Tidwell",
            actor: "Cuba Gooding Jr."
        },
        {
            text: "I'll have what she's having.",
            movie: "When Harry Met Sally (1989)",
            character: "Customer",
            actor: "Estelle Reiner"
        },
        {
            text: "There's no place like home.",
            movie: "The Wizard of Oz (1939)",
            character: "Dorothy",
            actor: "Judy Garland"
        },
        {
            text: "You talking to me?",
            movie: "Taxi Driver (1976)",
            character: "Travis Bickle",
            actor: "Robert De Niro"
        },
        {
            text: "After all, tomorrow is another day!",
            movie: "Gone with the Wind (1939)",
            character: "Scarlett O'Hara",
            actor: "Vivien Leigh"
        },
        {
            text: "Frankly, my dear, I don't give a damn.",
            movie: "Gone with the Wind (1939)",
            character: "Rhett Butler",
            actor: "Clark Gable"
        },
        {
            text: "I'm gonna make him an offer he can't refuse.",
            movie: "The Godfather (1972)",
            character: "Don Vito Corleone",
            actor: "Marlon Brando"
        },
        {
            text: "Go ahead, make my day.",
            movie: "Sudden Impact (1983)",
            character: "Harry Callahan",
            actor: "Clint Eastwood"
        },
        {
            text: "I'm king of the world!",
            movie: "Titanic (1997)",
            character: "Jack Dawson",
            actor: "Leonardo DiCaprio"
        },
        {
            text: "Elementary, my dear Watson.",
            movie: "The Adventures of Sherlock Holmes (1939)",
            character: "Sherlock Holmes",
            actor: "Basil Rathbone"
        }
    ];

    const randomQuote = quotes[Math.floor(Math.random() * quotes.length)];

    document.getElementById('quoteMovie').innerHTML = randomQuote.movie;
    document.getElementById('quoteText').innerHTML = `"${randomQuote.text}"`;
    document.getElementById('quoteCharacter').innerHTML = `${randomQuote.character} (${randomQuote.actor})`;

    // Add fade effect
    const quoteBox = document.getElementById('quoteBox');
    quoteBox.style.opacity = '0';
    setTimeout(() => {
        quoteBox.style.transition = 'opacity 0.5s ease';
        quoteBox.style.opacity = '1';
    }, 100);
}

// Top Actors
function loadActors(type = 'popular') {
    showLoading('actorList');

    const url = type === 'trending'
        ? `${TMDB_BASE_URL}/trending/person/week?api_key=${TMDB_API_KEY}`
        : `${TMDB_BASE_URL}/person/popular?api_key=${TMDB_API_KEY}&language=en-US&page=1`;

    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch actors');
            return response.json();
        })
        .then(data => {
            const actors = data.results.filter(a => a.profile_path).slice(0, 12);

            let html = '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">';
            actors.forEach(actor => {
                html += `
                    <div class="col">
                        <div class="card actor-card h-100">
                            <img src="${TMDB_IMAGE_BASE}${actor.profile_path}" class="actor-photo" alt="${actor.name}">
                            <div class="card-body">
                                <h6 class="card-title text-warning">${actor.name}</h6>
                                <p class="card-text small text-muted">${actor.known_for_department || 'Acting'}</p>
                                ${actor.known_for ? `<p class="small">Known for: ${actor.known_for.slice(0, 2).map(m => m.title || m.name).join(', ')}</p>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';

            document.getElementById('actorList').innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading actors:', error);
            showError('actorList', 'Failed to load actors. Please try again.');
        });
}

// Series Tracker
function searchSeries() {
    const query = document.getElementById('seriesSearch').value.trim();
    if (!query) return;

    showLoading('seriesSearchResults');

    fetch(`${TMDB_BASE_URL}/search/tv?api_key=${TMDB_API_KEY}&language=en-US&query=${encodeURIComponent(query)}`)

        .then(response => {
            if (!response.ok) throw new Error('Failed to search series');
            return response.json();
        })
        .then(data => {
            const series = data.results.filter(s => s.poster_path).slice(0, 5);

            if (series.length === 0) {
                document.getElementById('seriesSearchResults').innerHTML = '<div class="alert alert-info">No series found.</div>';
                return;
            }

            let html = '<h6 class="text-warning mb-3">Search Results:</h6>';
            series.forEach(show => {
                html += `
                    <div class="card bg-dark mb-2">
                        <div class="row g-0">
                            <div class="col-3">
                                <img src="${TMDB_IMAGE_BASE}${show.poster_path}" class="img-fluid rounded-start" style="height: 120px; object-fit: cover;">
                            </div>
                            <div class="col-9">
                                <div class="card-body py-2">
                                    <h6 class="card-title text-warning">${show.name}</h6>
                                    <p class="card-text small">${show.first_air_date ? new Date(show.first_air_date).getFullYear() : 'Unknown'}</p>
                                    <button class="btn btn-warning btn-sm" onclick="addSeries(${show.id}, '${show.name}', '${show.poster_path}')">
    <i class="fas fa-plus"></i> Add
</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            document.getElementById('seriesSearchResults').innerHTML = html;
        })
        .catch(error => {
            console.error('Error searching series:', error);
            showError('seriesSearchResults', 'Failed to search series.');
        });
}

async function addSeries(id, name, posterPath) {
    // Check if series already exists
    if (userSeries.find(s => s.id === id)) {
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                background: 'rgba(30, 30, 47, 0.9)',
                customClass: {
                    popup: 'glass-popup',
                    confirmButton: 'btn btn-warning'
                },
                didOpen: () => {
                    const btn = Swal.getConfirmButton();
                    if (btn) {
                        btn.style.padding = '8px 32px';
                        btn.style.borderRadius = '10px';
                        btn.style.fontWeight = 'bold';
                    }
                }
            });
            Toast.fire({
                icon: "warning",
                title: "Series already added!"
            });
        } else {
            alert('Series already added!');
        }
        return;
    }

    try {
        // Fetch detailed series info to get the actual number of seasons
        const response = await fetch(`${TMDB_BASE_URL}/tv/${id}?api_key=${TMDB_API_KEY}&language=en-US`);
        const seriesDetails = await response.json();
        
        const newSeries = {
            id: id,
            name: name,
            poster: posterPath,
            totalSeasons: seriesDetails.number_of_seasons || 1,
            watchedSeasons: 0,
            currentSeason: 1
        };

        userSeries.push(newSeries);
        localStorage.setItem('userSeries', JSON.stringify(userSeries));
        updateMySeriesDisplay();

        // Clear search
        document.getElementById('seriesSearch').value = '';
        document.getElementById('seriesSearchResults').innerHTML = '';

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Series Added!',
                text: `${name} has been added to your tracker with ${seriesDetails.number_of_seasons} seasons.`,
                timer: 1500,
                showConfirmButton: false
            });
        }
    } catch (error) {
        console.error('Error fetching series details:', error);
        // Fallback to 1 season if API call fails
        const newSeries = {
            id: id,
            name: name,
            poster: posterPath,
            totalSeasons: 1,
            watchedSeasons: 0,
            currentSeason: 1
        };

        userSeries.push(newSeries);
        localStorage.setItem('userSeries', JSON.stringify(userSeries));
        updateMySeriesDisplay();
    }
}
function updateMySeriesDisplay() {
    const container = document.getElementById('mySeries');

    if (userSeries.length === 0) {
        container.innerHTML = '<p class="text-muted">No series added yet. Search and add some!</p>';
        return;
    }

    let html = '';
    userSeries.forEach(series => {
        const progress = (series.watchedSeasons / series.totalSeasons) * 100;
        html += `
            <div class="card bg-dark mb-3 border-warning">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <img src="${TMDB_IMAGE_BASE}${series.poster}" class="img-fluid rounded" style="height: 80px; object-fit: cover;">
                        </div>
                        <div class="col-9">
                            <h6 class="text-warning">${series.name}</h6>
                            <p class="small mb-1">Season ${series.watchedSeasons}/${series.totalSeasons}</p>
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar" style="width: ${progress}%"></div>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-warning" onclick="updateSeriesProgress(${series.id}, 'prev')">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button class="btn btn-outline-warning" onclick="updateSeriesProgress(${series.id}, 'next')">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="removeSeries(${series.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

function updateSeriesProgress(seriesId, action) {
    const series = userSeries.find(s => s.id === seriesId);
    if (!series) return;

    if (action === 'next' && series.watchedSeasons < series.totalSeasons) {
        series.watchedSeasons++;
    } else if (action === 'prev' && series.watchedSeasons > 0) {
        series.watchedSeasons--;
    }

    localStorage.setItem('userSeries', JSON.stringify(userSeries));
    updateMySeriesDisplay();
}

function removeSeries(seriesId) {
    const series = userSeries.find(s => s.id === seriesId);
    if (!series) return;

    const confirmMessage = `Remove "${series.name}" from your tracker?`;

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Are you sure?',
            text: confirmMessage,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'Cancel',
            customClass: {
                    confirmButton: 'btn btn-warning'
                },
        }).then((result) => {
            if (result.isConfirmed) {
                userSeries = userSeries.filter(s => s.id !== seriesId);
                localStorage.setItem('userSeries', JSON.stringify(userSeries));
                updateMySeriesDisplay();

                Swal.fire('Removed!', 'Series has been removed from your tracker.', 'success');
                if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Removed!',
                text: `Series has been removed from your tracker.`,
                timer: 1500,
                showConfirmButton: false
            });
        }
            }
        });
    } else {
        if (confirm(confirmMessage)) {
            userSeries = userSeries.filter(s => s.id !== seriesId);
            localStorage.setItem('userSeries', JSON.stringify(userSeries));
            updateMySeriesDisplay();
        }
    }
}



// Movie Night Planner
function searchMoviesForPoll() {
    const query = document.getElementById('movieSearch').value.trim();
    if (!query) return;

    fetch(`${TMDB_BASE_URL}/search/movie?api_key=${TMDB_API_KEY}&language=en-US&query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            const movies = data.results.filter(m => m.poster_path).slice(0, 5);

            let html = '';
            movies.forEach(movie => {
                html += `
                    <div class="card bg-dark mb-2 search-result">
                        <div class="card-body py-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h6 class="mb-0">${movie.title} (${movie.release_date ? new Date(movie.release_date).getFullYear() : 'Unknown'})</h6>
                                </div>
                                <div class="col-4 text-end">
                                    <button class="btn btn-warning btn-sm" onclick="addMovieToPoll(${movie.id}, '${movie.title.replace(/'/g, "\\'")}', '${movie.poster_path}')">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            document.getElementById('movieSearchResults').innerHTML = html;
        })
        .catch(error => {
            console.error('Error searching movies:', error);
        });
}

function addMovieToPoll(id, title, poster) {
    if (selectedMoviesForPoll.find(m => m.id === id)) {
        // alert('Movie already added!');

        const Toast = Swal.mixin({
            background: 'rgba(30, 30, 47, 0.9)',
            customClass: {
                popup: 'glass-popup',
                confirmButton: 'btn btn-warning'
            },
            didOpen: () => {
                const btn = Swal.getConfirmButton();
                btn.style.padding = '8px 32px';
                btn.style.borderRadius = '10px';
                btn.style.fontWeight = 'bold';
            }
        });
        Toast.fire({
            icon: "warning",
            title: "Movie already added!"
        });
        return;
    }

    selectedMoviesForPoll.push({ id, title, poster });
    updateSelectedMoviesDisplay();

    // Clear search
    document.getElementById('movieSearch').value = '';
    document.getElementById('movieSearchResults').innerHTML = '';
}

function updateSelectedMoviesDisplay() {
    const container = document.getElementById('selectedMovies');

    if (selectedMoviesForPoll.length === 0) {
        container.innerHTML = '';
        return;
    }

    let html = '<h6 class="text-warning">Selected Movies:</h6>';
    selectedMoviesForPoll.forEach(movie => {
        html += `
            <div class="badge bg-warning text-dark me-2 mb-2">
                ${movie.title}
                <button class="btn-close btn-close-white ms-2" onclick="removeMovieFromPoll(${movie.id})" style="font-size: 0.7em;"></button>
            </div>
        `;
    });

    container.innerHTML = html;
}

function removeMovieFromPoll(movieId) {
    selectedMoviesForPoll = selectedMoviesForPoll.filter(m => m.id !== movieId);
    updateSelectedMoviesDisplay();
}

function createPoll() {
    const title = document.getElementById('pollTitle').value.trim();

    if (!title) {
        // alert('Please enter a poll title');

        const Toast = Swal.mixin({
            background: 'rgba(30, 30, 47, 0.9)',
            customClass: {
                popup: 'glass-popup',
                confirmButton: 'btn btn-warning'
            },
            didOpen: () => {
                const btn = Swal.getConfirmButton();
                btn.style.padding = '8px 32px';
                btn.style.borderRadius = '10px';
                btn.style.fontWeight = 'bold';
            }
        });
        Toast.fire({
            icon: "warning",
            title: "Please enter a poll title"
        });
        return;
    }

    if (selectedMoviesForPoll.length < 2) {
        // alert('Please add at least 2 movies to the poll');

        const Toast = Swal.mixin({
            background: 'rgba(30, 30, 47, 0.9)',
            customClass: {
                popup: 'glass-popup',
                confirmButton: 'btn btn-warning'
            },
            didOpen: () => {
                const btn = Swal.getConfirmButton();
                btn.style.padding = '8px 32px';
                btn.style.borderRadius = '10px';
                btn.style.fontWeight = 'bold';
            }
        });
        Toast.fire({
            icon: "warning",
            title: "Please add at least 2 movies to the poll"
        });
        return;
    }

    const poll = {
        id: Date.now(),
        title: title,
        movies: selectedMoviesForPoll.map(m => ({ ...m, votes: 0 })),
        totalVotes: 0,
        created: new Date().toLocaleDateString()
    };

    moviePolls.push(poll);
    localStorage.setItem('moviePolls', JSON.stringify(moviePolls));

    // Reset form
    document.getElementById('pollTitle').value = '';
    selectedMoviesForPoll = [];
    updateSelectedMoviesDisplay();
    updateActivePollsDisplay();

    // alert('Poll created successfully!');

    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: 'rgba(30, 30, 47, 0.9)',
        customClass: {
            popup: 'glass-popup',
        },
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: "success",
        title: "Poll created successfully!"
    });
}

function updateActivePollsDisplay() {
    const container = document.getElementById('activePolls');

    if (moviePolls.length === 0) {
        container.innerHTML = '<p class="text-muted">No active polls. Create one to get started!</p>';
        return;
    }

    let html = '';
    moviePolls.forEach(poll => {
        html += `
            <div class="card bg-dark border-warning mb-3">
                <div class="card-header">
                    <h6 class="mb-0">${poll.title}</h6>
                    <small class="text-muted">Created: ${poll.created} | Total votes: ${poll.totalVotes}</small>
                </div>
                <div class="card-body">
                    ${poll.movies.map(movie => {
            const percentage = poll.totalVotes > 0 ? (movie.votes / poll.totalVotes * 100).toFixed(1) : 0;
            return `
                            <div class="poll-option mb-2" onclick="voteForMovie(${poll.id}, ${movie.id})">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>${movie.title}</span>
                                    <span class="badge bg-warning text-dark">${movie.votes} votes</span>
                                </div>
                                <div class="progress mt-1" style="height: 5px;">
                                    <div class="vote-bar" style="width: ${percentage}%"></div>
                                </div>
                            </div>
                        `;
        }).join('')}
                    <button class="btn btn-outline-danger btn-sm mt-2" onclick="deletePoll(${poll.id})">
                        <i class="fas fa-trash"></i> Delete Poll
                    </button>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

function voteForMovie(pollId, movieId) {
    const poll = moviePolls.find(p => p.id === pollId);
    if (!poll) return;

    const movie = poll.movies.find(m => m.id === movieId);
    if (!movie) return;

    movie.votes++;
    poll.totalVotes++;

    localStorage.setItem('moviePolls', JSON.stringify(moviePolls));
    updateActivePollsDisplay();
}

function deletePoll(pollId) {
    if (confirm('Delete this poll?')) {
        moviePolls = moviePolls.filter(p => p.id !== pollId);
        localStorage.setItem('moviePolls', JSON.stringify(moviePolls));
        updateActivePollsDisplay();
    }
}

// Movie Search
function searchMovies() {
    const rawInput = document.getElementById('searchInput').value.trim();
    if (!rawInput) return;

    showLoading('searchResults');

    // Enhanced year extraction logic
    let query = rawInput;
    let year = '';
    const currentYear = new Date().getFullYear();
    
    // Try multiple patterns for year extraction
    const patterns = [
        /^(.+?)\s+(\d{4})$/, // "movie title 2022"
        /^(.+?)\s+\((\d{4})\)$/, // "movie title (2022)"
        /^(.+?)\s+-\s+(\d{4})$/, // "movie title - 2022"
    ];
    
    for (const pattern of patterns) {
        const match = rawInput.match(pattern);
        if (match && match[2] >= 1900 && match[2] <= (currentYear + 2)) {
            query = match[1].trim();
            year = match[2];
            break;
        }
    }

    const encodedQuery = encodeURIComponent(query);
    
    // Build search URLs with year parameters
    let movieSearchUrl = `${TMDB_BASE_URL}/search/movie?api_key=${TMDB_API_KEY}&language=en-US&query=${encodedQuery}&page=1`;
    let tvSearchUrl = `${TMDB_BASE_URL}/search/tv?api_key=${TMDB_API_KEY}&language=en-US&query=${encodedQuery}&page=1`;
    
    // Add year parameter for movies if specified
    if (year) {
        movieSearchUrl += `&year=${year}`;
        tvSearchUrl += `&year=${year}`;
        // Note: TV search doesn't have a year parameter, we'll filter results later
    }

    const movieSearch = fetch(movieSearchUrl);
    const tvSearch = fetch(tvSearchUrl);

    Promise.all([movieSearch, tvSearch])
        .then(responses => {
            if (!responses[0].ok || !responses[1].ok) {
                throw new Error('Failed to search');
            }
            return Promise.all([responses[0].json(), responses[1].json()]);
        })
        .then(([movieData, tvData]) => {
            // Process movie results
            let movies = movieData.results
                .filter(m => m.poster_path)
                .map(movie => ({...movie, media_type: 'movie'}));

            // Process TV results and filter by year if specified
            let tvShows = tvData.results
                .filter(tv => tv.poster_path)
                .map(tv => ({...tv, media_type: 'tv'}));
            
            if (year) {
                // Filter TV shows by year
                tvShows = tvShows.filter(tv => {
                    if (tv.first_air_date) {
                        const tvYear = new Date(tv.first_air_date).getFullYear();
                        return tvYear == year;
                    }
                    return false;
                });
            }

            // Combine results with smart sorting
            const allResults = [...movies, ...tvShows];
            
            if (year) {
                // When year is specified, prioritize exact year matches
                allResults.sort((a, b) => {
                    const aYear = a.media_type === 'movie' ? 
                        (a.release_date ? new Date(a.release_date).getFullYear() : 0) :
                        (a.first_air_date ? new Date(a.first_air_date).getFullYear() : 0);
                    const bYear = b.media_type === 'movie' ? 
                        (b.release_date ? new Date(b.release_date).getFullYear() : 0) :
                        (b.first_air_date ? new Date(b.first_air_date).getFullYear() : 0);
                    
                    // Exact year matches first
                    const aExactMatch = aYear == year ? 1 : 0;
                    const bExactMatch = bYear == year ? 1 : 0;
                    
                    if (aExactMatch !== bExactMatch) {
                        return bExactMatch - aExactMatch;
                    }
                    
                    // Then by popularity
                    return b.popularity - a.popularity;
                });
            } else {
                // Normal sorting by popularity
                allResults.sort((a, b) => b.popularity - a.popularity);
            }

            if (allResults.length === 0) {
                const searchTerm = year ? `"${query}" from ${year}` : `"${rawInput}"`;
                document.getElementById('searchResults').innerHTML = 
                    `<div class="alert alert-info">No movies or TV series found for ${searchTerm}. Try a different search term.</div>`;
                return;
            }

            displaySearchResults(allResults, query, year);
        })
        .catch(error => {
            console.error('Error searching:', error);
            showError('searchResults', 'Failed to search. Please try again.');
        });
}

function displaySearchResults(results, originalQuery, searchYear) {
    let html = '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">';
    
    results.forEach((item, index) => {
        const isMovie = item.media_type === 'movie';
        const title = isMovie ? item.title : item.name;
        const releaseDate = isMovie ? item.release_date : item.first_air_date;
        const year = releaseDate ? new Date(releaseDate).getFullYear() : 'Unknown';
        const mediaTypeLabel = isMovie ? 'Movie' : 'TV Series';
        
        // Highlight exact matches
        const isExactMatch = searchYear && year == searchYear;
        const cardClass = isExactMatch ? 'border-warning' : '';
        const exactMatchBadge = isExactMatch ? '<span class="badge bg-success position-absolute top-0 start-0 m-2">Exact Match</span>' : '';
        
        html += `
            <div class="col">
                <div class="card movie-card h-100 ${cardClass}" onclick="${isMovie ? `getMovieDetails(${item.id})` : `getTVDetails(${item.id})`}" style="cursor: pointer;">
                    <div class="position-relative">
                        <img src="${TMDB_IMAGE_BASE}${item.poster_path}" class="card-img-top" alt="${title}" loading="lazy">
                        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">${mediaTypeLabel}</span>
                        ${exactMatchBadge}
                    </div>
                    <div class="card-body">
                        <h6 class="card-title text-warning">${title}</h6>
                        <p class="text-muted small mb-1">${year}</p>
                        <div class="mb-2">${formatRating(item.vote_average)}</div>
                        <p class="card-text small text-light">${item.overview ? item.overview.substring(0, 100) + '...' : 'No description available'}</p>
                    </div>
                    <div class="card-footer bg-transparent">
                        <button class="btn btn-warning btn-sm w-100">
                            <i class="fas fa-info"></i> View Details
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';

    // Add search summary
    const searchSummary = searchYear ? 
        `<div class="alert alert-info mb-4"><i class="fas fa-search"></i> Showing results for "<strong>${originalQuery}</strong>" from <strong>${searchYear}</strong> (${results.length} results found)</div>` :
        `<div class="alert alert-info mb-4"><i class="fas fa-search"></i> Showing results for "<strong>${originalQuery}</strong>" (${results.length} results found)</div>`;

    document.getElementById('searchResults').innerHTML = searchSummary + html;
}


// Movie Details Modal (you can expand this)
function getDetails(id, mediaType = 'movie') {
    const endpoint = mediaType === 'tv' ? 'tv' : 'movie';

    // First, fetch movie/TV show details
    fetch(`${TMDB_BASE_URL}/${endpoint}/${id}?api_key=${TMDB_API_KEY}&language=en-US`)
        .then(response => response.json())
        .then(item => {
            const title = item.title || item.name;
            const runtime = item.runtime || (item.episode_run_time && item.episode_run_time[0]) || 'Unknown';
            const releaseDate = item.release_date || item.first_air_date;
            const overview = item.overview;

            // Now fetch the credits (cast)
            fetch(`${TMDB_BASE_URL}/${endpoint}/${id}/credits?api_key=${TMDB_API_KEY}&language=en-US`)
                .then(response => response.json())
                .then(credits => {
                    const top10Cast = credits.cast.slice(0, 10).map(castMember => `${castMember.name}&nbsp;&nbsp; as &nbsp;&nbsp;${castMember.character}`).join('<br>');

                    // alert(`${title}\n\nRating: ${item.vote_average}/10\nRuntime: ${runtime} minutes\nRelease: ${releaseDate}\n\n${overview}\n\nTop Cast:\n${top10Cast}`);

                    Swal.fire({
                        html: `
                            <div style="display: flex; align-items: flex-start;">
                            <img src="${TMDB_IMAGE_BASE}${item.poster_path}" 
                                alt="Poster" 
                                style="width: 120px; height: auto; margin-right: 20px; border-radius: 10px;" />
                            <div style="text-align: left;">
                                <h5 style="margin: 0; font-size: 22px;">${title}</h5><br>
                                <p style="margin: 4px 0;"><b>Rating:</b> ${item.vote_average}/10</p>
                                <p style="margin: 4px 0;"><b>Runtime:</b> ${runtime} minutes</p>
                                <p style="margin: 4px 0;"><b>Release:</b> ${releaseDate}</p>
                            </div>
                            </div>
                            <div>
                            <p style="margin: 10px 0 0; text-align: justify;"><b>Overview:</b><br> ${overview}</p>
                                <p style="margin: 10px 0 0; text-align: left;"><b>Cast:</b><br> ${top10Cast}</p>
                            </div>
                        `,
                        customClass: {
                            popup: 'glass-popup',
                        },
                        showConfirmButton: false,          // Hide default "OK" button
                        showCloseButton: true,            // Show (X) in the top-right
                        focusConfirm: false,
                        background: 'rgba(30, 30, 47, 0.9)',
                        width: "600px",
                    });

                })
                .catch(error => {
                    console.error('Error fetching cast:', error);
                    // alert(`${title}\n\nRating: ${item.vote_average}/10\nRuntime: ${runtime} minutes\nRelease: ${releaseDate}\n\n${overview}\n\n(Cast unavailable)`);

                    Swal.fire({
                        html: `
                            <div style="display: flex; align-items: flex-start;">
                            <img src="${TMDB_IMAGE_BASE}${item.poster_path}" 
                                alt="Poster" 
                                style="width: 120px; height: auto; margin-right: 20px; border-radius: 10px;" />
                            <div style="text-align: left;">
                                <h5 style="margin: 0; font-size: 22px;">${title}</h5><br>
                                <p style="margin: 4px 0;"><b>Rating:</b> ${item.vote_average}/10</p>
                                <p style="margin: 4px 0;"><b>Runtime:</b> ${runtime} minutes</p>
                                <p style="margin: 4px 0;"><b>Release:</b> ${releaseDate}</p>
                            </div>
                            </div>
                            <div>
                            <p style="margin: 10px 0 0; text-align: justify;"><b>Overview:</b><br> ${overview}</p>
                                <p style="margin: 10px 0 0; text-align: left;"><b>Cast:</b><br> [Cast unavailable]</p>
                            </div>
                        `,
                        customClass: {
                            popup: 'glass-popup',
                        },
                        showConfirmButton: false,          // Hide default "OK" button
                        showCloseButton: true,            // Show (X) in the top-right
                        focusConfirm: false,
                        background: 'rgba(30, 30, 47, 0.9)',
                        width: "600px",
                    });

                });
        })
        .catch(error => {
            console.error('Error getting details:', error);
            alert('Failed to load details');
        });
}


function getMovieDetails(movieId) {
    showLoading('searchResults');
    
    fetch(`${TMDB_BASE_URL}/movie/${movieId}?api_key=${TMDB_API_KEY}&language=en-US&append_to_response=credits,videos`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch movie details');
            return response.json();
        })
        .then(movie => {
            // Get main cast (first 5)
            const cast = movie.credits?.cast?.slice(0, 5) || [];
            
            // Get director
            const director = movie.credits?.crew?.find(person => person.job === 'Director');
            
            // Get trailer
            const trailer = movie.videos?.results?.find(v => v.type === 'Trailer' && v.site === 'YouTube');
            
            // Format genres
            const genres = movie.genres?.map(g => g.name).join(', ') || 'Unknown';
            
            // Format runtime
            const runtime = movie.runtime ? `${movie.runtime} min` : 'Unknown';
            
            // Format release date
            const releaseYear = movie.release_date ? new Date(movie.release_date).getFullYear() : 'Unknown';
            const releaseDate = movie.release_date ? new Date(movie.release_date).toLocaleDateString() : 'Unknown';
            
            // Format budget and revenue
            const budget = movie.budget ? `$${movie.budget.toLocaleString()}` : 'Unknown';
            const revenue = movie.revenue ? `$${movie.revenue.toLocaleString()}` : 'Unknown';
            
            let html = `
                <div class="movie-details">
                    <button class="btn btn-outline-warning mb-3" onclick="searchMovies()">
                        <i class="fas fa-arrow-left"></i> Back to Search
                    </button>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <img src="${TMDB_IMAGE_BASE}${movie.poster_path}" class="img-fluid rounded shadow-lg" alt="${movie.title}">
                        </div>
                        <div class="col-md-8">
                            <h2 class="text-warning mb-3">${movie.title}</h2>
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <p><strong class="text-warning">Release Date:</strong> ${releaseDate}</p>
                                    <p><strong class="text-warning">Runtime:</strong> ${runtime}</p>
                                    <p><strong class="text-warning">Rating:</strong> ${formatRating(movie.vote_average)}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong class="text-warning">Budget:</strong> ${budget}</p>
                                    <p><strong class="text-warning">Revenue:</strong> ${revenue}</p>
                                    <p><strong class="text-warning">Status:</strong> ${movie.status || 'Unknown'}</p>
                                </div>
                            </div>
                            
                            <p><strong class="text-warning">Genres:</strong> ${genres}</p>
                            
                            ${director ? `
                                <p><strong class="text-warning">Director:</strong> ${director.name}</p>
                            ` : ''}
                            
                            ${movie.overview ? `
                                <div class="mb-4">
                                    <h5 class="text-warning">Overview</h5>
                                    <p class="text-light">${movie.overview}</p>
                                </div>
                            ` : ''}
                            
                            ${cast.length > 0 ? `
                                <div class="mb-4">
                                    <h5 class="text-warning">Main Cast</h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        ${cast.map(actor => `
                                            <span class="badge bg-secondary">${actor.name} as ${actor.character}</span>
                                        `).join('')}
                                    </div>
                                </div>
                            ` : ''}
                            
                            ${trailer ? `
                                <div class="mb-4">
                                    <h5 class="text-warning">Trailer</h5>
                                    <div class="ratio ratio-16x9">
                                        <iframe src="https://www.youtube.com/embed/${trailer.key}" 
                                                allowfullscreen 
                                                class="rounded"></iframe>
                                    </div>
                                </div>
                            ` : ''}
                            
                            ${movie.production_companies && movie.production_companies.length > 0 ? `
                                <div class="mb-3">
                                    <strong class="text-warning">Production Companies:</strong> 
                                    ${movie.production_companies.map(company => company.name).join(', ')}
                                </div>
                            ` : ''}
                            
                            ${movie.production_countries && movie.production_countries.length > 0 ? `
                                <div class="mb-3">
                                    <strong class="text-warning">Countries:</strong> 
                                    ${movie.production_countries.map(country => country.name).join(', ')}
                                </div>
                            ` : ''}
                            
                            ${movie.spoken_languages && movie.spoken_languages.length > 0 ? `
                                <div class="mb-3">
                                    <strong class="text-warning">Languages:</strong> 
                                    ${movie.spoken_languages.map(lang => lang.english_name).join(', ')}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('searchResults').innerHTML = html;
        })
        .catch(error => {
            console.error('Error fetching movie details:', error);
            showError('searchResults', 'Failed to load movie details. Please try again.');
        });
}


function getTVDetails(tvId) {
    showLoading('searchResults');
    
    fetch(`${TMDB_BASE_URL}/tv/${tvId}?api_key=${TMDB_API_KEY}&language=en-US&append_to_response=credits,videos`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch TV series details');
            return response.json();
        })
        .then(tv => {
            // Get main cast (first 5)
            const cast = tv.credits?.cast?.slice(0, 5) || [];
            
            // Get trailer
            const trailer = tv.videos?.results?.find(v => v.type === 'Trailer' && v.site === 'YouTube');
            
            // Format genres
            const genres = tv.genres?.map(g => g.name).join(', ') || 'Unknown';
            
            // Format runtime (for TV shows, this might be episode runtime)
            const runtime = tv.episode_run_time && tv.episode_run_time.length > 0 
                ? `${tv.episode_run_time[0]} min/episode` 
                : 'Unknown';
            
            // Format air dates
            const firstAirDate = tv.first_air_date ? new Date(tv.first_air_date).getFullYear() : 'Unknown';
            const lastAirDate = tv.last_air_date ? new Date(tv.last_air_date).getFullYear() : 'Ongoing';
            const airDateRange = firstAirDate === lastAirDate ? firstAirDate : `${firstAirDate} - ${lastAirDate}`;
            
            // Format status
            const status = tv.status || 'Unknown';
            
            let html = `
                <div class="movie-details">
                    <button class="btn btn-outline-warning mb-3" onclick="searchMovies()">
                        <i class="fas fa-arrow-left"></i> Back to Search
                    </button>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <img src="${TMDB_IMAGE_BASE}${tv.poster_path}" class="img-fluid rounded shadow-lg" alt="${tv.name}">
                        </div>
                        <div class="col-md-8">
                            <h2 class="text-warning mb-3">${tv.name}</h2>
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <p><strong class="text-warning">First Aired:</strong> ${airDateRange}</p>
                                    <p><strong class="text-warning">Seasons:</strong> ${tv.number_of_seasons || 'Unknown'}</p>
                                    <p><strong class="text-warning">Episodes:</strong> ${tv.number_of_episodes || 'Unknown'}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p><strong class="text-warning">Episode Runtime:</strong> ${runtime}</p>
                                    <p><strong class="text-warning">Status:</strong> ${status}</p>
                                    <p><strong class="text-warning">Rating:</strong> ${formatRating(tv.vote_average)}</p>
                                </div>
                            </div>
                            
                            <p><strong class="text-warning">Genres:</strong> ${genres}</p>
                            
                            ${tv.overview ? `
                                <div class="mb-4">
                                    <h5 class="text-warning">Overview</h5>
                                    <p class="text-light">${tv.overview}</p>
                                </div>
                            ` : ''}
                            
                            ${cast.length > 0 ? `
                                <div class="mb-4">
                                    <h5 class="text-warning">Main Cast</h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        ${cast.map(actor => `
                                            <span class="badge bg-secondary">${actor.name} as ${actor.character}</span>
                                        `).join('')}
                                    </div>
                                </div>
                            ` : ''}
                            
                            ${trailer ? `
                                <div class="mb-4">
                                    <h5 class="text-warning">Trailer</h5>
                                    <div class="ratio ratio-16x9">
                                        <iframe src="https://www.youtube.com/embed/${trailer.key}" 
                                                allowfullscreen 
                                                class="rounded"></iframe>
                                    </div>
                                </div>
                            ` : ''}
                            
                            ${tv.networks && tv.networks.length > 0 ? `
                                <div class="mb-3">
                                    <strong class="text-warning">Networks:</strong> 
                                    ${tv.networks.map(network => network.name).join(', ')}
                                </div>
                            ` : ''}
                            
                            ${tv.created_by && tv.created_by.length > 0 ? `
                                <div class="mb-3">
                                    <strong class="text-warning">Created by:</strong> 
                                    ${tv.created_by.map(creator => creator.name).join(', ')}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('searchResults').innerHTML = html;
        })
        .catch(error => {
            console.error('Error fetching TV series details:', error);
            showError('searchResults', 'Failed to load TV series details. Please try again.');
        });
}



// Initialize the app
document.addEventListener('DOMContentLoaded', function () {
    // Load initial quote
    loadQuote();

    // Load actors
    loadActors();

    // Update displays
    updateMySeriesDisplay();
    updateActivePollsDisplay();

    // Auto-load content based on active section
    const activeSection = document.querySelector('.active-section');
    if (activeSection) {
        const sectionId = activeSection.id;
        if (sectionId === 'random') {
            // Don't auto-load random movie
        } else if (sectionId === 'actors') {
            loadActors();
        } else if (sectionId === 'quote') {
            loadQuote();
        }
    }
});
