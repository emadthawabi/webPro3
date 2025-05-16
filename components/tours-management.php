<div class="admin-header">
    <h2><i class="fas fa-hiking"></i> Tours Management</h2>
    <div class="admin-actions">
        <button class="btn btn-primary" data-modal-target="add-tour-modal">
            <i class="fas fa-plus"></i> Add New Tour
        </button>
    </div>
</div>

<!-- Notification Messages -->
<?php if (isset($_SESSION['tour_add_success'])): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['tour_add_success']; unset($_SESSION['tour_add_success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['tour_add_error'])): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['tour_add_error']; unset($_SESSION['tour_add_error']); ?>
    </div>
<?php endif; ?>

<div class="search-filter">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="search-input" placeholder="Search tours..." data-table-search="tours-table">
    </div>
</div>

<div class="data-table-wrapper">
    <table class="data-table" id="tours-table">
        <thead>
        <tr>
            <th>Tour ID</th>
            <th>Tour Name</th>
            <th>Destination</th>
            <th>Price</th>
            <th>Rating</th>
            <th>Duration</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Connect to database
        try {
            $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

            // Query to get all tours with destination information
            $query = "SELECT t.*, d.country, d.city 
                          FROM tours t 
                          LEFT JOIN destination d ON t.destid = d.destid 
                          ORDER BY t.tourid DESC";

            $result = $db->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['tourid']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['tourname']) . '</td>';
                    echo '<td>' . (isset($row['city']) ? htmlspecialchars($row['city'] . ', ' . $row['country']) : 'N/A') . '</td>';
                    echo '<td>' . htmlspecialchars($row['price']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['rating']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['duration']) . ' days</td>';
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
                echo '<tr><td colspan="7" class="text-center">No tours found</td></tr>';
            }

            $db->close();
        } catch (Exception $e) {
            echo '<tr><td colspan="7" class="text-center">Error loading tours: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
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

<!-- Add Tour Modal -->
<div class="modal-overlay" id="add-tour-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add New Tour</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="add-tour-form" method="post" action="../Actions/addTour.php">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="tourname">Tour Name</label>
                        <input type="text" id="tourname" name="tourname" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="destid">Destination ID</label>
                        <input type="number" id="destid" name="destid" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="flightid">Flight ID</label>
                        <input type="number" id="flightid" name="flightid" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="hotelid">Hotel ID</label>
                        <input type="number" id="hotelid" name="hotelid" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" id="price" name="price" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="rating">Rating</label>
                        <input type="number" id="rating" name="rating" class="form-control" min="0" max="5" step="0.1" required>
                    </div>

                    <div class="form-group">
                        <label for="duration">Duration (days)</label>
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
