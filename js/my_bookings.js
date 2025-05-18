// my_bookings.js
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS animations
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-btn');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Get the parent booking details container
            const bookingDetails = this.closest('.booking-details');

            // Get all tab buttons and panes within this booking
            const tabBtns = bookingDetails.querySelectorAll('.tab-btn');
            const tabPanes = bookingDetails.querySelectorAll('.tab-pane');

            // Remove active class from all buttons and panes in this booking
            tabBtns.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Get the tab name from data attribute
            const tabName = this.getAttribute('data-tab');

            // Show the corresponding tab pane
            bookingDetails.querySelector(`#${tabName}-tab`).classList.add('active');
        });
    });

    // Print booking functionality
    const printBookingBtns = document.querySelectorAll('.print-booking');
    if (printBookingBtns.length > 0) {
        printBookingBtns.forEach(button => {
            button.addEventListener('click', function() {
                // Get the specific booking details container
                const bookingDetails = this.closest('.booking-details');

                // Store the booking ID to use for targeting specific booking in print media query
                const bookingId = bookingDetails.getAttribute('data-booking-id');

                // Set a temporary attribute to identify which booking to print
                document.body.setAttribute('data-print-booking', bookingId);

                // Print the page (CSS will handle showing only the selected booking)
                window.print();

                // Remove the temporary attribute
                document.body.removeAttribute('data-print-booking');
            });
        });
    }

    // Cancel booking functionality
    const cancelBookingBtns = document.querySelectorAll('.cancel-booking');
    if (cancelBookingBtns.length > 0) {
        cancelBookingBtns.forEach(button => {
            button.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-booking-id');

                if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
                    // Create form data
                    const formData = new FormData();
                    formData.append('booking_id', bookingId);

                    // Show loading state
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cancelling...';
                    this.disabled = true;

                    // Send cancel request
                    fetch('cancel_booking.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message and refresh page
                                alert('Your booking has been successfully cancelled.');
                                location.reload();
                            } else {
                                // Show error message
                                alert('Failed to cancel booking: ' + data.message);
                                // Reset button
                                this.innerHTML = '<i class="fas fa-times"></i> Cancel';
                                this.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while cancelling your booking. Please try again.');
                            // Reset button
                            this.innerHTML = '<i class="fas fa-times"></i> Cancel';
                            this.disabled = false;
                        });
                }
            });
        });
    }
});