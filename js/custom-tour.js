// custom-tour.js
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS animation library
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Add back to top button
    addBackToTopButton();

    // Initialize tour builder
    initTourBuilder();
});

function addBackToTopButton() {
    // Create the button element
    const backToTopBtn = document.createElement('button');
    backToTopBtn.id = 'backToTopBtn';
    backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    document.body.appendChild(backToTopBtn);

    // Function to toggle button visibility
    function toggleBackToTopBtn() {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.add('show');
        } else {
            backToTopBtn.classList.remove('show');
        }
    }

    // Add scroll event to toggle button visibility
    window.addEventListener('scroll', toggleBackToTopBtn);

    // Add click event to scroll to top
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Global variable to store tour selections
const tourSelections = {
    destination: null,
    flights: null,
    hotel: null
};

function initTourBuilder() {
    // Variables to track the current step
    let currentStep = 'destination';

    // Initialize destination selection
    initDestinationFilters();
    initDestinationSelection();

    // Initialize flights step
    initFlightsStep();

    // Initialize hotels step
    initHotelsStep();

    // Initialize review step
    initReviewStep();

    // Setup navigation between steps
    setupNavigation();

    function initDestinationFilters() {
        const continentSelect = document.getElementById('continentSelect');
        const countrySelect = document.getElementById('countrySelect');
        const searchInput = document.getElementById('destinationSearch');

        // Get all destination cards
        const destinationCards = document.querySelectorAll('.destination-card');

        // Extract unique countries by continent
        const countriesByContinent = {};

        destinationCards.forEach(card => {
            const continent = card.dataset.continent;
            const country = card.dataset.country;

            if (!countriesByContinent[continent]) {
                countriesByContinent[continent] = [];
            }

            if (!countriesByContinent[continent].includes(country)) {
                countriesByContinent[continent].push(country);
            }
        });

        // Update country options when continent changes
        continentSelect.addEventListener('change', function() {
            const selectedContinent = this.value;

            // Reset country select
            countrySelect.innerHTML = '<option value="">Select Country</option>';

            if (selectedContinent && countriesByContinent[selectedContinent]) {
                // Enable country select
                countrySelect.disabled = false;

                // Sort countries alphabetically
                const countries = countriesByContinent[selectedContinent].sort();

                // Add country options for selected continent
                countries.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country;
                    option.textContent = country.charAt(0).toUpperCase() + country.slice(1);
                    countrySelect.appendChild(option);
                });

                // Filter destinations by continent
                filterDestinations(selectedContinent, '', '');
            } else {
                // Disable country select if no continent selected
                countrySelect.disabled = true;

                // Show all destinations
                filterDestinations('', '', '');
            }
        });

        // Filter destinations when country changes
        countrySelect.addEventListener('change', function() {
            filterDestinations(continentSelect.value, this.value, searchInput.value);
        });

        // Filter destinations when search input changes
        searchInput.addEventListener('input', function() {
            filterDestinations(continentSelect.value, countrySelect.value, this.value);
        });
    }

    function filterDestinations(continent, country, searchTerm) {
        const destinationCards = document.querySelectorAll('.destination-card');
        let visibleCount = 0;

        destinationCards.forEach(card => {
            const cardContinent = card.dataset.continent;
            const cardCountry = card.dataset.country;
            const cardDestination = card.dataset.destination;
            const cardTitle = card.querySelector('h3').textContent.toLowerCase();

            // Check if the card matches all filters
            const matchesContinent = !continent || cardContinent === continent;
            const matchesCountry = !country || cardCountry === country;
            const matchesSearch = !searchTerm ||
                cardTitle.includes(searchTerm.toLowerCase()) ||
                cardDestination.includes(searchTerm.toLowerCase());

            // Show or hide the card based on filter match
            if (matchesContinent && matchesCountry && matchesSearch) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
    }

    function initDestinationSelection() {
        const destinationCards = document.querySelectorAll('.destination-card');
        const nextBtn = document.getElementById('destinationNextBtn');

        // Handle destination card selection (with toggle functionality)
        destinationCards.forEach(card => {
            card.addEventListener('click', function() {
                // Check if this card is already selected
                const isSelected = this.classList.contains('selected');

                // Remove selected class from all cards
                destinationCards.forEach(c => c.classList.remove('selected'));

                // If this card wasn't selected before, select it
                // Otherwise, deselect it (by doing nothing since we already removed the class)
                if (!isSelected) {
                    // Add selected class to clicked card
                    this.classList.add('selected');

                    // Store selected destination
                    const destinationName = this.querySelector('h3').textContent;
                    const destinationCountry = this.querySelector('p').textContent.split(',')[0].trim();
                    const destinationContinent = this.querySelector('p').textContent.split(',')[1].trim();
                    const destinationId = this.dataset.destid;

                    tourSelections.destination = {
                        name: destinationName,
                        country: destinationCountry,
                        continent: destinationContinent,
                        id: destinationId
                    };

                    // Enable next button
                    nextBtn.disabled = false;

                    // Filter flights and hotels to match the selected destination
                    filterFlightsByDestination(destinationId);
                    filterHotelsByDestination(destinationId);
                } else {
                    // Clear the selection
                    tourSelections.destination = null;

                    // Disable next button
                    nextBtn.disabled = true;

                    // Reset flights and hotels (show all)
                    resetFlightsAndHotels();
                }
            });
        });
    }

    function resetFlightsAndHotels() {
        // Reset flight cards display
        const flightCards = document.querySelectorAll('.flight-card');
        flightCards.forEach(card => {
            card.style.display = 'flex';
            card.classList.remove('selected');
        });

        // Reset hotel cards display
        const hotelCards = document.querySelectorAll('.hotel-card');
        hotelCards.forEach(card => {
            card.style.display = 'block';
            card.classList.remove('selected');
        });

        // Reset flights selection
        tourSelections.flights = null;
        document.getElementById('flightsNextBtn').disabled = true;

        // Reset hotels selection
        tourSelections.hotel = null;
        document.getElementById('hotelsNextBtn').disabled = true;
    }

    function filterFlightsByDestination(destId) {
        const flightCards = document.querySelectorAll('.flight-card');
        let visibleCount = 0;

        flightCards.forEach(card => {
            if (card.dataset.destid === destId) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show no flights message if needed
        const noFlightsMessage = document.querySelector('.flight-cards .no-flights-message') ||
            document.createElement('div');

        if (visibleCount === 0) {
            if (!document.querySelector('.flight-cards .no-flights-message')) {
                noFlightsMessage.className = 'no-flights-message';
                noFlightsMessage.innerHTML = '<p>No flights are currently available for this destination. Please try a different destination.</p>';
                document.getElementById('flights').appendChild(noFlightsMessage);
            }
        } else if (document.querySelector('.flight-cards .no-flights-message')) {
            document.querySelector('.flight-cards .no-flights-message').remove();
        }
    }

    function filterHotelsByDestination(destId) {
        const hotelCards = document.querySelectorAll('.hotel-card');
        let visibleCount = 0;

        hotelCards.forEach(card => {
            if (card.dataset.destid === destId) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show no hotels message if needed
        const noHotelsMessage = document.querySelector('.hotel-cards .no-hotels-message') ||
            document.createElement('div');

        if (visibleCount === 0) {
            if (!document.querySelector('.hotel-cards .no-hotels-message')) {
                noHotelsMessage.className = 'no-hotels-message';
                noHotelsMessage.innerHTML = '<p>No hotels are currently available for this destination. Please try a different destination.</p>';
                document.getElementById('hotelList').appendChild(noHotelsMessage);
            }
        } else if (document.querySelector('.hotel-cards .no-hotels-message')) {
            document.querySelector('.hotel-cards .no-hotels-message').remove();
        }
    }

    function initFlightsStep() {
        const flightCards = document.querySelectorAll('.flight-card');
        const nextBtn = document.getElementById('flightsNextBtn');

        // Disable next button initially
        nextBtn.disabled = true;

        // Handle flight selection (with toggle functionality)
        flightCards.forEach(flight => {
            const selectBtn = flight.querySelector('.select-btn');

            selectBtn.addEventListener('click', function() {
                // Check if this flight is already selected
                const isSelected = flight.classList.contains('selected');

                // Remove selected class from all flights
                flightCards.forEach(f => {
                    f.classList.remove('selected');
                    f.querySelector('.select-btn').textContent = 'Select';
                });

                if (!isSelected) {
                    // Add selected class to clicked flight
                    flight.classList.add('selected');

                    // Store selected flight
                    const airline = flight.querySelector('.flight-airline span').textContent;
                    const departureTime = flight.querySelector('.departure .time').textContent;
                    const departureAirport = flight.querySelector('.departure .airport').textContent;
                    const arrivalTime = flight.querySelector('.arrival .time').textContent;
                    const arrivalAirport = flight.querySelector('.arrival .airport').textContent;
                    const flightId = flight.dataset.flightid;
                    const price = flight.querySelector('.price').textContent;

                    tourSelections.flights = {
                        airline,
                        departureTime,
                        departureAirport,
                        arrivalTime,
                        arrivalAirport,
                        price,
                        id: flightId
                    };

                    // Enable next button
                    nextBtn.disabled = false;

                    // Change button text
                    this.textContent = 'Selected';
                } else {
                    // Clear selection
                    tourSelections.flights = null;

                    // Disable next button
                    nextBtn.disabled = true;
                }
            });
        });
    }

    function initHotelsStep() {
        const hotelCards = document.querySelectorAll('.hotel-card');
        const nextBtn = document.getElementById('hotelsNextBtn');

        // Hotels are now required, disable the next button initially
        nextBtn.disabled = true;

        // Handle hotel selection (with toggle functionality)
        hotelCards.forEach(hotel => {
            const selectBtn = hotel.querySelector('.select-btn');

            selectBtn.addEventListener('click', function() {
                // Check if this hotel is already selected
                const isSelected = hotel.classList.contains('selected');

                // Remove selected class from all hotels
                hotelCards.forEach(h => {
                    h.classList.remove('selected');
                    h.querySelector('.select-btn').textContent = 'Select Hotel';
                });

                if (!isSelected) {
                    // Add selected class to clicked hotel
                    hotel.classList.add('selected');

                    // Store selected hotel
                    const hotelName = hotel.querySelector('h3').textContent;
                    const location = hotel.querySelector('.hotel-location').textContent;
                    const hotelId = hotel.dataset.hotelid;

                    // Get hotel rating (count stars)
                    let rating = 0;
                    const stars = hotel.querySelectorAll('.hotel-rating .fas.fa-star');
                    rating = stars.length;

                    const price = hotel.querySelector('.hotel-price .price').textContent;

                    // Get hotel amenities
                    const amenities = [];
                    hotel.querySelectorAll('.hotel-amenities span').forEach(span => {
                        amenities.push(span.textContent.trim());
                    });

                    tourSelections.hotel = {
                        name: hotelName,
                        location,
                        rating,
                        price,
                        amenities,
                        id: hotelId
                    };

                    // Enable next button when hotel is selected
                    nextBtn.disabled = false;

                    // Change button text
                    this.textContent = 'Selected';
                } else {
                    // Clear the selection
                    tourSelections.hotel = null;

                    // Disable next button when hotel is deselected
                    nextBtn.disabled = true;
                }
            });
        });

        // Hotel filtering
        const hotelPriceFilter = document.getElementById('hotelPriceFilter');
        const hotelStarFilter = document.getElementById('hotelStarFilter');

        // Add event listeners for hotel filters
        if (hotelPriceFilter) {
            hotelPriceFilter.addEventListener('change', filterHotels);
        }

        if (hotelStarFilter) {
            hotelStarFilter.addEventListener('change', filterHotels);
        }

        function filterHotels() {
            const priceFilter = hotelPriceFilter.value;
            const starFilter = parseInt(hotelStarFilter.value) || 0;
            const selectedDestId = tourSelections.destination ? tourSelections.destination.id : null;

            // Get all hotel cards - we need to consider ALL hotels matching the destination,
            // not just currently visible ones
            const hotelCards = document.querySelectorAll('.hotel-card');

            hotelCards.forEach(hotel => {
                // First check if this hotel matches the selected destination
                if (!selectedDestId || hotel.dataset.destid === selectedDestId) {
                    // Get price - extract only numbers from the price string
                    const priceText = hotel.querySelector('.price').textContent;
                    const price = parseFloat(priceText.replace(/[^\d.]/g, ''));

                    // Count stars
                    const stars = hotel.querySelectorAll('.hotel-rating .fas.fa-star').length;

                    let matchesPrice = true;
                    if (priceFilter === 'budget') {
                        matchesPrice = price < 100;
                    } else if (priceFilter === 'moderate') {
                        matchesPrice = price >= 100 && price <= 200;
                    } else if (priceFilter === 'luxury') {
                        matchesPrice = price > 200;
                    }

                    const matchesStars = stars >= starFilter;

                    // Show or hide based on filter criteria
                    if (matchesPrice && matchesStars) {
                        hotel.style.display = 'block';
                    } else {
                        hotel.style.display = 'none';
                    }
                } else {
                    hotel.style.display = 'none';
                }
            });

            // Check if any hotels are visible after filtering
            const anyVisibleHotels = Array.from(hotelCards).some(hotel => hotel.style.display === 'block');

            // Show no hotels message if needed
            const noHotelsMessage = document.querySelector('.hotel-cards .no-hotels-message') ||
                document.createElement('div');

            if (!anyVisibleHotels) {
                if (!document.querySelector('.hotel-cards .no-hotels-message')) {
                    noHotelsMessage.className = 'no-hotels-message';
                    noHotelsMessage.innerHTML = '<p>No hotels match your filter criteria. Please try different filters.</p>';
                    document.getElementById('hotelList').appendChild(noHotelsMessage);
                }
            } else if (document.querySelector('.hotel-cards .no-hotels-message')) {
                document.querySelector('.hotel-cards .no-hotels-message').remove();
            }
        }
    }

    // Updated initReviewStep function with token handling
    function initReviewStep() {
        // Add click event to edit buttons
        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const stepToEdit = this.getAttribute('data-step');

                // Navigate to the specified step
                navigateToStep(stepToEdit);
            });
        });

        // Update booking button click handler - using event delegation to prevent duplicates
        const bookTourBtn = document.getElementById('bookTourBtn');

        if (bookTourBtn) {
            // Remove any existing event listeners first to prevent duplicates
            bookTourBtn.replaceWith(bookTourBtn.cloneNode(true));

            // Get the fresh reference after replacement
            const freshBookTourBtn = document.getElementById('bookTourBtn');

            // Add the event listener to the fresh button
            freshBookTourBtn.addEventListener('click', handleBookingSubmission);
        }

        // First get a booking token from the server
        let bookingToken = '';

        function handleBookingSubmission() {
            // Disable the button immediately to prevent double-clicks
            this.disabled = true;

            // Ensure all required selections are made
            if (!tourSelections.destination || !tourSelections.flights || !tourSelections.hotel) {
                alert('Please complete all selections before booking');
                this.disabled = false;
                return;
            }

            // Store button reference for use inside the promises
            const bookButton = this;

            // If we don't have a token yet, get one first
            if (!bookingToken) {
                // Create empty form data to request a token
                const tokenRequest = new FormData();

                // Show loading state
                bookButton.textContent = 'Preparing booking...';

                // Request a token from the server
                fetch('book_custom_tour.php', {
                    method: 'POST',
                    body: tokenRequest
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.booking_token) {
                            bookingToken = data.booking_token;
                            // Now proceed with the actual booking
                            proceedWithBooking(bookButton);
                        } else {
                            alert('login first.');
                            bookButton.textContent = 'Book Your Tour';
                            bookButton.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while preparing your booking. Please try again.');
                        bookButton.textContent = 'Book Your Tour';
                        bookButton.disabled = false;
                    });
            } else {
                // We already have a token, proceed directly
                proceedWithBooking(bookButton);
            }
        }

        function proceedWithBooking(bookButton) {
            // User is logged in, proceed with booking
            const selectedDestId = tourSelections.destination.id;
            const selectedFlightId = tourSelections.flights.id;
            const selectedHotelId = tourSelections.hotel.id; // Hotel is now required

            // Create form data
            const formData = new FormData();
            formData.append('destid', selectedDestId);
            formData.append('flightid', selectedFlightId);
            formData.append('hotelid', selectedHotelId);
            formData.append('booking_token', bookingToken);

            // Show loading state
            bookButton.textContent = 'Booking...';

            // Send booking request
            fetch('book_custom_tour.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    // Update the token for potential future use
                    if (data.booking_token) {
                        bookingToken = data.booking_token;
                    }

                    if (data.success) {
                        alert('Your tour has been booked successfully!');
                        // Redirect to my bookings page
                        window.location.href = 'my_bookings.php';
                    } else {
                        if (data.message === 'not_logged_in') {
                            // Handle not logged in case
                            alert('Please log in to book a tour');
                            document.getElementById('authModal').style.display = 'block';
                            document.body.style.overflow = 'hidden';
                        } else {
                            // Handle other errors
                            alert('There was a problem booking your tour: ' + data.message);
                        }
                        // Reset button
                        bookButton.textContent = 'Book Your Tour';
                        bookButton.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while booking your tour. Please try again.');
                    // Reset button
                    bookButton.textContent = 'Book Your Tour';
                    bookButton.disabled = false;
                });
        }
    }

    function updateReviewStep() {
        // Update destination
        document.getElementById('reviewDestination').textContent =
            `${tourSelections.destination.name}, ${tourSelections.destination.country}`;

        // Update flights
        const flightsElement = document.getElementById('reviewFlights');
        flightsElement.innerHTML = `
            <p>${tourSelections.flights.airline}</p>
            <p>From ${tourSelections.flights.departureAirport} to ${tourSelections.flights.arrivalAirport}</p>
            <p>Departure: ${tourSelections.flights.departureTime}</p>
            <p>Price: ${tourSelections.flights.price}</p>
        `;

        // Update hotel - hotel is now required so we don't need the conditional check
        const hotelElement = document.getElementById('reviewHotel');
        hotelElement.innerHTML = `
            <p>${tourSelections.hotel.name} (${tourSelections.hotel.rating}★)</p>
            <p>${tourSelections.hotel.location}</p>
            <p>${tourSelections.hotel.price} per night</p>
        `;

        // Calculate and update price breakdown
        updatePriceBreakdown();
    }

    function updatePriceBreakdown() {
        // Parse prices and remove currency symbols
        const flightPrice = parseFloat(tourSelections.flights.price.replace(/[^\d.]/g, ''));

        // Hotel price (now always included since hotel is required)
        const hotelPrice = parseFloat(tourSelections.hotel.price.replace(/[^\d.]/g, ''));

        // Calculate taxes (for demonstration - 5% of subtotal)
        const subtotal = flightPrice + hotelPrice;
        const taxesCost = subtotal * 0.05;

        // Calculate total cost
        const totalCost = subtotal + taxesCost;

        // Update price breakdown in the UI
        document.getElementById('flightsCost').textContent = `$${flightPrice.toFixed(2)}`;
        document.getElementById('hotelCost').textContent = `$${hotelPrice.toFixed(2)}`;
        document.getElementById('taxesCost').textContent = `$${taxesCost.toFixed(2)}`;
        document.getElementById('sidebarTotalPrice').textContent = `$${totalCost.toFixed(2)}`;
        document.getElementById('totalPrice').textContent = `$${totalCost.toFixed(2)}`;
    }

    function setupNavigation() {
        // Navigation for Destination step
        document.getElementById('destinationNextBtn').addEventListener('click', function() {
            navigateToStep('flights');
        });

        // Navigation for Flights step
        document.getElementById('flightsBackBtn').addEventListener('click', function() {
            navigateToStep('destination');
        });

        document.getElementById('flightsNextBtn').addEventListener('click', function() {
            navigateToStep('hotels');
        });

        // Navigation for Hotels step
        document.getElementById('hotelsBackBtn').addEventListener('click', function() {
            navigateToStep('flights');
        });

        document.getElementById('hotelsNextBtn').addEventListener('click', function() {
            navigateToStep('review');
        });

        // Navigation for Review step
        document.getElementById('reviewBackBtn').addEventListener('click', function() {
            navigateToStep('hotels');
        });
    }

    function navigateToStep(step) {
        // Make sure required selections are made
        if (step === 'review') {
            // Check if destination and flight are selected (hotel is now required)
            if (!tourSelections.destination) {
                alert('Please select a destination first');
                navigateToStep('destination');
                return;
            }
            if (!tourSelections.flights) {
                alert('Please select a flight first');
                navigateToStep('flights');
                return;
            }
            if (!tourSelections.hotel) {
                alert('Please select a hotel first');
                navigateToStep('hotels');
                return;
            }
        }

        // Hide all steps
        document.querySelectorAll('.build-step').forEach(el => {
            el.classList.remove('active');
        });

        // Show the target step
        document.getElementById(`${step}Step`).classList.add('active');

        // Update progress tracker
        updateProgressTracker(step);

        // Update review info if navigating to review step
        if (step === 'review') {
            updateReviewStep();
        }

        // Scroll to top of step
        window.scrollTo({
            top: document.querySelector('.progress-tracker').offsetTop - 100,
            behavior: 'smooth'
        });

        // Update current step
        currentStep = step;
    }

    function updateProgressTracker(step) {
        // Define steps order
        const steps = ['destination', 'flights', 'hotels', 'review'];
        const currentStepIndex = steps.indexOf(step);

        // Update progress bar fill width
        const progressFill = document.getElementById('progressFill');
        const progressPercentage = ((currentStepIndex + 1) / steps.length) * 100;
        progressFill.style.width = `${progressPercentage}%`;

        // Update step indicators
        document.querySelectorAll('.progress-steps .step').forEach((stepEl, index) => {
            if (index <= currentStepIndex) {
                stepEl.classList.add('active');

                if (index < currentStepIndex) {
                    stepEl.classList.add('completed');
                } else {
                    stepEl.classList.remove('completed');
                }
            } else {
                stepEl.classList.remove('active', 'completed');
            }
        });
    }
}