<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pathfinder";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all destinations for the dropdowns
$destSql = "SELECT destid, country, city FROM destination ORDER BY country, city";
$destResult = $conn->query($destSql);
$destinations = array();
if ($destResult->num_rows > 0) {
    while($destRow = $destResult->fetch_assoc()) {
        $destinations[] = $destRow;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flights Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="admin-header">
    <h2><i class="fas fa-plane"></i> Flights Management</h2>
    <div class="admin-actions">
        <button class="btn btn-primary add-flight-btn" data-modal-target="add-flight-modal">
            <i class="fas fa-plus-circle"></i> Add New Flight
        </button>
    </div>
</div>

<div class="search-filter">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="search-input" id="flight-search" placeholder="Search flights..." data-table-search="flights-table">
    </div>
    <select class="filter-select" id="flight-type-filter" data-table-filter="flights-table">
        <option value="">All Flight Types</option>
        <option value="Economy class">Economy Class</option>
        <option value="Business class">Business Class</option>
        <option value="First class">First Class</option>
    </select>
</div>

<div class="data-table-wrapper">
    <table class="data-table" id="flights-table">
        <thead>
        <tr>
            <th>Flight ID</th>
            <th>Airport</th>
            <th>Origin</th>
            <th>Destination</th>
            <th>Price</th>
            <th>Type</th>
            <th>Date</th>
            <th>Time</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT f.*, CONCAT(d.city, ', ', d.country) as destination_name 
                FROM flights f 
                LEFT JOIN destination d ON f.destid = d.destid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr data-flight-type='" . htmlspecialchars(strtolower($row['type'])) . "' class='flight-row'>";
                echo "<td>" . htmlspecialchars($row['flightid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['airport']) . "</td>";
                echo "<td>" . htmlspecialchars($row['begin']) . "</td>";
                echo "<td>" . htmlspecialchars($row['destination_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['time']) . "</td>";

                echo "<td>
                        <div class='action-btns'>
                            <button class='action-btn edit' title='Edit Flight' data-modal-target='edit-flight-modal'>
                                <i class='fas fa-edit'></i>
                            </button>
                            <button class='action-btn delete delete-btn' title='Delete Flight'>
                                <i class='fas fa-trash-alt'></i>
                            </button>
                        </div>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No flights found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Pagination Container -->
<div class="pagination" id="flights-pagination">
    <!-- Pagination buttons will be added by JavaScript -->
</div>

<!-- Add Flight Modal -->
<div class="modal-overlay" id="add-flight-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add New Flight</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="add-flight-form" data-action="added" data-table="flights-table" action="/webPro/Actions/addFlight.php" method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="airport">Airport</label>
                        <input type="text" id="airport" name="airport" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="begin">Origin</label>
                        <input type="text" id="begin" name="begin" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="destid">Destination</label>
                        <select id="destid" name="destid" class="form-control" required>
                            <option value="">Select Destination</option>
                            <?php foreach ($destinations as $dest): ?>
                                <option value="<?php echo htmlspecialchars($dest['destid']); ?>">
                                    <?php echo htmlspecialchars($dest['city'] . ', ' . $dest['country']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="price">Price ($)</label>
                        <input type="text" id="price" name="price" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="type">Flight Type</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="">Select Flight Type</option>
                            <option value="Economy class">Economy Class</option>
                            <option value="Business class">Business Class</option>
                            <option value="First class">First Class</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date">Flight Date</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="time">Time</label>
                        <input type="time" id="time" name="time" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Flight</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Flight Modal -->
<div class="modal-overlay" id="edit-flight-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Flight</h3>
        </div>
        <div class="modal-body">
            <form id="edit-flight-form" method="POST">
                <div class="form-grid">
                    <!-- Hidden flight ID -->
                    <input type="hidden" id="edit-flight-id" name="flightid">

                    <div class="form-group">
                        <label for="edit-airport">Airport</label>
                        <input type="text" id="edit-airport" name="airport" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-begin">Origin</label>
                        <input type="text" id="edit-begin" name="begin" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-destid">Destination</label>
                        <select id="edit-destid" name="destid" class="form-control" required>
                            <option value="">Select Destination</option>
                            <?php foreach ($destinations as $dest): ?>
                                <option value="<?php echo htmlspecialchars($dest['destid']); ?>">
                                    <?php echo htmlspecialchars($dest['city'] . ', ' . $dest['country']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit-price">Price ($)</label>
                        <input type="text" id="edit-price" name="price" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-type">Flight Type</label>
                        <select id="edit-type" name="type" class="form-control" required>
                            <option value="">Select Flight Type</option>
                            <option value="Economy class">Economy Class</option>
                            <option value="Business class">Business Class</option>
                            <option value="First class">First Class</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit-date">Flight Date</label>
                        <input type="date" id="edit-date" name="date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-time">Time</label>
                        <input type="time" id="edit-time" name="time" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Flight</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
    .modal-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background-color: rgba(255, 255, 255, 0.2);
    }
    /* Admin Header Styling */
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .add-flight-btn {
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

    .add-flight-btn:hover {
        background-color: #309b78;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .add-flight-btn i {
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
        max-width: 600px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        animation: slideDown 0.4s ease;
        margin: 0 auto; /* Center horizontally */
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

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

    /* Action Button Styling */
    .action-btns {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .action-btn.edit {
        background-color: #2196F3;
        color: white;
    }

    .action-btn.delete {
        background-color: #f44336;
        color: white;
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Form Styling */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 5px;
        font-weight: 600;
        color: #555;
    }

    .form-control {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
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
    tr.flight-row.hidden-page {
        display: none;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .search-filter {
            flex-direction: column;
        }

        .data-table-wrapper {
            overflow-x: auto;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Pagination variables
        const rowsPerPage = 4; // Show 4 rows per page
        let currentPage = 1;

        // Initialize pagination
        function initPagination() {
            const table = document.getElementById('flights-table');
            const rows = table.querySelectorAll('tbody tr.flight-row:not(.hidden)');
            const paginationContainer = document.getElementById('flights-pagination');

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

            const table = document.getElementById('flights-table');
            const rows = table.querySelectorAll('tbody tr.flight-row:not(.hidden)');
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

        // Delete button event listeners
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (!confirm("Are you sure you want to delete this flight?")) return;

                const row = button.closest('tr');
                const flightId = row.cells[0].innerText.trim(); // Flight ID is in first <td>

                fetch('Actions/deleteFlight.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'flightid=' + encodeURIComponent(flightId)
                })
                    .then(response => response.text())
                    .then(result => {
                        if (result.trim() === "success") {
                            // Show success notification
                            const toast = document.getElementById('notification-toast');
                            if (toast) {
                                toast.querySelector('.toast-message').textContent = "Flight deleted successfully!";
                                toast.classList.add('show');
                                setTimeout(() => {
                                    toast.classList.remove('show');
                                }, 3000);
                            }

                            row.remove();

                            // Reinitialize pagination after removing a row
                            initPagination();
                        } else {
                            alert("Failed to delete flight.");
                        }
                    })
                    .catch(() => {
                        alert("An error occurred.");
                    });
            });
        });

        // Handle Edit button click
        const editButtons = document.querySelectorAll('.edit');
        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const row = btn.closest('tr');
                const cells = row.querySelectorAll('td');

                // Fill the form
                document.getElementById('edit-flight-id').value = cells[0].innerText.trim();
                document.getElementById('edit-airport').value = cells[1].innerText.trim();
                document.getElementById('edit-begin').value = cells[2].innerText.trim();

                // Set the destination dropdown value
                const destCityCountry = cells[3].innerText.trim();
                const destSelect = document.getElementById('edit-destid');
                for (let i = 0; i < destSelect.options.length; i++) {
                    if (destSelect.options[i].text === destCityCountry) {
                        destSelect.selectedIndex = i;
                        break;
                    }
                }

                document.getElementById('edit-price').value = cells[4].innerText.trim().replace('$', '');
                document.getElementById('edit-type').value = cells[5].innerText.trim();

                // Parse date value
                document.getElementById('edit-date').value = cells[6].innerText.trim();

                // Parse time value
                document.getElementById('edit-time').value = cells[7].innerText.trim();

                // Open modal
                openModal(document.getElementById('edit-flight-modal'));

                // Save reference to row for later update
                document.getElementById('edit-flight-form').dataset.targetRow = row.rowIndex;
            });
        });

        // Handle update submission
        document.getElementById('edit-flight-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get all form data
            const formData = new FormData(this);

            fetch('Actions/updateFlight.php', {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
                .then(response => response.text())
                .then(result => {
                    if (result.trim() === 'success') {
                        // Get the correct row index
                        const index = parseInt(this.dataset.targetRow);
                        const table = document.getElementById('flights-table');
                        const row = table.rows[index];

                        // Update all fields in the table display
                        row.cells[1].innerText = formData.get('airport');
                        row.cells[2].innerText = formData.get('begin');

                        // Update destination (would need to fetch the actual text)
                        // This is simplified - in production you'd want to show the actual destination name

                        row.cells[4].innerText = formData.get('price') + '$';
                        row.cells[5].innerText = formData.get('type');

                        // Update data-flight-type attribute for filtering
                        row.setAttribute('data-flight-type', formData.get('type').toLowerCase());

                        row.cells[6].innerText = formData.get('date');
                        row.cells[7].innerText = formData.get('time');

                        // Show success notification
                        const toast = document.getElementById('notification-toast');
                        if (toast) {
                            toast.querySelector('.toast-message').textContent = "Flight updated successfully!";
                            toast.classList.add('show');
                            setTimeout(() => {
                                toast.classList.remove('show');
                            }, 3000);
                        }

                        closeModal(document.getElementById('edit-flight-modal'));
                    } else {
                        alert("Update failed. Server response: " + result);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while updating the flight.");
                });
        });

        // Get all buttons that open modals
        const modalButtons = document.querySelectorAll('[data-modal-target]');

        // Add click event to buttons
        modalButtons.forEach(button => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.modalTarget);
                openModal(modal);
            });
        });

        // Close modal when clicking close button or cancel
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

        // Handle form submissions via AJAX for adding new flight
        const addFlightForm = document.getElementById('add-flight-form');
        if (addFlightForm) {
            addFlightForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form data
                const formData = new FormData(this);

                // Create AJAX request
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Actions/addFlight.php', true);

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
                        const modal = document.getElementById('add-flight-modal');
                        closeModal(modal);

                        // Reload the page to refresh the flights list
                        location.reload();
                    } else {
                        alert('Error adding flight: ' + this.responseText);
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
        const searchInput = document.getElementById('flight-search');
        const typeFilter = document.getElementById('flight-type-filter');
        const table = document.getElementById('flights-table');
        const rows = table.querySelectorAll('tbody tr.flight-row');

        // Search function
        function searchTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const filterValue = typeFilter.value.toLowerCase();

            // Check if we have any rows in the table
            if(rows.length === 0) return;

            let noResultsFound = true;
            let visibleRows = [];

            rows.forEach(row => {
                const flightType = row.getAttribute('data-flight-type') || '';
                const flightId = row.cells[0].textContent.toLowerCase();
                const airport = row.cells[1].textContent.toLowerCase();
                const origin = row.cells[2].textContent.toLowerCase();
                const destination = row.cells[3].textContent.toLowerCase();
                const price = row.cells[4].textContent.toLowerCase();
                const date = row.cells[6].textContent.toLowerCase();
                const time = row.cells[7].textContent.toLowerCase();

                // Check search term
                const matchesSearch = flightId.includes(searchTerm) ||
                    airport.includes(searchTerm) ||
                    origin.includes(searchTerm) ||
                    destination.includes(searchTerm) ||
                    price.includes(searchTerm) ||
                    flightType.includes(searchTerm) ||
                    date.includes(searchTerm) ||
                    time.includes(searchTerm);

                // Check filter
                const matchesFilter = !filterValue || flightType === filterValue;

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
                    cell.colSpan = 9;
                    cell.className = 'text-center';
                    cell.textContent = 'No flights match your search criteria';
                    noResultsRow.appendChild(cell);
                    table.querySelector('tbody').appendChild(noResultsRow);
                }

                // Hide pagination when no results
                document.getElementById('flights-pagination').style.display = 'none';
            } else {
                if (noResultsRow) {
                    noResultsRow.remove();
                }

                // Show pagination when there are results
                document.getElementById('flights-pagination').style.display = 'flex';

                // Reset to first page and reinitialize pagination
                currentPage = 1;
                initPagination();
            }
        }

        // Add event listeners for search and filter
        if (searchInput) {
            searchInput.addEventListener('input', searchTable);
        }

        if (typeFilter) {
            typeFilter.addEventListener('change', searchTable);
        }

        // Initialize pagination when the page loads
        initPagination();
    });
</script>
</body>
</html>