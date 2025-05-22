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



    // Optional: Add keyboard navigation for tabs
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab' && e.target.classList.contains('tab-btn')) {
            // Allow normal tab navigation
            return;
        }

        if (e.key === 'Enter' && e.target.classList.contains('tab-btn')) {
            e.target.click();
        }
    });

    // Add confirmation for page reload/navigation if there are pending operations
    let hasPendingOperations = false;

    window.addEventListener('beforeunload', function(e) {
        if (hasPendingOperations) {
            e.preventDefault();
            e.returnValue = 'You have operations in progress. Are you sure you want to leave?';
            return e.returnValue;
        }
    });

    // Helper function to set pending operations
    function setPendingOperation(isPending) {
        hasPendingOperations = isPending;
    }

    // Update delete function to use pending operations
    deleteBookingBtns.forEach(button => {
        const originalClickHandler = button.onclick;
        button.addEventListener('click', function(e) {
            if (!this.disabled) {
                setPendingOperation(true);
            }
        });
    });
});