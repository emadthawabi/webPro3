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
        <input type="text" class="search-input" placeholder="Search destinations..." data-table-search="destinations-table">
    </div>

    <select class="filter-select" data-table-filter="destinations-table">
        <option value="">All Continents</option>
        <option value="europe">Europe</option>
        <option value="asia">Asia</option>
        <option value="africa">Africa</option>
        <option value="north-america">North America</option>
        <option value="south-america">South America</option>
        <option value="australia">Australia & Oceania</option>
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
                    echo '<tr data-category="' . htmlspecialchars($row['continent']) . '">';
                    echo '<td>' . htmlspecialchars($row['destid']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['continent']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['country']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['city']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['description']) . '</td>';
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
                echo '<tr><td colspan="6" class="text-center">No destinations found</td></tr>';
            }

            $db->close();
        } catch (Exception $e) {
            echo '<tr><td colspan="6" class="text-center">Error loading destinations: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
        }
        ?>
        </tbody>
    </table>
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
            <form id="add-destination-form" method="POST">
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
            <form id="edit-destination-form" method="POST">
                <input type="hidden" id="edit-dest-id" name="destid" value="">
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
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
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

        // Handle edit form submissions
        const editDestinationForm = document.getElementById('edit-destination-form');
        if (editDestinationForm) {
            editDestinationForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form data
                const formData = new FormData(this);

                // Create AJAX request
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Actions/updateDestination.php', true);

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
                        const modal = document.getElementById('edit-destination-modal');
                        closeModal(modal);

                        // Reload the page to refresh the destinations list
                        location.reload();
                    } else {
                        alert('Error updating destination: ' + this.responseText);
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