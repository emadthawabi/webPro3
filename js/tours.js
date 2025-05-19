// Initialize AOS animation library
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Initialize filter functionality
    initializeFilters();

    // Add back to top button
    addBackToTopButton();
});

// Dynamic country options based on selected continent
const countryOptions = {
    'europe': [
        { value: 'france', label: 'France' },
        { value: 'italy', label: 'Italy' },
        { value: 'greece', label: 'Greece' },
        { value: 'spain', label: 'Spain' },
        { value: 'uk', label: 'United Kingdom' }
    ],
    'asia': [
        { value: 'japan', label: 'Japan' },
        { value: 'thailand', label: 'Thailand' },
        { value: 'india', label: 'India' },
        { value: 'vietnam', label: 'Vietnam' },
        { value: 'china', label: 'China' }
    ],
    'africa': [
        { value: 'south-africa', label: 'South Africa' },
        { value: 'egypt', label: 'Egypt' },
        { value: 'morocco', label: 'Morocco' },
        { value: 'kenya', label: 'Kenya' },
        { value: 'tanzania', label: 'Tanzania' }
    ],
    'north-america': [
        { value: 'usa', label: 'United States' },
        { value: 'canada', label: 'Canada' },
        { value: 'mexico', label: 'Mexico' },
        { value: 'costa-rica', label: 'Costa Rica' },
        { value: 'jamaica', label: 'Jamaica' }
    ],
    'south-america': [
        { value: 'peru', label: 'Peru' },
        { value: 'brazil', label: 'Brazil' },
        { value: 'argentina', label: 'Argentina' },
        { value: 'colombia', label: 'Colombia' },
        { value: 'chile', label: 'Chile' }
    ],
    'australia': [
        { value: 'australia', label: 'Australia' },
        { value: 'new-zealand', label: 'New Zealand' },
        { value: 'fiji', label: 'Fiji' },
        { value: 'samoa', label: 'Samoa' },
        { value: 'tahiti', label: 'Tahiti' }
    ]
};

// Tour data (for sorting purposes)
const tourData = [
    {
        id: 'paris-explorer',
        name: 'Paris Explorer',
        continent: 'europe',
        country: 'france',
        duration: 'medium',
        budget: 'standard',
        price: 1899,
        rating: 4.5,
        popularity: 9
    },
    {
        id: 'roman-holiday',
        name: 'Roman Holiday',
        continent: 'europe',
        country: 'italy',
        duration: 'medium',
        budget: 'standard',
        price: 2199,
        rating: 4.0,
        popularity: 7
    },
    {
        id: 'greek-island-hopping',
        name: 'Greek Island Hopping',
        continent: 'europe',
        country: 'greece',
        duration: 'long',
        budget: 'standard',
        price: 2899,
        rating: 4.9,
        popularity: 8
    },
    {
        id: 'japan-heritage-tour',
        name: 'Japan Heritage Tour',
        continent: 'asia',
        country: 'japan',
        duration: 'long',
        budget: 'luxury',
        price: 3599,
        rating: 4.7,
        popularity: 9
    },
    {
        id: 'thailand-beach-culture',
        name: 'Thailand Beach & Culture',
        continent: 'asia',
        country: 'thailand',
        duration: 'medium',
        budget: 'budget',
        price: 999,
        rating: 4.2,
        popularity: 8
    },
    {
        id: 'ultimate-safari-experience',
        name: 'Ultimate Safari Experience',
        continent: 'africa',
        country: 'south-africa',
        duration: 'long',
        budget: 'luxury',
        price: 4299,
        rating: 4.9,
        popularity: 7
    },
    {
        id: 'ancient-egypt-explorer',
        name: 'Ancient Egypt Explorer',
        continent: 'africa',
        country: 'egypt',
        duration: 'medium',
        budget: 'standard',
        price: 1799,
        rating: 4.3,
        popularity: 6
    },
    {
        id: 'new-york-city-explorer',
        name: 'New York City Explorer',
        continent: 'north-america',
        country: 'usa',
        duration: 'medium',
        budget: 'standard',
        price: 1699,
        rating: 4.1,
        popularity: 7
    },
    {
        id: 'canadian-rockies-adventure',
        name: 'Canadian Rockies Adventure',
        continent: 'north-america',
        country: 'canada',
        duration: 'long',
        budget: 'standard',
        price: 2499,
        rating: 4.6,
        popularity: 6
    },
    {
        id: 'inca-trail-to-machu-picchu',
        name: 'Inca Trail to Machu Picchu',
        continent: 'south-america',
        country: 'peru',
        duration: 'long',
        budget: 'standard',
        price: 2299,
        rating: 4.8,
        popularity: 9
    },
    {
        id: 'australian-coastal-explorer',
        name: 'Australian Coastal Explorer',
        continent: 'australia',
        country: 'australia',
        duration: 'long',
        budget: 'luxury',
        price: 4899,
        rating: 4.7,
        popularity: 7
    }
];

