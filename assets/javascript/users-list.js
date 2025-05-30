let usersTable;
let usersData = [];

$(document).ready(function () {
    loadUsers();
});

function loadUsers() {
    $('#table-content').html(`
        <div class="loading-state">
            <i class="fas fa-spinner fa-spin"></i>
            <h3>Loading Users...</h3>
            <p>Please wait while we fetch user data from the database.</p>
        </div>
    `);

    $.ajax({
        url: '../API/manage-users/users-list.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                usersData = response.users;
                initializeDataTable(response.users);
            } else {
                showError('Failed to load users: ' + (response.message || 'Unknown error'));
            }
        },
        error: function (xhr, status, error) {
            let errorMessage = 'Failed to connect to the server.';
            if (xhr.status === 404) {
                errorMessage = 'API endpoint not found.';
            } else if (xhr.status === 500) {
                errorMessage = 'Server error. Check PHP code or database.';
            } else if (xhr.responseText) {
                errorMessage = 'Server response: ' + xhr.responseText;
            }
            showError(errorMessage);
        }
    });
}

function initializeDataTable(users) {
    if (!$.fn.DataTable.isDataTable('#usersTable')) {
        $('#table-content').html(`
            <table id="usersTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        `);

        usersTable = $('#usersTable').DataTable({
            data: users,
            columns: [
                { data: 'id', render: data => `<span class="user-id">#${data}</span>` },
                { data: 'name' },
                { data: 'email' },
                { 
                    data: 'role', 
                    render: data => {
                        const roleColors = {
                            'admin': 'badge-admin',
                            'staff': 'badge-staff', 
                            'client': 'badge-client'
                        };
                        return `<span class="role-badge ${roleColors[data] || 'badge-default'}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                    }
                },
                { 
                    data: 'created_at',
                    render: data => {
                        const date = new Date(data);
                        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
                    }
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: id => `
                        <div class="action-buttons">
                            <button class="btn btn-edit" onclick="editUser(${id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-delete" onclick="deleteUser(${id})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>`
                }
            ],
            processing: true,
            pageLength: 10,
            order: [[0, 'desc']],
            language: {
                search: "Search Users:",
                lengthMenu: "Show _MENU_ users per page",
                info: "Showing _START_ to _END_ of _TOTAL_ users",
                infoEmpty: "No users found",
                infoFiltered: "(filtered from _MAX_ total users)",
                emptyTable: "No users available in the database",
                processing: "Loading users..."
            },
            dom: 'lfrtip'
        });
    } else {
        usersTable.clear().rows.add(users).draw();
    }
}

function showError(message) {
    $('#table-content').html(`
        <div class="error-state">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>Error Loading Users</h3>
            <p>${message}</p>
            <button class="btn btn-primary" onclick="loadUsers()">
                <i class="fas fa-refresh"></i>
                Try Again
            </button>
        </div>
    `);
}

// Fixed function name to match HTML onclick
function addUserModal() {
    $('#addUserModal').text('Add User');
    $('#userForm')[0].reset();
    $('#userId').val('');
    $('#userPassword').prop('required', true);
    $('#userModal').fadeIn(300);
}

function editUser(id) {
    const user = usersData.find(u => u.id == id);
    if (user) {
        $('#addUserModal').text('Edit User');
        $('#userId').val(user.id);
        $('#userName').val(user.name);
        $('#userEmail').val(user.email);
        $('#userRole').val(user.role);
        $('#userPassword').val(''); // Clear password field
        $('#userPassword').prop('required', false); // Password not required for edit
        $('#userModal').fadeIn(300);
    }
}

function deleteUser(id) {
    const user = usersData.find(u => u.id == id);
    if (!user) {
        alert('User not found');
        return;
    }

    if (confirm(`Are you sure you want to delete user "${user.name}"? This action cannot be undone.`)) {
        // Show loading state
        const deleteBtn = $(`.btn-delete[onclick="deleteUser(${id})"]`);
        const originalText = deleteBtn.html();
        deleteBtn.html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        deleteBtn.prop('disabled', true);

        $.ajax({
            url: '../API/manage-users/delete-user.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ id: id }),
            success: function(response) {
                if (response.status === 'success') {
                    alert('User deleted successfully!');
                    loadUsers(); // Reload the table
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Failed to delete user. ';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += xhr.responseJSON.message;
                } else {
                    errorMessage += 'Please try again.';
                }
                alert(errorMessage);
            },
            complete: function() {
                // Restore button state
                deleteBtn.html(originalText);
                deleteBtn.prop('disabled', false);
            }
        });
    }
}

function saveUser() {
    const formData = {
        name: $('#userName').val().trim(),
        email: $('#userEmail').val().trim(),
        role: $('#userRole').val(),
        password: $('#userPassword').val()
    };

    const isEdit = $('#userId').val();
    if (isEdit) {
        formData.id = parseInt($('#userId').val());
    }

    // Validation
    if (!formData.name || !formData.email || !formData.role) {
        alert('Please fill in all required fields');
        return;
    }

    // For new users, password is required
    if (!isEdit && !formData.password) {
        alert('Password is required for new users');
        return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(formData.email)) {
        alert('Please enter a valid email address');
        return;
    }

    // Show loading state
    const saveBtn = $('.btn-primary');
    const originalText = saveBtn.text();
    saveBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...');
    saveBtn.prop('disabled', true);

    const apiUrl = isEdit ? '../API/manage-users/update-user.php' : '../API/manage-users/add-user.php';
    const actionText = isEdit ? 'updated' : 'created';

    $.ajax({
        url: apiUrl,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(formData),
        success: function(response) {
            if (response.status === 'success') {
                alert(`User ${actionText} successfully!`);
                closeUserModal();
                loadUsers(); // Reload the table
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            let errorMessage = `Failed to ${isEdit ? 'update' : 'create'} user. `;
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage += xhr.responseJSON.message;
            } else {
                errorMessage += 'Please try again.';
            }
            alert(errorMessage);
        },
        complete: function() {
            // Restore button state
            saveBtn.text(originalText);
            saveBtn.prop('disabled', false);
        }
    });
}

function closeUserModal() {
    $('#userModal').fadeOut(300);
    $('#userForm')[0].reset();
}

// Window click event to close modal
window.onclick = function (event) {
    const modal = document.getElementById('userModal');
    if (event.target === modal) {
        closeUserModal();
    }
};

// Keyboard shortcuts
$(document).keydown(function (e) {
    if (e.key === 'Escape' && $('#userModal').is(':visible')) {
        closeUserModal();
    }

    if (e.ctrlKey && e.shiftKey && e.key === 'A') {
        e.preventDefault();
        addUserModal();
    }
});