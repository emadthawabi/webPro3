/**
 * Admin Dashboard JavaScript
 * Handles all interactions for the admin dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS Animation
    AOS.init({duration: 800, once: true});

    // Tab Navigation
    initTabs();

    // Modal Functionality
    initModals();

    // Notification System
    initNotifications();

    // Form Validation
    initFormValidation();

    // Image Upload Functionality
    initImageUploads();

    // Data Table Functionality
    initDataTables();
});

/**
 * Initialize Tab Navigation
 */
function initTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabName = button.getAttribute('data-tab');

            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Add active class to selected button and content
            button.classList.add('active');
            document.getElementById(`${tabName}-content`).classList.add('active');
        });
    });
}

/**
 * Initialize Modal Functionality
 */
function initModals() {
    // Open modal buttons
    const openModalButtons = document.querySelectorAll('[data-modal-target]');

    openModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal-target');
            const modal = document.getElementById(modalId);

            if (modal) {
                openModal(modal);
            }
        });
    });

    // Close modal buttons
    const closeModalButtons = document.querySelectorAll('.modal-close, .modal-cancel');

    closeModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('.modal-overlay');
            closeModal(modal);
        });
    });

    // Close modal when clicking outside
    const modalOverlays = document.querySelectorAll('.modal-overlay');

    modalOverlays.forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                closeModal(overlay);
            }
        });
    });
}

/**
 * Open a modal
 * @param {HTMLElement} modal - The modal element to open
 */
function openModal(modal) {
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Close a modal
 * @param {HTMLElement} modal - The modal element to close
 */
function closeModal(modal) {
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';

        // Reset form if exists
        const form = modal.querySelector('form');
        if (form) {
            form.reset();

            // Reset validation states
            const inputs = form.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.classList.remove('error');
                const errorText = input.parentElement.querySelector('.error-text');
                if (errorText) {
                    errorText.remove();
                }
            });

            // Reset image preview if exists
            const imagePreview = form.querySelector('.image-preview');
            if (imagePreview) {
                imagePreview.innerHTML = '';
            }
        }
    }
}

/**
 * Initialize Notification System
 */
function initNotifications() {
    const notificationToast = document.getElementById('notification-toast');
    const notificationMessage = notificationToast.querySelector('.notification-message');
    const notificationIcon = notificationToast.querySelector('.notification-icon i');
    const closeBtn = notificationToast.querySelector('.notification-close');

    // Close notification on click
    closeBtn.addEventListener('click', () => {
        notificationToast.classList.remove('active');
    });

    // Make showNotification function global
    window.showNotification = function(message, type = 'success') {
        // Set message
        notificationMessage.textContent = message;

        // Set icon based on type
        notificationToast.className = 'notification-toast';
        notificationToast.classList.add(type);

        // Set icon based on type
        switch(type) {
            case 'success':
                notificationIcon.className = 'fas fa-check-circle';
                break;
            case 'error':
                notificationIcon.className = 'fas fa-times-circle';
                break;
            case 'warning':
                notificationIcon.className = 'fas fa-exclamation-circle';
                break;
            case 'info':
                notificationIcon.className = 'fas fa-info-circle';
                break;
        }

        // Show notification
        notificationToast.classList.add('active');

        // Auto hide after 5 seconds
        setTimeout(() => {
            notificationToast.classList.remove('active');
        }, 5000);
    };
}

/**
 * Initialize Form Validation
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            let isValid = true;

            // Get all required inputs
            const requiredInputs = form.querySelectorAll('[required]');

            requiredInputs.forEach(input => {
                // Remove previous error state
                input.classList.remove('error');
                const existingError = input.parentElement.querySelector('.error-text');
                if (existingError) {
                    existingError.remove();
                }

                // Check if input is empty
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('error');

                    // Add error message
                    const errorText = document.createElement('div');
                    errorText.className = 'error-text';
                    errorText.textContent = 'This field is required';
                    input.parentElement.appendChild(errorText);
                }

                // Additional validation for specific input types
                if (input.type === 'email' && input.value.trim()) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(input.value)) {
                        isValid = false;
                        input.classList.add('error');

                        const errorText = document.createElement('div');
                        errorText.className = 'error-text';
                        errorText.textContent = 'Please enter a valid email address';
                        input.parentElement.appendChild(errorText);
                    }
                }

                if (input.type === 'number' && input.value.trim()) {
                    if (isNaN(parseFloat(input.value))) {
                        isValid = false;
                        input.classList.add('error');

                        const errorText = document.createElement('div');
                        errorText.className = 'error-text';
                        errorText.textContent = 'Please enter a valid number';
                        input.parentElement.appendChild(errorText);
                    }
                }
            });

            // If form is not valid, prevent submission
            if (!isValid) {
                e.preventDefault();
            } else {
                // For demo purposes, prevent actual form submission and show success notification
                e.preventDefault();

                // Close modal if this is a modal form
                const modal = form.closest('.modal-overlay');
                if (modal) {
                    closeModal(modal);
                }

                // Show success notification
                const formAction = form.getAttribute('data-action') || 'saved';
                showNotification(`Item ${formAction} successfully!`, 'success');

                // Update table if needed (demo)
                const tableId = form.getAttribute('data-table');
                if (tableId) {
                    const table = document.getElementById(tableId);
                    if (table && formAction === 'added') {
                        addDummyRowToTable(table);
                    }
                }
            }
        });
    });
}

/**
 * Initialize Image Upload Functionality
 */