// Initialize filter functionality
function initializeFilters() {
    const continentFilter = document.getElementById('continentFilter');
    const countryFilter = document.getElementById('countryFilter');
    const durationFilter = document.getElementById('durationFilter');
    const budgetFilter = document.getElementById('budgetFilter');
    const searchInput = document.getElementById('searchInput');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const sortOptions = document.getElementById('sortOptions');

    // Event listeners for filters
    continentFilter.addEventListener('change', handleContinentChange);
    countryFilter.addEventListener('change', filterTours);
    durationFilter.addEventListener('change', filterTours);
    budgetFilter.addEventListener('change', filterTours);
    searchInput.addEventListener('input', filterTours);
    clearFiltersBtn.addEventListener('click', clearFilters);
    resetFiltersBtn.addEventListener('click', clearFilters);
    sortOptions.addEventListener('change', sortTours);

    // Initial filtering
    filterTours();
}

// Handle continent change to update country options
function handleContinentChange() {
    const continentFilter = document.getElementById('continentFilter');
    const countryFilter = document.getElementById('countryFilter');
    const selectedContinent = continentFilter.value;

    // Clear country dropdown
    countryFilter.innerHTML = '';

    // Add default option
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'All Countries';
    countryFilter.appendChild(defaultOption);

    // If a continent is selected, add its countries
    if (selectedContinent && countryOptions[selectedContinent]) {
        countryFilter.disabled = false;

        countryOptions[selectedContinent].forEach(country => {
            const option = document.createElement('option');
            option.value = country.value;
            option.textContent = country.label;
            countryFilter.appendChild(option);
        });
    } else {
        // If no continent selected, disable country dropdown
        countryFilter.disabled = true;
        countryFilter.innerHTML = '<option value="">Select Continent First</option>';
    }

    // Filter tours after continent change
    filterTours();
}

