<div class="admin-header">
    <h2><i class="fas fa-map-marker-alt"></i> Destinations Management</h2>
    <div class="admin-actions">
        <button class="btn btn-primary" data-modal-target="add-destination-modal">
            <i class="fas fa-plus"></i> Add New Destination
        </button>
    </div>
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

<!-- Add Destination Modal -->
<div class="modal-overlay" id="add-destination-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add New Destination</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="add-destination-form" method="POST" action="../Actions/addDestination.php">
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

<!-- Add Destination Modal -->

        <div class="modal-body">
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

<!-- Edit Destination Modal -->
<div class="modal-overlay" id="edit-destination-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit Destination</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="edit-destination-form" method="POST" >
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