function initImageUploads() {
    const imageUploads = document.querySelectorAll('.image-upload');

    imageUploads.forEach(upload => {
        const input = upload.querySelector('input[type="file"]');
        const preview = upload.nextElementSibling; // Assuming the preview element is right after the upload element

        upload.addEventListener('click', () => {
            input.click();
        });

        input.addEventListener('change', (e) => {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(event) {
                    preview.innerHTML = `
                        <img src="${event.target.result}" alt="Preview">
                        <div class="image-preview-remove">
                            <i class="fas fa-times"></i>
                        </div>
                    `;

                    // Remove image on click
                    const removeBtn = preview.querySelector('.image-preview-remove');
                    removeBtn.addEventListener('click', (e) => {
                        e.stopPropagation(); // Prevent triggering the upload again
                        preview.innerHTML = '';
                        input.value = '';
                    });
                };

                reader.readAsDataURL(input.files[0]);
            }
        });
    });
}

/**
 * Initialize Data Tables Functionality
 * For demo purposes only - in a real app, this would use server-side pagination, filtering, etc.
 */
function initDataTables() {
    const tables = document.querySelectorAll('.data-table');

    tables.forEach(table => {
        const searchInput = document.querySelector(`[data-table-search="${table.id}"]`);
        const filterSelect = document.querySelector(`[data-table-filter="${table.id}"]`);

        // Search functionality
        if (searchInput) {
            searchInput.addEventListener('input', () => {
                const searchTerm = searchInput.value.toLowerCase();
                const rows = table.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Filter functionality
        if (filterSelect) {
            filterSelect.addEventListener('change', () => {
                const filterValue = filterSelect.value;
                const rows = table.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    if (!filterValue || row.getAttribute('data-category') === filterValue) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Delete functionality
        const deleteButtons = table.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();

                if (confirm('Are you sure you want to delete this item?')) {
                    const row = button.closest('tr');
                    row.style.opacity = '0';
                    setTimeout(() => {
                        row.remove();
                        showNotification('Item deleted successfully', 'success');
                    }, 300);
                }
            });
        });
    });
}

/**
 * Add dummy row to table for demo purposes
 * @param {HTMLElement} table - The table to add a row to
 */
function addDummyRowToTable(table) {
    const tbody = table.querySelector('tbody');
    const rows = tbody.querySelectorAll('tr');
    const firstRow = rows[0];

    if (firstRow) {
        const newRow = firstRow.cloneNode(true);
        const cells = newRow.querySelectorAll('td');

        // Set opacity to 0 initially for fade-in effect
        newRow.style.opacity = '0';
        newRow.style.backgroundColor = 'var(--success-light)';

        // Add to the top of the table
        tbody.insertBefore(newRow, tbody.firstChild);

        // Animate the row
        setTimeout(() => {
            newRow.style.opacity = '1';

            // Reset background color after animation
            setTimeout(() => {
                newRow.style.backgroundColor = '';
            }, 2000);
        }, 10);

        // Add event listeners for buttons
        const deleteBtn = newRow.querySelector('.delete-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', (e) => {
                e.preventDefault();

                if (confirm('Are you sure you want to delete this item?')) {
                    newRow.style.opacity = '0';
                    setTimeout(() => {
                        newRow.remove();
                        showNotification('Item deleted successfully', 'success');
                    }, 300);
                }
            });
        }
    }
}document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Show corresponding tab content
            const tabId = this.getAttribute('data-tab');
            document.getElementById(`${tabId}-content`).classList.add('active');
        });
    });

    // Automatically show success notification and hide after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        setTimeout(() => {
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);
    }

    // Pagination functionality (placeholder)
    const paginationButtons = document.querySelectorAll('.page-btn');
    paginationButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (!this.classList.contains('prev') && !this.classList.contains('next')) {
                paginationButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    // Notification toast functionality
    const notificationToast = document.getElementById('notification-toast');
    const notificationClose = document.querySelector('.notification-close');

    if (notificationClose) {
        notificationClose.addEventListener('click', function() {
            notificationToast.classList.remove('active');
        });
    }

    // Function to show notification
    window.showNotification = function(message, type = 'success') {
        if (notificationToast) {
            const notificationMessage = notificationToast.querySelector('.notification-message');
            const notificationIcon = notificationToast.querySelector('.notification-icon i');

            // Set message
            notificationMessage.textContent = message;

            // Set type
            notificationToast.className = 'notification-toast';
            notificationToast.classList.add(type);

            // Set icon
            if (type === 'success') {
                notificationIcon.className = 'fas fa-check-circle';
            } else if (type === 'error') {
                notificationIcon.className = 'fas fa-exclamation-circle';
            } else if (type === 'warning') {
                notificationIcon.className = 'fas fa-exclamation-triangle';
            } else if (type === 'info') {
                notificationIcon.className = 'fas fa-info-circle';
            }

            // Show notification
            notificationToast.classList.add('active');

            // Auto hide after 5 seconds
            setTimeout(function() {
                notificationToast.classList.remove('active');
            }, 5000);
        }
    };

    // Form validation functionality
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            let isValid = true;

            // Check required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');

                    // Add error message if it doesn't exist
                    let errorMsg = field.parentElement.querySelector('.error-text');
                    if (!errorMsg) {
                        errorMsg = document.createElement('div');
                        errorMsg.className = 'error-text';
                        errorMsg.textContent = 'This field is required';
                        field.parentElement.appendChild(errorMsg);
                    }
                } else {
                    field.classList.remove('error');
                    const errorMsg = field.parentElement.querySelector('.error-text');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            });

            if (!isValid) {
                event.preventDefault();
            }
        });

        // Real-time validation for fields
        const fields = form.querySelectorAll('input, select, textarea');
        fields.forEach(field => {
            field.addEventListener('input', function() {
                if (field.hasAttribute('required') && !field.value.trim()) {
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                    const errorMsg = field.parentElement.querySelector('.error-text');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            });
        });
    });
});
