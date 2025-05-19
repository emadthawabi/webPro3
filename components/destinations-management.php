<?php
// Define a base path constant that can be used throughout the application
if (!defined('BASE_PATH')) {
    // This will be the root directory of your application
    define('BASE_PATH', '/');
}
?>

<div class="admin-header">
    <h2><i class="fas fa-map-marker-alt"></i> Destinations Management</h2>
    <!-- Add New Destination Button -->
    <button class="add-destination-btn" data-modal-target="add-destination-modal">
        <i class="fas fa-plus-circle"></i> Add New Destination
    </button>
</div>

<div class="search-filter">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="search-input" id="destination-search" placeholder="Search destinations..." data-table-search="destinations-table">
    </div>

    <select class="filter-select" id="continent-filter" data-table-filter="destinations-table">
        <option value="">All Continents</option>
        <option value="europe">Europe</option>
        <option value="asia">Asia</option>
        <option value="africa">Africa</option>
        <option value="north america">North America</option>
        <option value="south america">South America</option>
        <option value="australia & oceania">Australia & Oceania</option>
    </select>
</div>

<div class="data-table-wrapper">
    <table class="data-table" id="destinations-table">
        <thead>
        <tr>
            <th>Destination ID</th>
            <th>Continent</th>
            <th>Country</th>
            <th>City</th>
            <th>Description</th>
            <!--<th>Image</th>-->
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Connect to database
        try {
            $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

            // Query to get all destinations
            $query = "SELECT * FROM destination ORDER BY destid ASC";
            $result = $db->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr data-continent="' . strtolower(htmlspecialchars($row['continent'])) . '" class="destination-row">';
                    echo '<td>' . htmlspecialchars($row['destid']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['continent']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['country']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['city']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                    /*echo '<td>';*/
                    /*if (!empty($row['destimage'])) {
                        echo '<img src="' . htmlspecialchars($row['destimage']) . '" alt="Destination image" style="max-width: 100px; max-height: 75px;">';
                    } else {
                        echo '<span class="text-muted">No image</span>';
                    }*/
                   /* echo '</td>';*/
                    echo '<td>
                                <div class="action-btns">
                                    <button class="action-btn edit" title="Edit Destination" data-modal-target="edit-destination-modal" data-dest-id="' . htmlspecialchars($row['destid']) . '">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn delete delete-btn" title="Delete Destination" data-dest-id="' . htmlspecialchars($row['destid']) . '">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                              </td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="7" class="text-center">No destinations found</td></tr>';
            }

            $db->close();
        } catch (Exception $e) {
            echo '<tr><td colspan="7" class="text-center">Error loading destinations: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Pagination Controls -->
<div class="pagination" id="destinations-pagination">
    <!-- Pagination buttons will be added by JavaScript -->
</div>

<!-- Debug: Show current script path -->
<?php
// Uncomment this for debugging
// echo "Current script path: " . $_SERVER['SCRIPT_NAME'] . "<br>";
// echo "Document root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
?>

<!-- Add Destination Modal -->
<div class="modal-overlay" id="add-destination-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add New Destination</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <!-- Direct form submission without page reload -->
            <form id="add-destination-form" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="continent">Continent</label>
                        <select id="continent" name="continent" class="form-control" required>
                            <option value="">Select Continent</option>
                            <option value="Europe">Europe</option>
                            <option value="Asia">Asia</option>
                            <option value="Africa">Africa</option>
                            <option value="North America">North America</option>
                            <option value="South America">South America</option>
                            <option value="Australia & Oceania">Australia & Oceania</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                    </div>

                    <!-- Add the new image upload field -->
                    <div class="form-group">
                        <label for="destimage">Destination Image</label>
                        <input type="file" id="destimage" name="destimage" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Select an image for this destination (optional)</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Destination</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Destination Modal -->
<div class="modal-overlay" id="edit-destination-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Destination</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="edit-destination-form" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="edit-destid">ID</label>
                    <input type="number" id="edit-destid" name="destid" class="form-control" required >
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-continent">Continent</label>
                        <select id="edit-continent" name="continent" class="form-control" required>
                            <option value="">Select Continent</option>
                            <option value="Europe">Europe</option>
                            <option value="Asia">Asia</option>
                            <option value="Africa">Africa</option>
                            <option value="North America">North America</option>
                            <option value="South America">South America</option>
                            <option value="Australia & Oceania">Australia & Oceania</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit-country">Country</label>
                        <input type="text" id="edit-country" name="country" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-city">City</label>
                        <input type="text" id="edit-city" name="city" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-description">Description</label>
                        <textarea id="edit-description" name="description" class="form-control" rows="4" required></textarea>
                    </div>

                    <!-- Add the new image upload field -->
                    <div class="form-group">
                        <label for="edit-destimage">Destination Image</label>
                        <input type="file" id="edit-destimage" name="destimage" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Select a new image for this destination (optional)</small>
                        <div id="current-image-preview" class="mt-2">
                            <p>Current image:</p>
                            <img id="edit-image-preview" src="" alt="Current destination image" style="max-width: 200px; max-height: 150px;">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Destination</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .modal-close {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: white;
        font-size: 1.5rem;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        line-height: 1;
        padding: 0;
        margin: 0;
    }
    /* Modal Header Styling */
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px;
        background: linear-gradient(to right, #3dbb91, #4ecaa0);
        border-radius: 8px 8px 0 0;
        position: relative;
    }

    .modal-header h3 {
        color: white;
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .modal-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background-color: rgba(255, 255, 255, 0.2);
    }
    /* Add New Button Styling */
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .add-destination-btn {
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

    .add-destination-btn:hover {
        background-color: #3dbb91;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .add-destination-btn i {
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
    /* Update the modal-overlay CSS */
    .modal-overlay {
        display: none; /* Hidden by default */
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        overflow-y: auto;
        padding: 0;
    }

    .modal-overlay.active,
    .modal-overlay[style*="display: flex"] {
        display: flex !important;
        align-items: center; /* Center vertically */
        justify-content: center; /* Center horizontally */
    }

    .modal {
        background-color: var(--white);
        border-radius: var(--radius);
        width: 90%;
        max-width: 700px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        margin: 0 auto; /* Remove top/bottom margin, keep horizontal centering */
        position: relative;
        z-index: 1001;
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 60px);
    }

    .modal-body {
        padding: 20px;
        overflow-y: auto;
        max-height: 60vh;
    }
    /* Pagination Styling */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 20px;
        margin-bottom: 40px;
    }

    .page-btn {
        min-width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: white;
        color: #555;
        border: 1px solid #ddd;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .page-btn.active {
        background-color: #3dbb91;
        color: white;
        border-color: #3dbb91;
    }

    .page-btn:not(.active):hover {
        background-color: #f9f9f9;
    }

    .page-btn.prev,
    .page-btn.next {
        padding: 0 15px;
    }

    /* Hidden row class for pagination */
    tr.destination-row.hidden-page {
        display: none;
    }

    /* Image preview styling */
    #current-image-preview {
        margin-top: 10px;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 4px;
        display: none;
    }

    #current-image-preview img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
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
                    const destId = button.getAttribute('data-dest-id');

                    // Fix here: use the correct input ID 'edit-destid'
                    const destIdField = document.getElementById('edit-destid');
                    if (destIdField) {
                        destIdField.value = destId;
                    }

                    // Get the row data
                    const row = button.closest('tr');
                    const continent = row.cells[1].textContent.trim();
                    const country = row.cells[2].textContent.trim();
                    const city = row.cells[3].textContent.trim();
                    const description = row.cells[4].textContent.trim();

                    // Get current image if exists
                    const imageCell = row.cells[5];
                    const imgElement = imageCell.querySelector('img');
                    const currentImagePreview = document.getElementById('current-image-preview');
                    const imagePreview = document.getElementById('edit-image-preview');

                    if (imgElement && currentImagePreview && imagePreview) {
                        imagePreview.src = imgElement.src;
                        currentImagePreview.style.display = 'block';
                    } else if (currentImagePreview) {
                        currentImagePreview.style.display = 'none';
                    }

                    // Set the form field values
                    document.getElementById('edit-continent').value = continent;
                    document.getElementById('edit-country').value = country;
                    document.getElementById('edit-city').value = city;
                    document.getElementById('edit-description').value = description;
                }
            });
        });

        // Delete button event listeners
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const destId = this.getAttribute('data-dest-id');

                // Confirm before deletion
                if (confirm('Are you sure you want to delete this destination?')) {
                    // Create AJAX request for deletion
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'Actions/deleteDestination.php', true);
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
                                // Reinitialize pagination after removing a row
                                initPagination();
                            } else {
                                location.reload();
                            }
                        } else {
                            alert('Error deleting destination: ' + this.responseText);
                        }
                    };

                    xhr.onerror = function() {
                        alert('Request error. Please try again.');
                    };

                    // Send the destination ID to the server
                    xhr.send('destid=' + encodeURIComponent(destId));
                }
            });
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

        // Handle form submissions via AJAX
        const addDestinationForm = document.getElementById('add-destination-form');
        if (addDestinationForm) {
            addDestinationForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form data
                const formData = new FormData(this);

                // Create AJAX request
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Actions/addDestination.php', true);

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

                        // Close modal
                        const modal = document.getElementById('add-destination-modal');
                        closeModal(modal);

                        // Reload the page to refresh the destinations list
                        location.reload();
                    } else {
                        alert('Error adding destination: ' + this.responseText);
                    }
                };

                xhr.onerror = function() {
                    alert('Request error. Please try again.');
                };

                // Send form data
                xhr.send(formData);
            });
        }

        const editDestinationForm = document.getElementById('edit-destination-form');
        if (editDestinationForm) {
            editDestinationForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get the destination ID from the form field
                const destId = document.getElementById('edit-destid').value;

                // Verify that we have a destination ID before proceeding
                if (!destId) {
                    alert('Error: No destination ID found.');
                    return;
                }

                // Create FormData object from the form
                const formData = new FormData(this);

                // Debug - log the form data being sent
                console.log("Destination ID being sent:", formData.get('destid'));
                console.log("Continent being sent:", formData.get('continent'));
                console.log("Country being sent:", formData.get('country'));

                // Create AJAX request
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Actions/updateDestination.php', true);

                xhr.onload = function() {
                    console.log("Response received:", this.responseText);

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
                                        toastMessage.textContent = "Destination updated successfully!";
                                    }

                                    toast.classList.add('show');
                                    setTimeout(() => {
                                        toast.classList.remove('show');
                                    }, 3000);
                                }

                                // Close modal
                                const modal = document.getElementById('edit-destination-modal');
                                closeModal(modal);

                                // Reload the page to refresh the destinations list
                                location.reload();
                            } else {
                                alert('Error updating destination: ' + response.message);
                            }
                        } catch (e) {
                            console.error("Error parsing JSON:", e);
/*
                            alert('Invalid response from server. Please try again.');
*/
                        }
                    } else {
                        alert('Error updating destination. Status: ' + this.status);
                    }
                };

                xhr.onerror = function() {
                    alert('Request error. Please try again.');
                };

                // Send form data
                xhr.send(formData);
            });
        }

        // Pagination functionality
        const rowsPerPage = 4; // Show 4 rows per page
        let currentPage = 1;

        // Initialize pagination
        function initPagination() {
            const table = document.getElementById('destinations-table');
            const rows = table.querySelectorAll('tbody tr.destination-row');
            const paginationContainer = document.getElementById('destinations-pagination');

            if (rows.length === 0) return;

            // Calculate total pages
            const totalPages = Math.ceil(rows.length / rowsPerPage);

            // Clear any existing pagination
            paginationContainer.innerHTML = '';

            // Add previous button
            if (totalPages > 1) {
                const prevBtn = document.createElement('button');
                prevBtn.className = 'page-btn prev';
                prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
                prevBtn.addEventListener('click', () => {
                    if (currentPage > 1) {
                        goToPage(currentPage - 1);
                    }
                });
                paginationContainer.appendChild(prevBtn);
            }

            // Add page buttons
            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = 'page-btn' + (i === currentPage ? ' active' : '');
                pageBtn.textContent = i;
                pageBtn.addEventListener('click', () => goToPage(i));
                paginationContainer.appendChild(pageBtn);
            }

            // Add next button
            if (totalPages > 1) {
                const nextBtn = document.createElement('button');
                nextBtn.className = 'page-btn next';
                nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
                nextBtn.addEventListener('click', () => {
                    if (currentPage < totalPages) {
                        goToPage(currentPage + 1);
                    }
                });
                paginationContainer.appendChild(nextBtn);
            }

            // Show the current page
            goToPage(currentPage);
        }

        // Function to display a specific page
        function goToPage(page) {
            currentPage = page;

            const table = document.getElementById('destinations-table');
            const rows = table.querySelectorAll('tbody tr.destination-row');
            const pageButtons = document.querySelectorAll('.page-btn:not(.prev):not(.next)');

            // Update active button
            pageButtons.forEach(btn => {
                btn.classList.remove('active');
                if (parseInt(btn.textContent) === page) {
                    btn.classList.add('active');
                }
            });

            // Show/hide rows based on current page
            rows.forEach((row, index) => {
                if (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) {
                    row.classList.remove('hidden-page');
                } else {
                    row.classList.add('hidden-page');
                }
            });
        }

        // Search and Filter functionality
        const searchInput = document.getElementById('destination-search');
        const continentFilter = document.getElementById('continent-filter');
        const table = document.getElementById('destinations-table');
        const rows = table.querySelectorAll('tbody tr.destination-row');

        // Search function
        function searchTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const filterValue = continentFilter.value.toLowerCase();

            // Check if we have any rows in the table
            if(rows.length === 0) return;

            let noResultsFound = true;
            let visibleRows = [];

            rows.forEach(row => {
                const continent = row.getAttribute('data-continent') || '';
                const destId = row.cells[0].textContent.toLowerCase();
                const country = row.cells[2].textContent.toLowerCase();
                const city = row.cells[3].textContent.toLowerCase();
                const description = row.cells[4].textContent.toLowerCase();

                // Check search term
                const matchesSearch = destId.includes(searchTerm) ||
                    continent.includes(searchTerm) ||
                    country.includes(searchTerm) ||
                    city.includes(searchTerm) ||
                    description.includes(searchTerm);

                // Check filter
                const matchesFilter = !filterValue || continent === filterValue;

                // Show or hide the row
                if (matchesSearch && matchesFilter) {
                    row.classList.remove('hidden');
                    visibleRows.push(row);
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
                    cell.colSpan = 7;
                    cell.className = 'text-center';
                    cell.textContent = 'No destinations match your search criteria';
                    noResultsRow.appendChild(cell);
                    table.querySelector('tbody').appendChild(noResultsRow);
                }

                // Hide pagination when no results
                document.getElementById('destinations-pagination').style.display = 'none';
            } else {
                if (noResultsRow) {
                    noResultsRow.remove();
                }

                // Show pagination when there are results
                document.getElementById('destinations-pagination').style.display = 'flex';

                // Reset to first page and reinitialize pagination with visible rows only
                currentPage = 1;
                initPagination();
            }
        }

        // Add event listeners for search and filter
        if (searchInput) {
            searchInput.addEventListener('input', searchTable);
        }

        if (continentFilter) {
            continentFilter.addEventListener('change', searchTable);
        }

        // Initialize pagination when the page loads
        initPagination();
    });
</script>