<div class="admin-header">
    <h2><i class="fas fa-plane"></i> Flights Management</h2>
    <div class="admin-actions">
        <button class="btn btn-primary" data-modal-target="add-flight-modal">
            <i class="fas fa-plus"></i> Add New Flight
        </button>
    </div>
</div>

<!-- Notification Messages -->
<?php if(isset($_SESSION['flight_add_success'])): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['flight_add_success']; unset($_SESSION['flight_add_success']); ?>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['flight_add_error'])): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['flight_add_error']; unset($_SESSION['flight_add_error']); ?>
    </div>
<?php endif; ?>

<div class="search-filter">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="search-input" placeholder="Search flights..." data-table-search="flights-table">
    </div>

    <select class="filter-select" data-table-filter="flights-table">
        <option value="">All Flight Types</option>
        <option value="Economy">Economy Class</option>
        <option value="Business">Business Class</option>
        <option value="First">First Class</option>
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
        // Connect to database
        try {
            $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

            // Query to get all flights with destination information
            $query = "SELECT f.*, d.country, d.city 
                      FROM flights f 
                      LEFT JOIN destination d ON f.destid = d.destid 
                      ORDER BY f.flightid DESC";

            $result = $db->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr data-category="'.htmlspecialchars($row['type']).'">';
                    echo '<td>'.htmlspecialchars($row['flightid']).'</td>';
                    echo '<td>'.htmlspecialchars($row['airport']).'</td>';
                    echo '<td>'.htmlspecialchars($row['begin']).'</td>';
                    echo '<td>'.(isset($row['city']) ? htmlspecialchars($row['city'].', '.$row['country']) : 'N/A').'</td>';
                    echo '<td>'.htmlspecialchars($row['price']).'</td>';
                    echo '<td>'.htmlspecialchars($row['type']).'</td>';
                    echo '<td>'.htmlspecialchars($row['date']).'</td>';
                    echo '<td>'.htmlspecialchars($row['time']).'</td>';
                    echo '<td>
                            <div class="action-btns">
                                <button class="action-btn edit" title="Edit Flight" data-modal-target="edit-flight-modal" data-flight-id="'.htmlspecialchars($row['flightid']).'">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete delete-btn" title="Delete Flight" data-flight-id="'.htmlspecialchars($row['flightid']).'">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                          </td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="9" class="text-center">No flights found</td></tr>';
            }

            $db->close();
        } catch (Exception $e) {
            echo '<tr><td colspan="9" class="text-center">Error loading flights: '.htmlspecialchars($e->getMessage()).'</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>

<div class="pagination">
    <button class="page-btn prev"><i class="fas fa-chevron-left"></i> Previous</button>
    <button class="page-btn active">1</button>
    <button class="page-btn">2</button>
    <button class="page-btn">3</button>
    <button class="page-btn next">Next <i class="fas fa-chevron-right"></i></button>
</div>

<!-- Add Flight Modal -->
<div class="modal-overlay" id="add-flight-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add New Flight</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="add-flight-form" method="post" action="../Actions/addFlight.php">
                <!-- Add a hidden input to store the current page URL -->
                <input type="hidden" name="referer_page" value="<?php echo basename($_SERVER['PHP_SELF']); ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="airport">Airport</label>
                        <input type="text" id="airport" name="airport" class="form-control"
                               value="<?php echo isset($_SESSION['form_data']['airport']) ? htmlspecialchars($_SESSION['form_data']['airport']) : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="time">Time</label>
                        <input type="time" id="time" name="time" class="form-control"
                               value="<?php echo isset($_SESSION['form_data']['time']) ? htmlspecialchars($_SESSION['form_data']['time']) : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="begin">Origin</label>
                        <input type="text" id="begin" name="begin" class="form-control"
                               value="<?php echo isset($_SESSION['form_data']['begin']) ? htmlspecialchars($_SESSION['form_data']['begin']) : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="destid">Destination</label>
                        <select id="destid" name="destid" class="form-control" required>
                            <option value="">Select Destination</option>
                            <?php
                            try {
                                $db = new mysqli("localhost", "root", "", "pathfinder", 3306);
                                $query = "SELECT destid, country, city FROM destination ORDER BY country, city";
                                $result = $db->query($query);

                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = (isset($_SESSION['form_data']['destid']) && $_SESSION['form_data']['destid'] == $row['destid']) ? 'selected' : '';
                                        echo '<option value="'.htmlspecialchars($row['destid']).'" '.$selected.'>'.htmlspecialchars($row['city'].', '.$row['country']).'</option>';
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
                        <label for="price">Price ($)</label>
                        <input type="text" id="price" name="price" class="form-control"
                               value="<?php echo isset($_SESSION['form_data']['price']) ? htmlspecialchars($_SESSION['form_data']['price']) : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="type">Flight Type</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="">Select Flight Type</option>
                            <option value="Economy" <?php echo (isset($_SESSION['form_data']['type']) && $_SESSION['form_data']['type'] == 'Economy') ? 'selected' : ''; ?>>Economy Class</option>
                            <option value="Business" <?php echo (isset($_SESSION['form_data']['type']) && $_SESSION['form_data']['type'] == 'Business') ? 'selected' : ''; ?>>Business Class</option>
                            <option value="First" <?php echo (isset($_SESSION['form_data']['type']) && $_SESSION['form_data']['type'] == 'First') ? 'selected' : ''; ?>>First Class</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date">Flight Date</label>
                        <input type="date" id="date" name="date" class="form-control"
                               value="<?php echo isset($_SESSION['form_data']['date']) ? htmlspecialchars($_SESSION['form_data']['date']) : ''; ?>" required>
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
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="edit-flight-form" method="post" action="../Actions/updateFlight.php">
                <input type="hidden" name="referer_page" value="<?php echo basename($_SERVER['PHP_SELF']); ?>">
                <input type="hidden" id="edit-flightid" name="flightid" value="">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-airport">Airport</label>
                        <input type="text" id="edit-airport" name="airport" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-time">Time</label>
                        <input type="time" id="edit-time" name="time" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-begin">Origin</label>
                        <input type="text" id="edit-begin" name="begin" class="form-control" required>
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
                                        echo '<option value="'.htmlspecialchars($row['destid']).'">'.htmlspecialchars($row['city'].', '.$row['country']).'</option>';
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
                        <label for="edit-price">Price ($)</label>
                        <input type="text" id="edit-price" name="price" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-type">Flight Type</label>
                        <select id="edit-type" name="type" class="form-control" required>
                            <option value="">Select Flight Type</option>
                            <option value="Economy">Economy Class</option>
                            <option value="Business">Business Class</option>
                            <option value="First">First Class</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit-date">Flight Date</label>
                        <input type="date" id="edit-date" name="date" class="form-control" required>
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

<!-- Delete Flight Confirmation Modal -->
<div class="modal-overlay" id="delete-flight-modal">
    <div class="modal small-modal">
        <div class="modal-header">
            <h3>Confirm Deletion</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this flight? This action cannot be undone.</p>

            <form id="delete-flight-form" method="post" action="../Actions/deleteFlight.php">
                <input type="hidden" name="referer_page" value="<?php echo basename($_SERVER['PHP_SELF']); ?>">
                <input type="hidden" id="delete-flightid" name="flightid" value="">

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-cancel">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Alert messages */
    .alert {
        padding: 15px;
        border-radius: var(--radius);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background-color: var(--success-light);
        color: var(--success-dark);
        border-left: 4px solid var(--success);
    }

    .alert-danger {
        background-color: var(--danger-light);
        color: var(--danger-dark);
        border-left: 4px solid var(--danger);
    }

    .alert i {
        font-size: 1.2rem;
    }

    /* Small modal for confirmations */
    .small-modal {
        max-width: 400px;
    }

    /* Fix for table styles */
    .data-table th, .data-table td {
        vertical-align: middle;
    }

    .data-table tbody tr td {
        padding: 12px 15px;
    }

    .text-center {
        text-align: center;
    }

    /* Make sure the modal is visible when opened */
    .modal-overlay {
        display: none;
    }
</style>

<?php
// Clear form data after displaying
if(isset($_SESSION['form_data'])) {
    unset($_SESSION['form_data']);
}
?>

<script>
    // Script to handle modal opening and closing
    document.addEventListener('DOMContentLoaded', function() {
        // Modal open buttons
        const modalButtons = document.querySelectorAll('[data-modal-target]');
        modalButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetModal = document.getElementById(this.getAttribute('data-modal-target'));
                if (targetModal) {
                    targetModal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }
            });
        });

        // Modal close buttons
        const closeButtons = document.querySelectorAll('.modal-close, .modal-cancel');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('.modal-overlay');
                if (modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        });

        // Close modal when clicking outside
        const modals = document.querySelectorAll('.modal-overlay');
        modals.forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        });

        // Edit flight button click handler
        const editButtons = document.querySelectorAll('.action-btn.edit');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const flightId = this.getAttribute('data-flight-id');
                document.getElementById('edit-flightid').value = flightId;

                // Fetch flight data from server and populate form fields
                // This would normally be done with an AJAX request
                // For the demo, we're omitting this part
            });
        });

        // Delete flight button click handler
        const deleteButtons = document.querySelectorAll('.action-btn.delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const flightId = this.getAttribute('data-flight-id');
                document.getElementById('delete-flightid').value = flightId;
                // Open delete confirmation modal
                const modal = document.getElementById('delete-flight-modal');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
        });
    });
</script>