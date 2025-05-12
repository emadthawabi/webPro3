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

    // Set minimum date for date inputs to today
    setMinDateToday();
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

function setMinDateToday() {
    // Set minimum date for departure and return date inputs
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('departureDate').min = today;
    document.getElementById('returnDate').min = today;
}

function initTourBuilder() {
    // Variables to track the current step and validation status
    let currentStep = 'destination';
    const tourSelections = {
        destination: null,
        dates: {
            departure: null,
            return: null,
            travelers: {
                adults: 2,
                children: 0,
                infants: 0
            }
        },
        flights: {
            outbound: null,
            return: null
        },
        hotel: null,
        activities: []
    };

    // Initialize country select options based on continent selection
    initDestinationFilters();

    // Initialize destination selection
    initDestinationSelection();

    // Initialize dates step
    initDatesStep();

    // Initialize flights step
    initFlightsStep();

    // Initialize hotels step
    initHotelsStep();

    // Initialize activities step
    initActivitiesStep();

    // Initialize review step
    initReviewStep();

    // Setup navigation between steps
    setupNavigation();

    function initDestinationFilters() {
        const continentSelect = document.getElementById('continentSelect');
        const countrySelect = document.getElementById('countrySelect');
        const searchInput = document.getElementById('destinationSearch');

        // Country options by continent
        const countryOptions = {
            'europe': ['France', 'Italy', 'Spain', 'Greece', 'Germany', 'UK'],
            'asia': ['Japan', 'Thailand', 'Indonesia', 'Vietnam', 'India', 'China'],
            'africa': ['South Africa', 'Morocco', 'Egypt', 'Kenya', 'Tanzania'],
            'north-america': ['USA', 'Canada', 'Mexico', 'Costa Rica'],
            'south-america': ['Brazil', 'Peru', 'Argentina', 'Colombia', 'Chile'],
            'australia': ['Australia', 'New Zealand', 'Fiji', 'Samoa']
        };

        // Update country options when continent changes
        continentSelect.addEventListener('change', function() {
            const selectedContinent = this.value;

            // Reset country select
            countrySelect.innerHTML = '<option value="">Select Country</option>';

            if (selectedContinent) {
                // Enable country select
                countrySelect.disabled = false;

                // Add country options for selected continent
                countryOptions[selectedContinent].forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.toLowerCase().replace(' ', '-');
                    option.textContent = country;
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
                const destinationCountry = this.querySelector('p').textContent.split(',')[0].trim().replace('France', 'France'); // Extract country name

                tourSelections.destination = {
                    name: destinationName,
                    country: destinationCountry,
                    continent: this.dataset.continent
                };

                // Enable next button
                nextBtn.disabled = false;
            });
        });
    }

    function initDatesStep() {
        const departureDate = document.getElementById('departureDate');
        const returnDate = document.getElementById('returnDate');
        const adultCount = document.getElementById('adultCount');
        const childCount = document.getElementById('childCount');
        const infantCount = document.getElementById('infantCount');
        const nextBtn = document.getElementById('datesNextBtn');

        // Function to validate dates step
        function validateDatesStep() {
            const departureDateValue = departureDate.value;
            const returnDateValue = returnDate.value;

            if (departureDateValue && returnDateValue) {
                const departureTimestamp = new Date(departureDateValue).getTime();
                const returnTimestamp = new Date(returnDateValue).getTime();

                // Check if return date is after departure date
                if (returnTimestamp > departureTimestamp) {
                    nextBtn.disabled = false;
                    return true;
                } else {
                    nextBtn.disabled = true;
                    return false;
                }
            } else {
                nextBtn.disabled = true;
                return false;
            }
        }

        // Update tour selections when dates change
        departureDate.addEventListener('change', function() {
            if (this.value) {
                tourSelections.dates.departure = this.value;

                // Update return date minimum to be the departure date
                returnDate.min = this.value;

                // If return date is before departure date, clear it
                if (returnDate.value && new Date(returnDate.value) < new Date(this.value)) {
                    returnDate.value = '';
                    tourSelections.dates.return = null;
                }
            } else {
                tourSelections.dates.departure = null;
            }

            validateDatesStep();
        });

        returnDate.addEventListener('change', function() {
            tourSelections.dates.return = this.value || null;
            validateDatesStep();
        });

        // Handle traveler count adjustments
        const decreaseBtns = document.querySelectorAll('.decrease-btn');
        const increaseBtns = document.querySelectorAll('.increase-btn');

        decreaseBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const currentValue = parseInt(input.value);

                if (currentValue > parseInt(input.min)) {
                    input.value = currentValue - 1;

                    // Update tour selections
                    if (input.id === 'adultCount') {
                        tourSelections.dates.travelers.adults = parseInt(input.value);
                    } else if (input.id === 'childCount') {
                        tourSelections.dates.travelers.children = parseInt(input.value);
                    } else if (input.id === 'infantCount') {
                        tourSelections.dates.travelers.infants = parseInt(input.value);
                    }
                }
            });
        });

        increaseBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const currentValue = parseInt(input.value);

                if (currentValue < parseInt(input.max)) {
                    input.value = currentValue + 1;

                    // Update tour selections
                    if (input.id === 'adultCount') {
                        tourSelections.dates.travelers.adults = parseInt(input.value);
                    } else if (input.id === 'childCount') {
                        tourSelections.dates.travelers.children = parseInt(input.value);
                    } else if (input.id === 'infantCount') {
                        tourSelections.dates.travelers.infants = parseInt(input.value);
                    }
                }
            });
        });

        // Handle direct input of traveler counts
        [adultCount, childCount, infantCount].forEach(input => {
            input.addEventListener('change', function() {
                // Ensure value is within min/max range
                const value = parseInt(this.value);
                const min = parseInt(this.min);
                const max = parseInt(this.max);

                if (value < min) {
                    this.value = min;
                } else if (value > max) {
                    this.value = max;
                }

                // Update tour selections
                if (this.id === 'adultCount') {
                    tourSelections.dates.travelers.adults = parseInt(this.value);
                } else if (this.id === 'childCount') {
                    tourSelections.dates.travelers.children = parseInt(this.value);
                } else if (this.id === 'infantCount') {
                    tourSelections.dates.travelers.infants = parseInt(this.value);
                }
            });
        });
    }

    function initFlightsStep() {
        const outboundFlights = document.querySelectorAll('#outboundFlights .flight-card');
        const returnFlights = document.querySelectorAll('#returnFlights .flight-card');
        const nextBtn = document.getElementById('flightsNextBtn');

        // Disable next button initially
        nextBtn.disabled = true;

        // Handle flight selection for outbound flights
        outboundFlights.forEach(flight => {
            const selectBtn = flight.querySelector('.select-btn');

            selectBtn.addEventListener('click', function() {
                // Remove selected class from all outbound flights
                outboundFlights.forEach(f => f.classList.remove('selected'));

                // Add selected class to clicked flight
                flight.classList.add('selected');

                // Store selected outbound flight
                const airline = flight.querySelector('.flight-airline span').textContent;
                const departureTime = flight.querySelector('.departure .time').textContent;
                const departureAirport = flight.querySelector('.departure .airport').textContent;
                const arrivalTime = flight.querySelector('.arrival .time').textContent;
                const arrivalAirport = flight.querySelector('.arrival .airport').textContent;
                const duration = flight.querySelector('.duration').textContent;
                const price = flight.querySelector('.price').textContent;

                tourSelections.flights.outbound = {
                    airline,
                    departureTime,
                    departureAirport,
                    arrivalTime,
                    arrivalAirport,
                    duration,
                    price
                };

                // Enable next button if both flights are selected
                if (tourSelections.flights.outbound && tourSelections.flights.return) {
                    nextBtn.disabled = false;
                }

                // Change button text
                this.textContent = 'Selected';

                // Reset text for other buttons
                outboundFlights.forEach(f => {
                    if (f !== flight) {
                        f.querySelector('.select-btn').textContent = 'Select';
                    }
                });
            });
        });

        // Handle flight selection for return flights
        returnFlights.forEach(flight => {
            const selectBtn = flight.querySelector('.select-btn');

            selectBtn.addEventListener('click', function() {

                // Remove selected class from all return flights
                returnFlights.forEach(f => f.classList.remove('selected'));

                // Add selected class to clicked flight
                flight.classList.add('selected');

                // Store selected return flight
                const airline = flight.querySelector('.flight-airline span').textContent;
                const departureTime = flight.querySelector('.departure .time').textContent;
                const departureAirport = flight.querySelector('.departure .airport').textContent;
                const arrivalTime = flight.querySelector('.arrival .time').textContent;
                const arrivalAirport = flight.querySelector('.arrival .airport').textContent;
                const duration = flight.querySelector('.duration').textContent;
                const price = flight.querySelector('.price').textContent;

                tourSelections.flights.return = {
                    airline,
                    departureTime,
                    departureAirport,
                    arrivalTime,
                    arrivalAirport,
                    duration,
                    price
                };

                // Enable next button if both flights are selected
                if (tourSelections.flights.outbound && tourSelections.flights.return) {
                    nextBtn.disabled = false;
                }

                // Change button text
                this.textContent = 'Selected';

                // Reset text for other buttons
                returnFlights.forEach(f => {
                    if (f !== flight) {
                        f.querySelector('.select-btn').textContent = 'Select';
                    }
                });
            });
        });

        // Handle flight filtering and sorting
        const directFlightsOnly = document.getElementById('directFlightsOnly');
        const directFlightsOnlyReturn = document.getElementById('directFlightsOnlyReturn');
        const flightSortOutbound = document.getElementById('flightSortOutbound');
        const flightSortReturn = document.getElementById('flightSortReturn');

        // Filter flights based on direct/non-direct
        directFlightsOnly.addEventListener('change', function() {
            // For demo purposes - in a real implementation, we would filter the flights
            // based on the 'stops' information
            console.log('Filter outbound flights: direct only =', this.checked);
        });

        directFlightsOnlyReturn.addEventListener('change', function() {
            // For demo purposes
            console.log('Filter return flights: direct only =', this.checked);
        });

        // Sort flights based on selected criteria
        flightSortOutbound.addEventListener('change', function() {
            // For demo purposes
            console.log('Sort outbound flights by:', this.value);
        });

        flightSortReturn.addEventListener('change', function() {
            // For demo purposes
            console.log('Sort return flights by:', this.value);
        });
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
                    amenities
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

        // Hotel filtering (for demo purposes)
        const hotelPriceFilter = document.getElementById('hotelPriceFilter');
        const hotelStarFilter = document.getElementById('hotelStarFilter');
        const amenityCheckboxes = document.querySelectorAll('.amenity-checkbox input');

        hotelPriceFilter.addEventListener('change', function() {
            // For demo purposes
            console.log('Filter hotels by price:', this.value);
        });

        hotelStarFilter.addEventListener('change', function() {
            // For demo purposes
            console.log('Filter hotels by stars:', this.value);
        });

        amenityCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // For demo purposes - collect all checked amenities
                const selectedAmenities = [];
                amenityCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        selectedAmenities.push(cb.value);
                    }
                });

                console.log('Filter hotels by amenities:', selectedAmenities);
            });
        });
    }

    function initActivitiesStep() {
        const activityCards = document.querySelectorAll('.activity-card');
        const selectedActivitiesList = document.getElementById('selectedActivitiesList');
        const noActivitiesMessage = document.querySelector('.no-activities-message');

        // Handle activity selection
        activityCards.forEach(activity => {
            const addBtn = activity.querySelector('.select-activity');

            addBtn.addEventListener('click', function() {
                const activityName = activity.querySelector('h3').textContent;
                const activityPrice = activity.querySelector('.activity-price-select .price').textContent;
                const activityDuration = activity.querySelector('.activity-meta span:first-child').textContent;

                // Check if activity is already selected
                const isAlreadySelected = tourSelections.activities.some(act => act.name === activityName);

                if (isAlreadySelected) {
                    // Remove activity from selections
                    tourSelections.activities = tourSelections.activities.filter(act => act.name !== activityName);

                    // Remove activity from selected activities list
                    const activityItemToRemove = document.querySelector(`.selected-activity-item[data-activity="${activityName}"]`);
                    if (activityItemToRemove) {
                        selectedActivitiesList.removeChild(activityItemToRemove);
                    }

                    // Update button text and class
                    this.textContent = 'Add';
                    this.classList.remove('added');
                    this.innerHTML = '<i class="fas fa-plus"></i> Add';

                    // Show "No activities" message if no activities are selected
                    if (tourSelections.activities.length === 0) {
                        noActivitiesMessage.style.display = 'block';
                    }
                } else {
                    // Add activity to selections
                    tourSelections.activities.push({
                        name: activityName,
                        price: activityPrice,
                        duration: activityDuration
                    });

                    // Create and add activity item to selected activities list
                    const activityItem = document.createElement('div');
                    activityItem.className = 'selected-activity-item';
                    activityItem.setAttribute('data-activity', activityName);

                    activityItem.innerHTML = `
                        <div class="activity-name-price">
                            <div class="activity-name">${activityName}</div>
                            <div class="activity-price">${activityPrice}</div>
                        </div>
                        <button class="remove-activity"><i class="fas fa-times"></i></button>
                    `;

                    // Add click event to remove button
                    activityItem.querySelector('.remove-activity').addEventListener('click', function() {
                        // Remove activity from selections
                        tourSelections.activities = tourSelections.activities.filter(act => act.name !== activityName);

                        // Remove activity item from list
                        selectedActivitiesList.removeChild(activityItem);

                        // Update button text and class for the activity card
                        addBtn.textContent = 'Add';
                        addBtn.classList.remove('added');
                        addBtn.innerHTML = '<i class="fas fa-plus"></i> Add';

                        // Show "No activities" message if no activities are selected
                        if (tourSelections.activities.length === 0) {
                            noActivitiesMessage.style.display = 'block';
                        }
                    });

                    // Hide "No activities" message
                    noActivitiesMessage.style.display = 'none';

                    // Add activity item to list
                    selectedActivitiesList.appendChild(activityItem);

                    // Update button text and class
                    this.textContent = 'Added';
                    this.classList.add('added');
                    this.innerHTML = '<i class="fas fa-check"></i> Added';
                }
            });
        });

        // Activity filtering
        const activityCategoryFilter = document.getElementById('activityCategoryFilter');
        const activityPriceFilter = document.getElementById('activityPriceFilter');
        const activityDurationFilter = document.getElementById('activityDurationFilter');

        // For demo purposes - add event listeners for filters
        [activityCategoryFilter, activityPriceFilter, activityDurationFilter].forEach(filter => {
            filter.addEventListener('change', function() {
                console.log(`Filter activities by ${this.id}:`, this.value);
                // In a real implementation, we would filter the activities based on the selected criteria
            });
        });
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

        // Add click events to booking buttons
        const bookBtn = document.querySelector('.book-btn');
        const saveBtn = document.querySelector('.save-btn');

        bookBtn.addEventListener('click', function() {
            alert('Your custom tour has been booked! A confirmation email will be sent shortly.');
        });

        saveBtn.addEventListener('click', function() {
            alert('Your custom tour itinerary has been saved. You can access it from your account.');
        });
    }

    function updateReviewStep() {
        // Update destination
        document.getElementById('reviewDestination').textContent =
            `${tourSelections.destination.name}, ${tourSelections.destination.country}`;

        // Update dates
        const departureDate = new Date(tourSelections.dates.departure);
        const returnDate = new Date(tourSelections.dates.return);
        const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };

        document.getElementById('reviewDates').textContent =
            `${departureDate.toLocaleDateString('en-US', dateOptions)} - ${returnDate.toLocaleDateString('en-US', dateOptions)}`;

        // Update travelers
        const { adults, children, infants } = tourSelections.dates.travelers;
        document.getElementById('reviewTravelers').textContent =
            `${adults} Adult${adults !== 1 ? 's' : ''}, ${children} Child${children !== 1 ? 'ren' : ''}, ${infants} Infant${infants !== 1 ? 's' : ''}`;

        // Update flights
        const flightsElement = document.getElementById('reviewFlights');
        flightsElement.innerHTML = '';

        // Outbound flight
        const outboundFlightElement = document.createElement('div');
        outboundFlightElement.className = 'flight-summary';
        outboundFlightElement.innerHTML = `
            <p class="flight-type">Outbound:</p>
            <p>${tourSelections.flights.outbound.airline} - ${tourSelections.flights.outbound.departureAirport} to ${tourSelections.flights.outbound.arrivalAirport}</p>
            <p>${departureDate.toLocaleDateString('en-US', dateOptions)} - ${tourSelections.flights.outbound.departureTime} to ${tourSelections.flights.outbound.arrivalTime}</p>
        `;
        flightsElement.appendChild(outboundFlightElement);

        // Return flight
        const returnFlightElement = document.createElement('div');
        returnFlightElement.className = 'flight-summary';
        returnFlightElement.innerHTML = `
            <p class="flight-type">Return:</p>
            <p>${tourSelections.flights.return.airline} - ${tourSelections.flights.return.departureAirport} to ${tourSelections.flights.return.arrivalAirport}</p>
            <p>${returnDate.toLocaleDateString('en-US', dateOptions)} - ${tourSelections.flights.return.departureTime} to ${tourSelections.flights.return.arrivalTime}</p>
        `;
        flightsElement.appendChild(returnFlightElement);

        // Update hotel
        const hotelElement = document.getElementById('reviewHotel');
        hotelElement.innerHTML = `
            <p>${tourSelections.hotel.name} (${tourSelections.hotel.rating}★)</p>
            <p>${tourSelections.hotel.location}</p>
            <p>${calculateNights(departureDate, returnDate)} nights at ${tourSelections.hotel.price} per night</p>
        `;

        // Update activities
        const activitiesElement = document.getElementById('reviewActivities');

        if (tourSelections.activities.length > 0) {
            const activitiesList = document.createElement('ul');

            tourSelections.activities.forEach(activity => {
                const listItem = document.createElement('li');
                listItem.textContent = `${activity.name} - ${activity.price}`;
                activitiesList.appendChild(listItem);
            });

            activitiesElement.innerHTML = '';
            activitiesElement.appendChild(activitiesList);
        } else {
            activitiesElement.innerHTML = '<p>No activities selected</p>';
        }

        // Calculate and update price breakdown
        updatePriceBreakdown();
    }

    function calculateNights(startDate, endDate) {
        const timeDiff = endDate.getTime() - startDate.getTime();
        const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
        return nights;
    }

    function updatePriceBreakdown() {
        // Parse prices and remove currency symbols
        const outboundFlightPrice = parseFloat(tourSelections.flights.outbound.price.replace(/[^0-9.]/g, ''));
        const returnFlightPrice = parseFloat(tourSelections.flights.return.price.replace(/[^0-9.]/g, ''));
        const hotelPricePerNight = parseFloat(tourSelections.hotel.price.replace(/[^0-9.]/g, ''));

        // Calculate number of nights
        const departureDate = new Date(tourSelections.dates.departure);
        const returnDate = new Date(tourSelections.dates.return);
        const nights = calculateNights(departureDate, returnDate);

        // Calculate flight costs (per person)
        const totalTravelers = tourSelections.dates.travelers.adults + tourSelections.dates.travelers.children;
        const flightsCost = (outboundFlightPrice + returnFlightPrice) * totalTravelers;

        // Calculate hotel cost
        const hotelCost = hotelPricePerNight * nights;

        // Calculate activities cost
        let activitiesCost = 0;
        tourSelections.activities.forEach(activity => {
            const activityPrice = parseFloat(activity.price.replace(/[^0-9.]/g, ''));
            activitiesCost += activityPrice * totalTravelers;
        });

        // Calculate taxes (for demonstration - 5% of subtotal)
        const subtotal = flightsCost + hotelCost + activitiesCost;
        const taxesCost = subtotal * 0.05;

        // Calculate total cost
        const totalCost = subtotal + taxesCost;

        // Update price breakdown in the UI
        document.getElementById('flightsCost').textContent = `$${flightsCost.toFixed(0)}`;
        document.getElementById('hotelCost').textContent = `$${hotelCost.toFixed(0)}`;
        document.getElementById('activitiesCost').textContent = `$${activitiesCost.toFixed(0)}`;
        document.getElementById('taxesCost').textContent = `$${taxesCost.toFixed(0)}`;
        document.getElementById('sidebarTotalPrice').textContent = `$${totalCost.toFixed(0)}`;
        document.getElementById('totalPrice').textContent = `$${totalCost.toFixed(0)}`;
    }

    function setupNavigation() {
        // Navigation for Destination step
        document.getElementById('destinationNextBtn').addEventListener('click', function() {
            navigateToStep('dates');
        });

        // Navigation for Dates step
        document.getElementById('datesBackBtn').addEventListener('click', function() {
            navigateToStep('destination');
        });

        document.getElementById('datesNextBtn').addEventListener('click', function() {
            navigateToStep('flights');
        });

        // Navigation for Flights step
        document.getElementById('flightsBackBtn').addEventListener('click', function() {
            navigateToStep('dates');
        });

        document.getElementById('flightsNextBtn').addEventListener('click', function() {
            navigateToStep('hotels');
        });

        // Navigation for Hotels step
        document.getElementById('hotelsBackBtn').addEventListener('click', function() {
            navigateToStep('flights');
        });

        document.getElementById('hotelsNextBtn').addEventListener('click', function() {
            navigateToStep('activities');
        });

        // Navigation for Activities step
        document.getElementById('activitiesBackBtn').addEventListener('click', function() {
            navigateToStep('hotels');
        });

        document.getElementById('activitiesNextBtn').addEventListener('click', function() {
            navigateToStep('review');
        });

        // Navigation for Review step
        document.getElementById('reviewBackBtn').addEventListener('click', function() {
            navigateToStep('activities');
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
        const steps = ['destination', 'dates', 'flights', 'hotels', 'activities', 'review'];
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