<?php
// Define a base path constant that can be used throughout the application
if (!defined('BASE_PATH')) {
    // This will be the root directory of your application
    define('BASE_PATH', '/');
}
?>

<div class="admin-header">
    <h2><i class="fas fa-hiking"></i> Tours Management</h2>
    <!-- Add New Tour Button -->
    <button class="add-tour-btn" data-modal-target="add-tour-modal">
        <i class="fas fa-plus-circle"></i> Add New Tour
    </button>
</div>

<div class="search-filter">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="search-input" id="tour-search" placeholder="Search tours..." data-table-search="tours-table">
    </div>

    <select class="filter-select" id="price-filter" data-table-filter="tours-table">
        <option value="">All Price Ranges</option>
        <option value="budget">Budget (Under $100)</option>
        <option value="standard">Standard ($100 - $500)</option>
        <option value="premium">Premium ($500 - $1000)</option>
        <option value="luxury">Luxury (Over $1000)</option>
    </select>

    <select class="filter-select" id="rating-filter" data-table-filter="tours-table">
        <option value="">All Ratings</option>
        <option value="5">5 Stars</option>
        <option value="4">4 Stars</option>
        <option value="3">3 Stars</option>
        <option value="2">2 Stars</option>
        <option value="1">1 Star</option>
    </select>
</div>

<div class="data-table-wrapper">
    <table class="data-table" id="tours-table">
        <thead>
        <tr>
            <th>Tour ID</th>
            <th>Tour Name</th>
            <th>Destination</th>
            <th>Flight</th>
            <th>Hotel</th>
            <th>Price</th>
            <th>Rating</th>
            <th>Duration (Days)</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Connect to database
        try {
            $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

            // Query to get all tours with joined destination, flight, and hotel data
            $query = "SELECT t.*, d.country, d.city, f.airport, h.stars 
                    FROM tours t 
                    LEFT JOIN destination d ON t.destid = d.destid 
                    LEFT JOIN flights f ON t.flightid = f.flightid 
                    LEFT JOIN hotels h ON t.hotelid = h.hotelid 
                    ORDER BY t.tourid ASC";
            $result = $db->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Determine price range for filtering
                    $priceRange = '';
                    $price = floatval($row['price']);
                    if ($price < 100) {
                        $priceRange = 'budget';
                    } elseif ($price < 500) {
                        $priceRange = 'standard';
                    } elseif ($price < 1000) {
                        $priceRange = 'premium';
                    } else {
                        $priceRange = 'luxury';
                    }

                    // Format destination
                    $destination = htmlspecialchars($row['country']) . ', ' . htmlspecialchars($row['city']);

                    // Format flight
                    $flight = htmlspecialchars($row['airport']);

                    // Format hotel
                    $hotel = htmlspecialchars($row['stars']) . ' Stars';

                    echo '<tr data-price="' . $priceRange . '" data-rating="' . intval($row['rating']) . '">';
                    echo '<td>' . htmlspecialchars($row['tourid']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['tourname']) . '</td>';
                    echo '<td>' . $destination . '</td>';
                    echo '<td>' . $flight . '</td>';
                    echo '<td>' . $hotel . '</td>';
                    echo '<td>$' . htmlspecialchars($row['price']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['rating']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['duration']) . '</td>';
                    echo '<td>
                                <div class="action-btns">
                                    <button class="action-btn edit" title="Edit Tour" data-modal-target="edit-tour-modal" data-tour-id="' . htmlspecialchars($row['tourid']) . '">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn delete delete-btn" title="Delete Tour" data-tour-id="' . htmlspecialchars($row['tourid']) . '">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                              </td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="9" class="text-center">No tours found</td></tr>';
            }

            $db->close();
        } catch (Exception $e) {
            echo '<tr><td colspan="9" class="text-center">Error loading tours: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Add Tour Modal -->
<div class="modal-overlay" id="add-tour-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add New Tour</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <!-- To this: -->
            <form id="add-tour-form" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="tourname">Tour Name</label>
                        <input type="text" id="tourname" name="tourname" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="destid">Destination</label>
                        <select id="destid" name="destid" class="form-control" required>
                            <option value="">Select Destination</option>
                            <?php
                            // Fetch destinations
                            try {
                                $db = new mysqli("localhost", "root", "", "pathfinder", 3306);
                                $query = "SELECT destid, country, city FROM destination ORDER BY country, city";
                                $result = $db->query($query);

                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($row['destid']) . '">' .
                                            htmlspecialchars($row['country']) . ', ' . htmlspecialchars($row['city']) . '</option>';
                                    }
                                }
                                $db->close();
                            } catch (Exception $e) {
                                echo '<option value="">Error loading destinations</option>';
                            }
                            ?>
                        </select>
                    </div>



                    <div class="form-group">
                        <label for="flightid">Flight</label>
                        <select id="flightid" name="flightid" class="form-control" required>
                            <option value="">Select Flight</option>
                            <!-- Flights will be populated based on selected destination -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="hotelid">Hotel</label>
                        <select id="hotelid" name="hotelid" class="form-control" required>
                            <option value="">Select Hotel</option>
                            <!-- Hotels will be populated based on selected destination -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="rating">Rating</label>
                        <select id="rating" name="rating" class="form-control" required>
                            <option value="">Select Rating</option>
                            <option value="5.0">5 Stars</option>
                            <option value="4.5">4.5 Stars</option>
                            <option value="4.0">4 Stars</option>
                            <option value="3.5">3.5 Stars</option>
                            <option value="3.0">3 Stars</option>
                            <option value="2.5">2.5 Stars</option>
                            <option value="2.0">2 Stars</option>
                            <option value="1.5">1.5 Stars</option>
                            <option value="1.0">1 Star</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="duration">Duration (Days)</label>
                        <input type="number" id="duration" name="duration" class="form-control" min="1" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Tour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Tour Modal -->
