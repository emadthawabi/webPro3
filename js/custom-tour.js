// custom-tour-modified.js
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

        // Handle destination card selection
        destinationCards.forEach(card => {
            card.addEventListener('click', function() {
                // Remove selected class from all cards
                destinationCards.forEach(c => c.classList.remove('selected'));

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
            });
        });
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

        // Handle flight selection
        flightCards.forEach(flight => {
            const selectBtn = flight.querySelector('.select-btn');

            selectBtn.addEventListener('click', function() {
                // Remove selected class from all flights
                flightCards.forEach(f => f.classList.remove('selected'));

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

                // Reset text for other buttons
                flightCards.forEach(f => {
                    if (f !== flight) {
                        f.querySelector('.select-btn').textContent = 'Select';
                    }
                });
            });
        });

        // Handle flight filtering (direct flights only)
        const directFlightsOnly = document.getElementById('directFlightsOnly');
        if (directFlightsOnly) {
            directFlightsOnly.addEventListener('change', function() {
                // In this implementation, all flights are already direct, so this is a placeholder
                console.log('Filter flights: direct only =', this.checked);
            });
        }

        // Handle flight sorting
        const flightSort = document.getElementById('flightSort');
        if (flightSort) {
            flightSort.addEventListener('change', function() {
                // For demo purposes
                console.log('Sort flights by:', this.value);

                // Get all visible flight cards
                const visibleFlights = Array.from(document.querySelectorAll('.flight-card'))
                    .filter(card => card.style.display !== 'none');

                // Sort flights based on selected criteria
                if (this.value === 'price') {
                    sortFlightsByPrice(visibleFlights);
                } else if (this.value === 'time') {
                    sortFlightsByTime(visibleFlights);
                }
            });
        }
    }

    function sortFlightsByPrice(flightCards) {
        const flightsContainer = document.getElementById('flights');

        // Sort flight cards by price
        flightCards.sort((a, b) => {
            const priceA = parseFloat(a.querySelector('.price').textContent.replace(/[^0-9.]/g, ''));
            const priceB = parseFloat(b.querySelector('.price').textContent.replace(/[^0-9.]/g, ''));
            return priceA - priceB;
        });

        // Remove all cards
        flightCards.forEach(card => card.remove());

        // Re-append in sorted order
        flightCards.forEach(card => flightsContainer.appendChild(card));
    }

    function sortFlightsByTime(flightCards) {
        const flightsContainer = document.getElementById('flights');

        // Sort flight cards by departure time
        flightCards.sort((a, b) => {
            // For simplicity, just use the text comparison - in a real application,
            // you'd want to parse the time properly
            const timeA = a.querySelector('.departure .time').textContent;
            const timeB = b.querySelector('.departure .time').textContent;
            return timeA.localeCompare(timeB);
        });

        // Remove all cards
        flightCards.forEach(card => card.remove());

        // Re-append in sorted order
        flightCards.forEach(card => flightsContainer.appendChild(card));
    }

    function initHotelsStep() {
        const hotelCards = document.querySelectorAll('.hotel-card');
        const nextBtn = document.getElementById('hotelsNextBtn');

        // Disable next button initially
        nextBtn.disabled = true;

        // Handle hotel selection
        hotelCards.forEach(hotel => {
            const selectBtn = hotel.querySelector('.select-btn');

            selectBtn.addEventListener('click', function() {
                // Remove selected class from all hotels
                hotelCards.forEach(h => h.classList.remove('selected'));

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

                // Enable next button
                nextBtn.disabled = false;

                // Change button text
                this.textContent = 'Selected';

                // Reset text for other buttons
                hotelCards.forEach(h => {
                    if (h !== hotel) {
                        h.querySelector('.select-btn').textContent = 'Select Hotel';
                    }
                });
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

            // Apply filters to visible hotels only (those matching the destination)
            const visibleHotels = Array.from(document.querySelectorAll('.hotel-card'))
                .filter(card => card.style.display !== 'none');

            visibleHotels.forEach(hotel => {
                const price = parseFloat(hotel.querySelector('.price').textContent.replace(/[^0-9.]/g, ''));
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

                if (matchesPrice && matchesStars) {
                    hotel.style.display = 'block';
                } else {
                    hotel.style.display = 'none';
                }
            });
        }
    }

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

        // Update hotel
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
        const flightPrice = parseFloat(tourSelections.flights.price.replace(/[^0-9.]/g, ''));
        const hotelPrice = parseFloat(tourSelections.hotel.price.replace(/[^0-9.]/g, ''));

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