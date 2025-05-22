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
    <title>Hotels Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="admin-header">
    <h2><i class="fas fa-hotel"></i> Hotels Management</h2>
    <div class="admin-actions">
        <button class="btn btn-primary add-hotel-btn" data-modal-target="add-hotel-modal">
            <i class="fas fa-plus-circle"></i> Add New Hotel
        </button>
    </div>
</div>

<div class="search-filter">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="search-input" id="hotel-search" placeholder="Search hotels..." data-table-search="hotels-table">
    </div>

    <select class="filter-select" id="stars-filter" data-table-filter="hotels-table">
        <option value="">All Star Ratings</option>
        <option value="1">1 Star</option>
        <option value="2">2 Stars</option>
        <option value="3">3 Stars</option>
        <option value="4">4 Stars</option>
        <option value="5">5 Stars</option>
    </select>
</div>

<div class="data-table-wrapper">
    <table class="data-table" id="hotels-table">
        <thead>
        <tr>
            <th>Hotel ID</th>
            <th>Hotel Name</th>
            <th>Destination</th>
            <th>Price</th>
            <th>Stars</th>
            <th>Time</th>
            <th># of People</th>
            <th>Location</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT h.*, CONCAT(d.country, ' - ', d.city) as destination_name 
                FROM hotels h 
                LEFT JOIN destination d ON h.destid = d.destid";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr data-stars='" . htmlspecialchars($row['stars']) . "' class='hotel-row' data-destid='" . htmlspecialchars($row['destid']) . "'>";
                echo "<td>" . htmlspecialchars($row['hotelid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['hotelname']) . "</td>";
                echo "<td data-destid='" . htmlspecialchars($row['destid']) . "'>" . htmlspecialchars($row['destination_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                echo "<td>" . htmlspecialchars($row['stars']) . "</td>";
                echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                echo "<td>" . htmlspecialchars($row['numofpeople']) . "</td>";
                echo "<td>" . htmlspecialchars($row['location']) . "</td>";

                echo "<td>
                            <div class='action-btns'>
                                <button class='action-btn edit' title='Edit Hotel' data-modal-target='edit-hotel-modal' data-hotel-id='" . htmlspecialchars($row['hotelid']) . "' data-hotel-image='" . htmlspecialchars($row['hotelimage']) . "'>
                                    <i class='fas fa-edit'></i>
                                </button>
                                <button class='action-btn delete delete-btn' title='Delete Hotel'>
                                    <i class='fas fa-trash-alt'></i>
                                </button>
                            </div>
                          </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No hotels found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Pagination Container -->
<div class="pagination" id="hotels-pagination">
    <!-- Pagination buttons will be added by JavaScript -->
</div>

<!-- Add Hotel Modal -->
<div class="modal-overlay" id="add-hotel-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add New Hotel</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="add-hotel-form" data-action="added" data-table="hotels-table" action="Actions/addHotel.php" method="POST" enctype="multipart/form-data">
                <div class="form-grid">

                    <div class="form-group">
                        <label for="hotel-name">Hotel Name</label>
                        <input type="text" id="hotel-name" name="hotelname" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="dest-id">Destination</label>
                        <select id="dest-id" name="destid" class="form-control" required>
                            <option value="">Select Destination</option>
                            <?php foreach ($destinations as $dest): ?>
                                <option value="<?php echo htmlspecialchars($dest['destid']); ?>">
                                    <?php echo htmlspecialchars($dest['country'] . ' - ' . $dest['city']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="price">Price per Night</label>
                        <input type="number" id="price" name="price" class="form-control" min="0" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="stars">Stars</label>
                        <select id="stars" name="stars" class="form-control" required>
                            <option value="">Select Stars</option>
                            <option value="1">1 Star</option>
                            <option value="2">2 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="5">5 Stars</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="check-in-time">Check-in Time</label>
                        <input type="time" id="check-in-time" name="time" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="num-people">Number of People</label>
                        <input type="number" id="num-people" name="numofpeople" class="form-control" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" class="form-control" required>
                    </div>

                    <!-- New Hotel Image Field -->
                    <div class="form-group">
                        <label for="hotel_image">Hotel Image</label>
                        <div class="file-input-wrapper">
                            <input type="file" id="hotel_image" name="hotel_image" class="form-control-file" accept="image/*">
                            <div class="file-input-preview">
                                <img id="hotel_image_preview" src="/images/placeholder-image.jpg" alt="Hotel Image Preview">
                                <span>Select Image</span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Hotel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Hotel Modal -->
<div class="modal-overlay" id="edit-hotel-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Hotel</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="edit-hotel-form" method="POST" action="Actions/updateHotel.php" enctype="multipart/form-data">
                <div class="form-grid">
                    <!-- Hidden hotel ID -->
                    <input type="hidden" id="edit-hotel-id" name="hotelid">
                    <!-- Hidden current image -->
                    <input type="hidden" id="edit-current-image" name="current_image">

                    <div class="form-group">
                        <label for="edit-hotel-name">Hotel Name</label>
                        <input type="text" id="edit-hotel-name" name="hotelname" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-dest-id">Destination</label>
                        <select id="edit-dest-id" name="destid" class="form-control" required>
                            <option value="">Select Destination</option>
                            <?php foreach ($destinations as $dest): ?>
                                <option value="<?php echo htmlspecialchars($dest['destid']); ?>">
                                    <?php echo htmlspecialchars($dest['country'] . ' - ' . $dest['city']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit-price">Price per Night</label>
                        <input type="number" id="edit-price" name="price" class="form-control" min="0" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-stars">Stars</label>
                        <select id="edit-stars" name="stars" class="form-control" required>
                            <option value="">Select Stars</option>
                            <option value="1">1 Star</option>
                            <option value="2">2 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="5">5 Stars</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit-check-in-time">Check-in Time</label>
                        <input type="time" id="edit-check-in-time" name="time" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-num-people">Number of People</label>
                        <input type="number" id="edit-num-people" name="numofpeople" class="form-control" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-location">Location</label>
                        <input type="text" id="edit-location" name="location" class="form-control" required>
                    </div>

                    <!-- New Hotel Image Field for Edit -->
                    <div class="form-group">
                        <label for="edit_hotel_image">Hotel Image</label>
                        <div class="file-input-wrapper">
                            <input type="file" id="edit_hotel_image" name="hotel_image" class="form-control-file" accept="image/*">
                            <div class="file-input-preview">
                                <img id="edit_hotel_image_preview" src="/images/placeholder-image.jpg" alt="Hotel Image Preview">
                                <span>Change Image</span>
                            </div>
                            <p class="form-text text-muted">Current image will be kept if no new image is selected</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Hotel</button>
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
    /* Admin Header Styling */
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .add-hotel-btn {
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

    .add-hotel-btn:hover {
        background-color: #3dbb91;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .add-hotel-btn i {
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
        max-width: 700px; /* Wider to accommodate the image upload */
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        animation: slideDown 0.4s ease;
        margin: 0 auto; /* Center horizontally */
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
    tr.hotel-row.hidden-page {
        display: none;
    }

    /* File Input Styling */
    .file-input-wrapper {
        position: relative;
        width: 100%;
    }

    .file-input-wrapper input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }

    .file-input-preview {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-input-preview:hover {
        border-color: #3dbb91;
    }

    .file-input-preview img {
        max-width: 100%;
        max-height: 150px;
        margin-bottom: 10px;
        border-radius: 4px;
        object-fit: cover;
    }

    .file-input-preview span {
        color: #3dbb91;
        font-weight: 500;
    }

    .form-text {
        font-size: 12px;
        margin-top: 5px;
    }

    /* Form Grid Layout */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 20px;
        padding: 20px;
    }

    /* Make certain fields span full width */
    .form-grid .form-group:nth-child(1),
    .form-grid .form-group:nth-child(8) {
        grid-column: span 2;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Pagination variables
        const rowsPerPage = 4; // Show 4 rows per page
        let currentPage = 1;

        // Initialize image preview functionality
        function initImagePreviews() {
            // For Add Hotel form
            const hotelImageInput = document.getElementById('hotel_image');
            const hotelImagePreview = document.getElementById('hotel_image_preview');

            if (hotelImageInput && hotelImagePreview) {
                hotelImageInput.addEventListener('change', function(e) {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            hotelImagePreview.src = e.target.result;
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }

            // For Edit Hotel form
            const editHotelImageInput = document.getElementById('edit_hotel_image');
            const editHotelImagePreview = document.getElementById('edit_hotel_image_preview');

            if (editHotelImageInput && editHotelImagePreview) {
                editHotelImageInput.addEventListener('change', function(e) {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            editHotelImagePreview.src = e.target.result;
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
        }

        // Initialize pagination
        function initPagination() {
            const table = document.getElementById('hotels-table');
            const rows = table.querySelectorAll('tbody tr.hotel-row:not(.hidden)');
            const paginationContainer = document.getElementById('hotels-pagination');

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

            const table = document.getElementById('hotels-table');
            const rows = table.querySelectorAll('tbody tr.hotel-row:not(.hidden)');
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
                if (!confirm("Are you sure you want to delete this hotel?")) return;

                const row = button.closest('tr');
                const hotelId = row.cells[0].innerText.trim(); // Hotel ID is in first <td>

                fetch('Actions/deleteHotel.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'hotelid=' + encodeURIComponent(hotelId)
                })
                    .then(response => response.text())
                    .then(result => {
                        if (result.trim() === "success") {
                            // Show success notification
                            const toast = document.getElementById('notification-toast');
                            if (toast) {
                                toast.querySelector('.toast-message').textContent = "Hotel deleted successfully!";
                                toast.classList.add('show');
                                setTimeout(() => {
                                    toast.classList.remove('show');
                                }, 3000);
                            }

                            row.remove();

                            // Reinitialize pagination after removing a row
                            initPagination();
                        } else {
                            alert("Failed to delete hotel.");
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
                const hotelId = cells[0].innerText.trim();
                const hotelName = cells[1].innerText.trim();

                // Get the actual destid from the data attribute (not the displayed text)
                const destId = cells[2].getAttribute('data-destid');

                // Fill the form
                document.getElementById('edit-hotel-id').value = hotelId;
                document.getElementById('edit-hotel-name').value = hotelName;

                // Get the hotel image from data attribute
                const hotelImage = btn.getAttribute('data-hotel-image') || '';
                document.getElementById('edit-current-image').value = hotelImage;

                // Update image preview if image exists
                if (hotelImage) {
                    const imagePreview = document.getElementById('edit_hotel_image_preview');
                    if (imagePreview) {
                        imagePreview.src = hotelImage ? '../uploadImages/' + hotelImage : '/images/placeholder-image.jpg';
                    }
                }

                // Set the destination dropdown value based on the data-destid attribute
                const destSelect = document.getElementById('edit-dest-id');
                for (let i = 0; i < destSelect.options.length; i++) {
                    if (destSelect.options[i].value === destId) {
                        destSelect.selectedIndex = i;
                        break;
                    }
                }

                document.getElementById('edit-price').value = cells[3].innerText.trim();
                document.getElementById('edit-stars').value = cells[4].innerText.trim();
                document.getElementById('edit-check-in-time').value = cells[5].innerText.trim();
                document.getElementById('edit-num-people').value = cells[6].innerText.trim();
                document.getElementById('edit-location').value = cells[7].innerText.trim();

                // Open modal
                openModal(document.getElementById('edit-hotel-modal'));

                // Save reference to row for later update
                document.getElementById('edit-hotel-form').dataset.targetRow = row.rowIndex;
            });
        });

        // Handle update submission with AJAX
        document.getElementById('edit-hotel-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get all form data
            const formData = new FormData(this);

            // Send AJAX request
            fetch('Actions/updateHotel.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(result => {
                    if (result.trim() === 'success' || result.trim() === 'no_change') {
                        // Get the correct row index
                        const index = parseInt(this.dataset.targetRow);
                        const table = document.getElementById('hotels-table');
                        const row = table.rows[index];

                        // Get the destination name for the selected destination ID
                        const destId = formData.get('destid');
                        const destSelect = document.getElementById('edit-dest-id');
                        let destName = '';

                        for (let i = 0; i < destSelect.options.length; i++) {
                            if (destSelect.options[i].value === destId) {
                                destName = destSelect.options[i].text;
                                break;
                            }
                        }

                        // Update all fields in the table display
                        row.cells[1].innerText = formData.get('hotelname');
                        // Update destination with both ID (as data attribute) and text (country - city)
                        row.cells[2].setAttribute('data-destid', destId);
                        row.cells[2].innerText = destName;
                        row.cells[3].innerText = formData.get('price');
                        row.cells[4].innerText = formData.get('stars');
                        // Update data-stars attribute for filtering
                        row.setAttribute('data-stars', formData.get('stars'));
                        row.cells[5].innerText = formData.get('time');
                        row.cells[6].innerText = formData.get('numofpeople');
                        row.cells[7].innerText = formData.get('location');

                        // Show success notification
                        const toast = document.getElementById('notification-toast');
                        if (toast) {
                            toast.querySelector('.toast-message').textContent = "Hotel updated successfully!";
                            toast.classList.add('show');
                            setTimeout(() => {
                                toast.classList.remove('show');
                            }, 3000);
                        }

                        closeModal(document.getElementById('edit-hotel-modal'));
                    } else {
                        alert("Update failed. Server response: " + result);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while updating the hotel.");
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

        // Handle form submissions via AJAX for adding new hotel
        const addHotelForm = document.getElementById('add-hotel-form');
        if (addHotelForm) {
            addHotelForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form data
                const formData = new FormData(this);

                // Create AJAX request
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Actions/addHotel.php', true);

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
                        const modal = document.getElementById('add-hotel-modal');
                        closeModal(modal);

                        // Reload the page to refresh the hotels list
                        location.reload();
                    } else {
                        alert('Error adding hotel: ' + this.responseText);
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
        const searchInput = document.getElementById('hotel-search');
        const starsFilter = document.getElementById('stars-filter');
        const table = document.getElementById('hotels-table');
        const rows = table.querySelectorAll('tbody tr.hotel-row');

        // Search function
        function searchTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const filterValue = starsFilter.value.toLowerCase();

            // Check if we have any rows in the table
            if(rows.length === 0) return;

            let noResultsFound = true;
            let visibleRows = [];

            rows.forEach(row => {
                const stars = row.getAttribute('data-stars') || '';
                const hotelId = row.cells[0].textContent.toLowerCase();
                const hotelName = row.cells[1].textContent.toLowerCase();
                const destName = row.cells[2].textContent.toLowerCase();
                const price = row.cells[3].textContent.toLowerCase();
                const time = row.cells[5].textContent.toLowerCase();
                const numPeople = row.cells[6].textContent.toLowerCase();
                const location = row.cells[7].textContent.toLowerCase();

                // Check search term
                const matchesSearch = hotelId.includes(searchTerm) ||
                    hotelName.includes(searchTerm) ||
                    destName.includes(searchTerm) ||
                    price.includes(searchTerm) ||
                    stars.includes(searchTerm) ||
                    time.includes(searchTerm) ||
                    numPeople.includes(searchTerm) ||
                    location.includes(searchTerm);

                // Check filter
                const matchesFilter = !filterValue || stars === filterValue;

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
                    cell.textContent = 'No hotels match your search criteria';
                    noResultsRow.appendChild(cell);
                    table.querySelector('tbody').appendChild(noResultsRow);
                }

                // Hide pagination when no results
                document.getElementById('hotels-pagination').style.display = 'none';
            } else {
                if (noResultsRow) {
                    noResultsRow.remove();
                }

                // Show pagination when there are results
                document.getElementById('hotels-pagination').style.display = 'flex';

                // Reset to first page and reinitialize pagination
                currentPage = 1;
                initPagination();
            }
        }

        // Add event listeners for search and filter
        if (searchInput) {
            searchInput.addEventListener('input', searchTable);
        }

        if (starsFilter) {
            starsFilter.addEventListener('change', searchTable);
        }

        // Initialize image previews
        initImagePreviews();

        // Initialize pagination when the page loads
        initPagination();
    });
</script>
</body>
</html>