<div class="modal-overlay" id="edit-tour-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Tour</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="edit-tour-form" method="POST">
                <input type="hidden" id="edit-tourid" name="tourid">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-tourname">Tour Name</label>
                        <input type="text" id="edit-tourname" name="tourname" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-destid">Destination</label>
                        <select id="edit-destid" name="destid" class="form-control" required>
                            <option value="">Select Destination</option>
                            <?php
                            try {
                                $db = new mysqli("localhost", "root", "", "pathfinder", 3306);
                                $query = "SELECT destid, country, city FROM destination ORDER BY country, city";
                                $result = $db->query($query);

                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($row['destid']) . '">' .
                                            htmlspecialchars($row['country']) . ', ' . htmlspecialchars($row['city']) . '</option>';
                                    }
                                }
                                $db->close();
                            } catch (Exception $e) {
                                echo '<option value="">Error loading destinations</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit-flightid">Flight</label>
                        <select id="edit-flightid" name="flightid" class="form-control" required>
                            <option value="">Select Flight</option>
                            <?php
                            try {
                                $db = new mysqli("localhost", "root", "", "pathfinder", 3306);
                                $query = "SELECT f.flightid, f.airport, f.begin, d.city, d.country 
                                         FROM flights f JOIN destination d ON f.destid = d.destid ORDER BY f.airport";
                                $result = $db->query($query);

                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($row['flightid']) . '">' .
                                            htmlspecialchars($row['airport']) . ' (' . htmlspecialchars($row['begin']) . ' to ' .
                                            htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['country']) . ')</option>';
                                    }
                                }
                                $db->close();
                            } catch (Exception $e) {
                                echo '<option value="">Error loading flights</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit-hotelid">Hotel</label>
                        <select id="edit-hotelid" name="hotelid" class="form-control" required>
                            <option value="">Select Hotel</option>
                            <?php
                            try {
                                $db = new mysqli("localhost", "root", "", "pathfinder", 3306);
                                $query = "SELECT h.hotelid, h.stars, h.price, d.city, d.country 
                                         FROM hotels h JOIN destination d ON h.destid = d.destid ORDER BY d.country, d.city";
                                $result = $db->query($query);

                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($row['hotelid']) . '">' .
                                            htmlspecialchars($row['stars']) . ' Stars in ' . htmlspecialchars($row['city']) .
                                            ', ' . htmlspecialchars($row['country']) . ' (' . htmlspecialchars($row['price']) . ')</option>';
                                    }
                                }
                                $db->close();
                            } catch (Exception $e) {
                                echo '<option value="">Error loading hotels</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit-price">Price</label>
                        <input type="number" id="edit-price" name="price" class="form-control" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-rating">Rating</label>
                        <select id="edit-rating" name="rating" class="form-control" required>
                            <option value="">Select Rating</option>
                            <option value="5.0">5 Stars</option>
                            <option value="4.5">4.5 Stars</option>
                            <option value="4.0">4 Stars</option>
                            <option value="3.5">3.5 Stars</option>
                            <option value="3.0">3 Stars</option>
                            <option value="2.5">2.5 Stars</option>
                            <option value="2.0">2 Stars</option>
                            <option value="1.5">1.5 Stars</option>
                            <option value="1.0">1 Star</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit-duration">Duration (Days)</label>
                        <input type="number" id="edit-duration" name="duration" class="form-control" min="1" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Tour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Add New Button Styling */
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .add-tour-btn {
        background-color: #3dbb91;
        color: white;
        border: none;
        border-radius: 15px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .add-tour-btn:hover {
        background-color: #3dbb91;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .add-tour-btn i {
        margin-right: 8px;
        font-size: 18px;
    }

    /* Search & Filter Styling */
    .search-filter {
        display: flex;
        margin-bottom: 20px;
        gap: 15px;
    }

    .search-box {
        flex-grow: 1;
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
    }

    .search-input {
        width: 100%;
        padding: 10px 10px 10px 35px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .filter-select {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        min-width: 180px;
        font-size: 14px;
        cursor: pointer;
    }

    /* No Results Message */
    .no-results {
        text-align: center;
        padding: 20px;
        font-style: italic;
        color: #666;
    }

    /* Modal Animation */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.6);
        z-index: 1000;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease;
    }

    .modal {
        background-color: white;
        border-radius: 8px;
        width: 90%;
        max-width: 700px; /* Wider to accommodate more fields */
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        animation: slideDown 0.4s ease;
        max-height: 90vh;
        overflow-y: auto;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    /* Form Grid Layout */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }

    /* Make certain fields span full width */
    .form-grid .form-group:nth-child(1),
    .form-grid .form-group:nth-child(7) {
        grid-column: span 2;
    }
</style>

<!-- Notification Toast Component -->
<div id="notification-toast" class="notification-toast">
    <div class="toast-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    <div class="toast-message">
        Operation completed successfully!
    </div>
</div>

<style>
    /* Toast Notification Styling */
    .notification-toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: #4CAF50;
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 2000;
    }

    .notification-toast.show {
        transform: translateY(0);
        opacity: 1;
    }

    .toast-icon {
        margin-right: 15px;
        font-size: 24px;
    }

    .toast-message {
        font-size: 16px;
        font-weight: 500;
    }

    /* Hidden class for search/filter */
    tr.hidden {
        display: none;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get all buttons that open modals
        const modalButtons = document.querySelectorAll('[data-modal-target]');

        // Add click event to buttons
        modalButtons.forEach(button => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.modalTarget);
                openModal(modal);

                if (button.classList.contains('edit')) {
                    const tourId = button.getAttribute('data-tour-id');

                    // Set the tour ID in the edit form
                    const tourIdField = document.getElementById('edit-tourid');
                    if (tourIdField) {
                        tourIdField.value = tourId;
                    }

                    // Get the row data
                    const row = button.closest('tr');
                    const tourname = row.cells[1].textContent.trim();
                    const price = row.cells[5].textContent.trim().replace('$', '');
                    const rating = row.cells[6].textContent.trim();
                    const duration = row.cells[7].textContent.trim();

                    // Set the form field values
                    document.getElementById('edit-tourname').value = tourname;
                    document.getElementById('edit-price').value = price;
                    document.getElementById('edit-rating').value = rating;
                    document.getElementById('edit-duration').value = duration;

                    // For dropdown fields, you would need to fetch the current values via AJAX
                    // This is a placeholder for that functionality
                    // In a real implementation, you'd need to make an AJAX call to get the current destid, flightid, and hotelid
                    // Here we're just showing a simplified version
                    fetchTourDetails(tourId);
                }
            });
        });

        // Function to fetch tour details for editing
        function fetchTourDetails(tourId) {
            // Make AJAX call to get the tour details
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'Actions/getTourDetails.php?tourid=' + tourId, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    try {
                        const response = JSON.parse(this.responseText);
                        if (response.success) {
                            const tourData = response.tour;
                            // Set the dropdown values
                            document.getElementById('edit-destid').value = tourData.destid;
                            document.getElementById('edit-flightid').value = tourData.flightid;
                            document.getElementById('edit-hotelid').value = tourData.hotelid;
                            document.getElementById('edit-price').value = tourData.price;
                            document.getElementById('edit-rating').value = tourData.rating;
                            document.getElementById('edit-duration').value = tourData.duration;
                            document.getElementById('edit-tourname').value = tourData.tourname;
                        } else {
                            console.error("Error fetching tour details:", response.message);
                        }
                    } catch (e) {
                        console.error("Error parsing JSON:", e);
                    }
                } else {
                    console.error("Error fetching tour details. Status:", this.status);
                }
            };
            xhr.onerror = function() {
                console.error("Request error when fetching tour details");
            };
            xhr.send();
        }

        // Delete button event listeners
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tourId = this.getAttribute('data-tour-id');

                // Confirm before deletion
                if (confirm('Are you sure you want to delete this tour?')) {
                    // Create AJAX request for deletion
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'Actions/deleteTour.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                    xhr.onload = function() {
                        if (this.status === 200) {
                            // Show success notification
                            const toast = document.getElementById('notification-toast');
                            if (toast) {
                                toast.classList.add('show');
                                setTimeout(() => {
                                    toast.classList.remove('show');
                                }, 3000);
                            }

                            // Remove the row from the table or reload the page
                            const row = button.closest('tr');
                            if (row) {
                                row.remove();
                            } else {
                                location.reload();
                            }
                        } else {
                            alert('Error deleting tour: ' + this.responseText);
                        }
                    };

                    xhr.onerror = function() {
                        alert('Request error. Please try again.');
                    };

                    // Send the tour ID to the server
                    xhr.send('tourid=' + encodeURIComponent(tourId));
                }
            });
        });

        // Close modal when clicking close button
        const closeButtons = document.querySelectorAll('.modal-close, .modal-cancel');
        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const modal = button.closest('.modal-overlay');
                closeModal(modal);
            });
        });

        // Close modal when clicking outside
        window.addEventListener('click', e => {
            if (e.target.classList.contains('modal-overlay')) {
                closeModal(e.target);
            }
        });

        // Function to open modal
        function openModal(modal) {
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            }
        }

        // Function to close modal
        function closeModal(modal) {
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Enable scrolling
            }
        }

        // Handle form submissions via AJAX (for the edit form)
        const editTourForm = document.getElementById('edit-tour-form');
        if (editTourForm) {
            editTourForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form data
                const formData = new FormData(this);

                // Create AJAX request
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Actions/updateTour.php', true);

                xhr.onload = function() {
                    if (this.status === 200) {
                        try {
                            const response = JSON.parse(this.responseText);

                            if (response.success) {
                                // Show success notification
                                const toast = document.getElementById('notification-toast');
                                if (toast) {
                                    // Update toast message
                                    const toastMessage = toast.querySelector('.toast-message');
                                    if (toastMessage) {
                                        toastMessage.textContent = "Tour updated successfully!";
                                    }

                                    toast.classList.add('show');
                                    setTimeout(() => {
                                        toast.classList.remove('show');
                                    }, 3000);
                                }

                                // Close modal
                                const modal = document.getElementById('edit-tour-modal');
                                closeModal(modal);

                                // Reload the page to refresh the tours list
                                location.reload();
                            } else {
                                alert('Error updating tour: ' + response.message);
                            }
                        } catch (e) {
                            console.error("Error parsing JSON:", e);
                            alert('Invalid response from server. Please try again.');
                        }
                    } else {
                        alert('Error updating tour. Status: ' + this.status);
                    }
                };

                xhr.onerror = function() {
                    alert('Request error. Please try again.');
                };

                // Send form data
                xhr.send(formData);
            });
        }

        // Search and Filter functionality
        const searchInput = document.getElementById('tour-search');
        const priceFilter = document.getElementById('price-filter');
        const ratingFilter = document.getElementById('rating-filter');
        const table = document.getElementById('tours-table');
        const rows = table.querySelectorAll('tbody tr');

        // Search function
        function searchTable() {
            const searchTerm = searchInput?.value.toLowerCase() || '';
            const priceValue = priceFilter?.value.toLowerCase() || '';
            const ratingValue = ratingFilter?.value || '';

            // Check if we have any rows in the table
            if(rows.length === 0) return;

            let noResultsFound = true;

            rows.forEach(row => {
                const priceRange = row.getAttribute('data-price') || '';
                const rating = row.getAttribute('data-rating') || '';

                // Get all cell text for search
                let rowText = '';
                Array.from(row.cells).forEach(cell => {
                    rowText += cell.textContent.toLowerCase() + ' ';
                });

                // Check search term
                const matchesSearch = rowText.includes(searchTerm);

                // Check price filter
                const matchesPrice = !priceValue || priceRange === priceValue;

                // Check rating filter
                const matchesRating = !ratingValue || rating === ratingValue;

                // Show or hide the row
                if (matchesSearch && matchesPrice && matchesRating) {
                    row.classList.remove('hidden');
                    noResultsFound = false;
                } else {
                    row.classList.add('hidden');
                }
            });

            // Check if we need to show "No results found" message
            let noResultsRow = table.querySelector('.no-results-row');

            if (noResultsFound) {
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.className = 'no-results-row';
                    const cell = document.createElement('td');
                    cell.colSpan = 9;
                    cell.className = 'text-center';
                    cell.textContent = 'No tours match your search criteria';
                    noResultsRow.appendChild(cell);
                    table.querySelector('tbody').appendChild(noResultsRow);
                }
            } else if (noResultsRow) {
                noResultsRow.remove();
            }
        }

        // Add event listeners for search and filter
        if (searchInput) {
            searchInput.addEventListener('input', searchTable);
        }

        if (priceFilter) {
            priceFilter.addEventListener('change', searchTable);
        }

        if (ratingFilter) {
            ratingFilter.addEventListener('change', searchTable);
        }
        // Handle form submissions via AJAX (for the add form)
        const addTourForm = document.getElementById('add-tour-form');
        if (addTourForm) {
            addTourForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form data
                const formData = new FormData(this);

                // Create AJAX request
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Actions/addTour.php', true);

                xhr.onload = function() {
                    if (this.status === 200) {
                        // Show success notification
                        const toast = document.getElementById('notification-toast');
                        if (toast) {
                            // Update toast message
                            const toastMessage = toast.querySelector('.toast-message');
                            if (toastMessage) {
                                toastMessage.textContent = "Tour added successfully!";
                            }

                            toast.classList.add('show');
                            setTimeout(() => {
                                toast.classList.remove('show');
                            }, 3000);
                        }

                        // Close modal
                        const modal = document.getElementById('add-tour-modal');
                        closeModal(modal);

                        // Reload the page to refresh the tours list
                        location.reload();
                    } else {
                        alert('Error adding tour: ' + this.responseText);
                    }
                };

                xhr.onerror = function() {
                    alert('Request error. Please try again.');
                };

                // Send form data
                xhr.send(formData);
            });
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const destSelect = document.getElementById('destid');
        const flightSelect = document.getElementById('flightid');
        const hotelSelect = document.getElementById('hotelid');

        destSelect.addEventListener('change', function() {
            const selectedDestId = this.value;

            // Fetch flights based on selected destination
            fetchFlights(selectedDestId);
            // Fetch hotels based on selected destination
            fetchHotels(selectedDestId);
        });

        function fetchFlights(destId) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'Actions/getFlights.php?destid=' + destId, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const flights = JSON.parse(this.responseText);
                    flightSelect.innerHTML = '<option value="">Select Flight</option>'; // Reset options
                    flights.forEach(flight => {
                        flightSelect.innerHTML += `<option value="${flight.flightid}">${flight.airport} (${flight.begin})</option>`;
                    });
                }
            };
            xhr.send();
        }

        function fetchHotels(destId) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'Actions/getHotels.php?destid=' + destId, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const hotels = JSON.parse(this.responseText);
                    hotelSelect.innerHTML = '<option value="">Select Hotel</option>'; // Reset options
                    hotels.forEach(hotel => {
                        hotelSelect.innerHTML += `<option value="${hotel.hotelid}">${hotel.hotelname} (${hotel.stars} Stars)</option>`;
                    });
                }
            };
            xhr.send();
        }
    });
</script>
