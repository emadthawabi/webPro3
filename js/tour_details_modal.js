// Tour Details Modal Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const modal = document.getElementById('tourDetailsModal');
    const closeModalBtn = document.getElementById('closeModal');
    const detailsLoading = document.querySelector('.details-loading');
    const detailsContent = document.querySelector('.details-content');
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    // Add click event listeners to all "View Details" buttons
    document.querySelectorAll('.view-details').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            // Get tour ID from the data attribute
            const tourId = this.getAttribute('data-tour-id');

            // Show modal
            openModal();

            // Fetch tour details
            fetchTourDetails(tourId);
        });
    });

    // Close modal when clicking the close button
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }

    // Close modal when clicking outside the modal content
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal on ESC key press
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'block') {
            closeModal();
        }
    });

    // Tab functionality
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Get the tab name from data attribute
            const tabName = this.getAttribute('data-tab');

            // Show the corresponding tab pane
            document.getElementById(`${tabName}-tab`).classList.add('active');
        });
    });

    // Book Tour button click
    document.getElementById('bookTourBtn').addEventListener('click', function(e) {
        e.preventDefault();
        alert('Booking functionality would be implemented here.');
        // You could redirect to a booking page or show another modal here
    });

    // Function to open the modal
    function openModal() {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent scrolling of body

        // Reset tab state
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabPanes.forEach(pane => pane.classList.remove('active'));

        // Set first tab as active
        tabButtons[0].classList.add('active');
        tabPanes[0].classList.add('active');

        // Show loading, hide content
        detailsLoading.style.display = 'flex';
        detailsContent.style.display = 'none';
    }

    // Function to close the modal
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = ''; // Restore scrolling
    }

    // Function to fetch tour details from the server
    function fetchTourDetails(tourId) {
        fetch(`get_tour_details.php?id=${tourId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide loading, show content
                    detailsLoading.style.display = 'none';
                    detailsContent.style.display = 'block';

                    // Populate the data into the modal
                    populateTourDetails(data.data);
                } else {
                    alert('Error: ' + data.message);
                    closeModal();
                }
            })
            .catch(error => {
                console.error('Error fetching tour details:', error);
                alert('Failed to load tour details. Please try again.');
                closeModal();
            });
    }

    // Function to populate tour details into the modal
    function populateTourDetails(data) {
        // Tour data
        document.getElementById('modal-tour-name').textContent = data.tour.name;
        document.getElementById('modal-tour-price').textContent = '$' + parseFloat(data.tour.price).toFixed(2);
        document.getElementById('modal-tour-duration').textContent = data.tour.duration + ' days, ' + (data.tour.duration - 1) + ' nights';
        document.getElementById('modal-tour-rating').textContent = parseFloat(data.tour.rating).toFixed(1);

        // Set the image
        if (data.tour.image && data.tour.image !== 'placeholder.jpg') {
            document.getElementById('modal-tour-image').src = 'uploadImages/' + data.tour.image;
        } else {
            document.getElementById('modal-tour-image').src = 'uploadImages/placeholder.jpg';
        }

        // Generate star rating HTML
        const ratingStars = generateStarRating(data.tour.rating);
        document.getElementById('modal-tour-stars').innerHTML = ratingStars;

        // Destination data
        document.getElementById('modal-continent').textContent = capitalizeFirstLetter(data.destination.continent);
        document.getElementById('modal-country').textContent = capitalizeFirstLetter(data.destination.country);
        document.getElementById('modal-city').textContent = capitalizeFirstLetter(data.destination.city);
        document.getElementById('modal-description').textContent = data.destination.description;

        // Flight data
        document.getElementById('modal-airport').textContent = data.flight.airport;
        document.getElementById('modal-flight-time').textContent = data.flight.time;
        document.getElementById('modal-flight-begin').textContent = data.flight.begin;
        document.getElementById('modal-flight-type').textContent = data.flight.type;
        document.getElementById('modal-flight-date').textContent = formatDate(data.flight.date);
        document.getElementById('modal-flight-price').textContent = data.flight.price;

        // Hotel data
        document.getElementById('modal-hotel-name').textContent = data.hotel.name;
        document.getElementById('modal-hotel-stars').textContent = data.hotel.stars + ' stars';
        document.getElementById('modal-hotel-time').textContent = data.hotel.time;
        document.getElementById('modal-hotel-people').textContent = data.hotel.numofpeople + ' people';
        document.getElementById('modal-hotel-location').textContent = data.hotel.location;
        document.getElementById('modal-hotel-price').textContent = '$' + data.hotel.price;
    }

    // Helper function to generate star rating HTML
    function generateStarRating(rating) {
        const fullStars = Math.floor(rating);
        const halfStar = (rating - fullStars) >= 0.5;
        let starsHTML = '';

        for (let i = 1; i <= 5; i++) {
            if (i <= fullStars) {
                starsHTML += '<i class="fas fa-star"></i>';
            } else if (i === fullStars + 1 && halfStar) {
                starsHTML += '<i class="fas fa-star-half-alt"></i>';
            } else {
                starsHTML += '<i class="far fa-star"></i>';
            }
        }

        return starsHTML;
    }

    // Helper function to capitalize first letter of a string
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // Helper function to format date
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const date = new Date(dateString);
        return date.toLocaleDateString(undefined, options);
    }
});