// Filter tours based on selected criteria
function filterTours() {
    const continentFilter = document.getElementById('continentFilter');
    const countryFilter = document.getElementById('countryFilter');
    const durationFilter = document.getElementById('durationFilter');
    const budgetFilter = document.getElementById('budgetFilter');
    const searchInput = document.getElementById('searchInput');
    const tourCards = document.querySelectorAll('.tour-card');
    const noResults = document.getElementById('noResults');
    const tourCount = document.getElementById('tourCount');

    const continent = continentFilter.value;
    const country = countryFilter.value;
    const duration = durationFilter.value;
    const budget = budgetFilter.value;
    const searchTerm = searchInput.value.toLowerCase();

    let visibleCount = 0;

    tourCards.forEach(card => {
        const cardContinent = card.dataset.continent;
        const cardCountry = card.dataset.country;
        const cardDuration = card.dataset.duration;
        const cardBudget = card.dataset.budget;
        const cardTitle = card.querySelector('h3').textContent.toLowerCase();
        const cardLocation = card.querySelector('.tour-location').textContent.toLowerCase();

        // Check if card matches all selected filters
        const matchesContinent = !continent || cardContinent === continent;
        const matchesCountry = !country || cardCountry === country;
        const matchesDuration = !duration || cardDuration === duration;
        const matchesBudget = !budget || cardBudget === budget;
        const matchesSearch = !searchTerm ||
            cardTitle.includes(searchTerm) ||
            cardLocation.includes(searchTerm);

        // Show or hide card based on matches
        if (matchesContinent && matchesCountry && matchesDuration && matchesBudget && matchesSearch) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Update tour count
    tourCount.textContent = `(${visibleCount})`;

    // Show or hide "no results" message
    if (visibleCount === 0) {
        noResults.style.display = 'flex';
    } else {
        noResults.style.display = 'none';
    }
}

// Clear all filters and reset to default
function clearFilters() {
    const continentFilter = document.getElementById('continentFilter');
    const countryFilter = document.getElementById('countryFilter');
    const durationFilter = document.getElementById('durationFilter');
    const budgetFilter = document.getElementById('budgetFilter');
    const searchInput = document.getElementById('searchInput');

    // Reset all filter values
    continentFilter.value = '';
    durationFilter.value = '';
    budgetFilter.value = '';
    searchInput.value = '';

    // Reset country dropdown
    countryFilter.value = '';
    countryFilter.disabled = true;
    countryFilter.innerHTML = '<option value="">Select Continent First</option>';

    // Hide "no results" message
    document.getElementById('noResults').style.display = 'none';

    // Show all tour cards
    const tourCards = document.querySelectorAll('.tour-card');
    tourCards.forEach(card => {
        card.style.display = 'block';
    });

    // Update tour count with total number of cards
    document.getElementById('tourCount').textContent = `(${tourCards.length})`;

    // Important: Call filterTours to reset internal state
    filterTours();
}

// Sort tours based on selected option
function sortTours() {
    const sortOption = document.getElementById('sortOptions').value;

    switch(sortOption) {
        case 'popular':
            sortToursByProperty('popularity', true);
            break;
        case 'price-asc':
            sortToursByProperty('price', false);
            break;
        case 'price-desc':
            sortToursByProperty('price', true);
            break;
        case 'duration':
            sortToursByProperty('duration', true);
            break;
        case 'rating':
            sortToursByProperty('rating', true);
            break;
    }
}

// Sort tours by a specific property
function sortToursByProperty(property, descending) {
    const tourGrid = document.getElementById('tourGrid');
    const tourCards = Array.from(document.querySelectorAll('.tour-card'));

    // Get sorted tour data
    const sortedTours = [...tourData].sort((a, b) => {
        let comparison = 0;

        if (property === 'duration') {
            // Convert duration to numeric values for sorting
            const durationValues = { 'short': 1, 'medium': 2, 'long': 3 };
            comparison = durationValues[a[property]] - durationValues[b[property]];
        } else {
            comparison = a[property] - b[property];
        }

        return descending ? -comparison : comparison;
    });

    // Create a map of sorted IDs for efficient lookup
    const sortedIdsMap = {};
    sortedTours.forEach((tour, index) => {
        sortedIdsMap[tour.id] = index;
    });

    // Sort tour cards based on the sorted data
    tourCards.sort((a, b) => {
        const aTitle = a.querySelector('h3').textContent;
        const bTitle = b.querySelector('h3').textContent;

        // Find corresponding tour data
        const aTour = tourData.find(tour => tour.name === aTitle);
        const bTour = tourData.find(tour => tour.name === bTitle);

        if (aTour && bTour) {
            return sortedIdsMap[aTour.id] - sortedIdsMap[bTour.id];
        }

        return 0;
    });

    // Remove all tour cards from the grid
    while (tourGrid.firstChild) {
        tourGrid.removeChild(tourGrid.firstChild);
    }

    // Re-append sorted cards
    tourCards.forEach(card => {
        if (card.style.display !== 'none') {
            tourGrid.appendChild(card);
        }
    });
}

// Add back to top button
function addBackToTopButton() {
    // Create button element
    const backToTopBtn = document.createElement('button');
    backToTopBtn.id = 'backToTopBtn';
    backToTopBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';
    document.body.appendChild(backToTopBtn);

    // Show/hide button based on scroll position
    window.addEventListener('scroll', () => {
        if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
            backToTopBtn.classList.add('show');
        } else {
            backToTopBtn.classList.remove('show');
        }
    });

    // Scroll to top when button is clicked
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Handle pagination clicks
document.querySelectorAll('.page-btn, .page-next').forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all buttons
        document.querySelectorAll('.page-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // Add active class to clicked button if it's a page button
        if (this.classList.contains('page-btn')) {
            this.classList.add('active');
        }

        // In a real application, you would load the appropriate page of results here
        // For this demo, we'll just scroll to the top of the tours section
        document.querySelector('.tours-display').scrollIntoView({ behavior: 'smooth' });
    });
});

// Handle view details button clicks
document.querySelectorAll('.view-details').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const tourName = this.closest('.tour-card').querySelector('h3').textContent;
        alert(`Viewing details for ${tourName}. This would lead to the tour details page in a complete application.`);
    });
});

// Handle newsletter form submission
document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const emailInput = this.querySelector('input[type="email"]');

    if (emailInput.value) {
        alert('Thank you for subscribing to our newsletter!');
        emailInput.value = '';
    } else {
        alert('Please enter a valid email address.');
    }
});

// For mobile menu toggle
const hamburger = document.getElementById('hamburger');
const navLinks = document.getElementById('navLinks');

if (hamburger) {
    hamburger.addEventListener('click', function() {
        navLinks.classList.toggle('active');
    });
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(e) {
    if (navLinks && navLinks.classList.contains('active') && !e.target.closest('.nav-container')) {
        navLinks.classList.remove('active');
    }
});

document.getElementById('loginBtn').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('authModal').style.display = "block";
    document.body.style.overflow = "hidden";
});


