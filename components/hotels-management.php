<div class="admin-header">
    <h2><i class="fas fa-hotel"></i> Hotels Management</h2>
    <div class="admin-actions">
        <button class="btn btn-primary" data-modal-target="add-hotel-modal">
            <i class="fas fa-plus"></i> Add New Hotel
        </button>
    </div>
</div>

<div class="search-filter">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="search-input" placeholder="Search hotels..." data-table-search="hotels-table">
    </div>

    <select class="filter-select" data-table-filter="hotels-table">
        <option value="">All Star Ratings</option>
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
            <th>Destination ID</th>
            <th>Price</th>
            <th>Stars</th>
            <th>Time</th>
            <th># of People</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr data-category="4">
            <td>HTL001</td>
            <td>DEST001</td>
            <td>$189</td>
            <td>4</td>
            <td>14:00</td>
            <td>2</td>
            <td>
                <div class="action-btns">
                    <button class="action-btn edit" title="Edit Hotel" data-modal-target="edit-hotel-modal">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete delete-btn" title="Delete Hotel">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </td>
        </tr>
        <tr data-category="5">
            <td>HTL002</td>
            <td>DEST001</td>
            <td>$350</td>
            <td>5</td>
            <td>15:00</td>
            <td>2</td>
            <td>
                <div class="action-btns">
                    <button class="action-btn edit" title="Edit Hotel" data-modal-target="edit-hotel-modal">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete delete-btn" title="Delete Hotel">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </td>
        </tr>
        <tr data-category="3">
            <td>HTL003</td>
            <td>DEST001</td>
            <td>$95</td>
            <td>3</td>
            <td>12:00</td>
            <td>2</td>
            <td>
                <div class="action-btns">
                    <button class="action-btn edit" title="Edit Hotel" data-modal-target="edit-hotel-modal">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete delete-btn" title="Delete Hotel">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </td>
        </tr>
        <tr data-category="4">
            <td>HTL004</td>
            <td>DEST002</td>
            <td>$210</td>
            <td>4</td>
            <td>14:00</td>
            <td>4</td>
            <td>
                <div class="action-btns">
                    <button class="action-btn edit" title="Edit Hotel" data-modal-target="edit-hotel-modal">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete delete-btn" title="Delete Hotel">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </td>
        </tr>
        <tr data-category="5">
            <td>HTL005</td>
            <td>DEST003</td>
            <td>$420</td>
            <td>5</td>
            <td>15:00</td>
            <td>2</td>
            <td>
                <div class="action-btns">
                    <button class="action-btn edit" title="Edit Hotel" data-modal-target="edit-hotel-modal">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete delete-btn" title="Delete Hotel">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </td>
        </tr>
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

<!-- Add Hotel Modal -->
<div class="modal-overlay" id="add-hotel-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add New Hotel</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="add-hotel-form" data-action="added" data-table="hotels-table">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="hotel-id">Hotel ID</label>
                        <input type="text" id="hotel-id" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="dest-id">Destination ID</label>
                        <input type="text" id="dest-id" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Price per Night</label>
                        <input type="number" id="price" class="form-control" min="0" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="stars">Stars</label>
                        <select id="stars" class="form-control" required>
                            <option value="">Select Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="5">5 Stars</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="check-in-time">Check-in Time</label>
                        <input type="time" id="check-in-time" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="num-people">Number of People</label>
                        <input type="number" id="num-people" class="form-control" min="1" required>
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
            <form id="edit-hotel-form" data-action="updated">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-hotel-id">Hotel ID</label>
                        <input type="text" id="edit-hotel-id" class="form-control" value="HTL001" readonly>
                    </div>

                    <div class="form-group">
                        <label for="edit-dest-id">Destination ID</label>
                        <input type="text" id="edit-dest-id" class="form-control" value="DEST001" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-price">Price per Night</label>
                        <input type="number" id="edit-price" class="form-control" value="189" min="0" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-stars">Stars</label>
                        <select id="edit-stars" class="form-control" required>
                            <option value="">Select Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="4" selected>4 Stars</option>
                            <option value="5">5 Stars</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit-check-in-time">Check-in Time</label>
                        <input type="time" id="edit-check-in-time" class="form-control" value="14:00" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-num-people">Number of People</label>
                        <input type="number" id="edit-num-people" class="form-control" value="2" min="1" required>
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