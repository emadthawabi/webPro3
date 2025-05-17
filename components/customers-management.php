<?php
// Connect to database
$db = null;
try {
    $db = new mysqli("localhost", "root", "", "pathfinder", 3306);
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>

<div class="admin-header">
    <h2><i class="fas fa-users"></i> Customers Management</h2>
    <div class="admin-actions">
        <button class="btn btn-primary refresh-btn" id="refresh-customers">
            <i class="fas fa-sync-alt"></i> Refresh List
        </button>
    </div>
</div>

<div class="search-filter">
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="search-input" id="customer-search" placeholder="Search customers..." data-table-search="customers-table">
    </div>

    <select class="filter-select" id="gender-filter" data-table-filter="customers-table">
        <option value="">All Genders</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
    </select>
</div>

<div class="data-table-wrapper">
    <table class="data-table" id="customers-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Birth Date</th>
            <th>SSN</th>
            <th>Visa Number</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Query to get all customers
        if ($db) {
            $query = "SELECT * FROM customer ORDER BY customerid ASC";
            $result = $db->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr data-gender="' . htmlspecialchars($row['gender']) . '">';
                    echo '<td>' . htmlspecialchars($row['customerid']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['gender']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['bdate']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['ssn']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['visanum']) . '</td>';
                    echo '<td>
                        <div class="action-btns">
                            <button class="action-btn view" title="View Customer Details" data-modal-target="view-customer-modal" data-customer-id="' . htmlspecialchars($row['customerid']) . '">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn delete delete-customer-btn" title="Delete Customer" data-customer-id="' . htmlspecialchars($row['customerid']) . '">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="8" class="text-center">No customers found</td></tr>';
            }

            $db->close();
        } else {
            echo '<tr><td colspan="8" class="text-center">Database connection failed</td></tr>';
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

<!-- View Customer Modal -->
<div class="modal-overlay" id="view-customer-modal">
    <div class="modal">
        <div class="modal-header">
            <h3>Customer Details</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="customer-details">
                <div class="detail-group">
                    <label>Customer ID:</label>
                    <span id="view-customer-id"></span>
                </div>
                <div class="detail-group">
                    <label>Username:</label>
                    <span id="view-customer-username"></span>
                </div>
                <div class="detail-group">
                    <label>Email:</label>
                    <span id="view-customer-email"></span>
                </div>
                <div class="detail-group">
                    <label>Gender:</label>
                    <span id="view-customer-gender"></span>
                </div>
                <div class="detail-group">
                    <label>Birth Date:</label>
                    <span id="view-customer-bdate"></span>
                </div>
                <div class="detail-group">
                    <label>SSN:</label>
                    <span id="view-customer-ssn"></span>
                </div>
                <div class="detail-group">
                    <label>Visa Number:</label>
                    <span id="view-customer-visa"></span>
                </div>
                <div class="detail-group">
                    <label>Tour ID:</label>
                    <span id="view-customer-tour"></span>
                </div>
                <div class="detail-group">
                    <label>Hotel ID:</label>
                    <span id="view-customer-hotel"></span>
                </div>
                <div class="detail-group">
                    <label>Flight ID:</label>
                    <span id="view-customer-flight"></span>
                </div>
                <div class="detail-group">
                    <label>Destination ID:</label>
                    <span id="view-customer-dest"></span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modal-cancel">Close</button>
                <button type="button" class="btn btn-danger delete-customer-from-modal">Delete Customer</button>
            </div>
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
    /* Admin Header Styling */
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .refresh-btn {
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

    .refresh-btn:hover {
        background-color: #309b78;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .refresh-btn i {
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

    /* Customer Details Modal */
    .customer-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .detail-group {
        display: flex;
        flex-direction: column;
    }

    .detail-group label {
        font-weight: 600;
        margin-bottom: 5px;
        color: #555;
    }

    .detail-group span {
        padding: 8px;
        background-color: #f9f9f9;
        border-radius: 4px;
        border: 1px solid #ddd;
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

    .action-btn.view {
        background-color: #4CAF50;
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
        margin-top: 20px;
        gap: 5px;
    }

    .page-btn {
        padding: 8px 15px;
        border: 1px solid #ddd;
        background-color: white;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .page-btn.active {
        background-color: #3dbb91;
        color: white;
        border-color: #3dbb91;
    }

    .page-btn:hover:not(.active) {
        background-color: #f5f5f5;
    }

    /* Responsive table */
    @media screen and (max-width: 768px) {
        .customer-details {
            grid-template-columns: 1fr;
        }

        .data-table-wrapper {
            overflow-x: auto;
        }

        .data-table th,
        .data-table td {
            padding: 10px 8px;
        }

        .search-filter {
            flex-direction: column;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get all view buttons
        const viewButtons = document.querySelectorAll('.action-btn.view');

        // Add click event to view buttons
        viewButtons.forEach(button => {
            button.addEventListener('click', () => {
                const customerId = button.getAttribute('data-customer-id');
                const modal = document.getElementById('view-customer-modal');

                // Fetch customer details
                fetchCustomerDetails(customerId);

                // Open modal
                openModal(modal);
            });
        });

        // Get all delete buttons
        const deleteButtons = document.querySelectorAll('.delete-customer-btn');

        // Add click event to delete buttons
        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                const customerId = button.getAttribute('data-customer-id');
                confirmDeleteCustomer(customerId);
            });
        });

        // Delete from modal button
        const deleteFromModalButton = document.querySelector('.delete-customer-from-modal');
        if (deleteFromModalButton) {
            deleteFromModalButton.addEventListener('click', () => {
                const customerId = document.getElementById('view-customer-id').textContent;
                confirmDeleteCustomer(customerId);
            });
        }

        // Refresh customers button
        const refreshButton = document.getElementById('refresh-customers');
        if (refreshButton) {
            refreshButton.addEventListener('click', () => {
                location.reload();
            });
        }

        // Search and Filter functionality
        const searchInput = document.getElementById('customer-search');
        const genderFilter = document.getElementById('gender-filter');
        const table = document.getElementById('customers-table');
        const rows = table.querySelectorAll('tbody tr');

        // Search function
        function searchTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const filterValue = genderFilter.value.toLowerCase();

            // Check if we have any rows in the table
            if(rows.length === 0) return;

            let noResultsFound = true;

            rows.forEach(row => {
                const gender = row.getAttribute('data-gender') || '';
                const id = row.cells[0].textContent.toLowerCase();
                const username = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                const birthDate = row.cells[4].textContent.toLowerCase();
                const ssn = row.cells[5].textContent.toLowerCase();
                const visaNum = row.cells[6].textContent.toLowerCase();

                // Check search term
                const matchesSearch = id.includes(searchTerm) ||
                    username.includes(searchTerm) ||
                    email.includes(searchTerm) ||
                    gender.includes(searchTerm) ||
                    birthDate.includes(searchTerm) ||
                    ssn.includes(searchTerm) ||
                    visaNum.includes(searchTerm);

                // Check filter
                const matchesFilter = !filterValue || gender === filterValue;

                // Show or hide the row
                if (matchesSearch && matchesFilter) {
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
                    cell.colSpan = 8;
                    cell.className = 'text-center';
                    cell.textContent = 'No customers match your search criteria';
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

        if (genderFilter) {
            genderFilter.addEventListener('change', searchTable);
        }

        // Function to fetch customer details
        function fetchCustomerDetails(customerId) {
            // Create AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'Actions/getCustomerDetails.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (this.status === 200) {
                    try {
                        const customer = JSON.parse(this.responseText);

                        // Update modal with customer details
                        document.getElementById('view-customer-id').textContent = customer.customerid;
                        document.getElementById('view-customer-username').textContent = customer.username;
                        document.getElementById('view-customer-email').textContent = customer.email;
                        document.getElementById('view-customer-gender').textContent = customer.gender;
                        document.getElementById('view-customer-bdate').textContent = customer.bdate;
                        document.getElementById('view-customer-ssn').textContent = customer.ssn;
                        document.getElementById('view-customer-visa').textContent = customer.visanum;
                        document.getElementById('view-customer-tour').textContent = customer.tourid || 'None';
                        document.getElementById('view-customer-hotel').textContent = customer.hotelid || 'None';
                        document.getElementById('view-customer-flight').textContent = customer.flightid || 'None';
                        document.getElementById('view-customer-dest').textContent = customer.destid || 'None';
                    } catch (e) {
                        alert('Error parsing customer data');
                    }
                } else {
                    alert('Error fetching customer details');
                }
            };

            xhr.onerror = function() {
                alert('Request error. Please try again.');
            };

            // Send the customer ID to the server
            xhr.send('customerid=' + encodeURIComponent(customerId));
        }

        // Function to confirm and delete customer
        function confirmDeleteCustomer(customerId) {
            if (confirm('Are you sure you want to delete this customer?')) {
                // Create AJAX request for deletion
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Actions/deleteCustomer.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function() {
                    if (this.status === 200) {
                        // Show success notification
                        showNotification('Customer deleted successfully');

                        // Close modal if open
                        const modal = document.getElementById('view-customer-modal');
                        if (modal && modal.style.display === 'flex') {
                            closeModal(modal);
                        }

                        // Remove the row from the table
                        const rows = document.querySelectorAll('#customers-table tbody tr');
                        rows.forEach(row => {
                            const cells = row.querySelectorAll('td');
                            if (cells.length > 0 && cells[0].textContent === customerId) {
                                row.remove();
                            }
                        });
                    } else {
                        alert('Error deleting customer: ' + this.responseText);
                    }
                };

                xhr.onerror = function() {
                    alert('Request error. Please try again.');
                };

                // Send the customer ID to the server
                xhr.send('customerid=' + encodeURIComponent(customerId));
            }
        }

        // Function to show notification
        function showNotification(message) {
            const toast = document.getElementById('notification-toast');
            if (toast) {
                const toastMessage = toast.querySelector('.toast-message');
                if (toastMessage) {
                    toastMessage.textContent = message;
                }
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }
        }

        // Function to open modal
        function openModal(modal) {
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        // Function to close modal
        function closeModal(modal) {
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
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
    });
</script>