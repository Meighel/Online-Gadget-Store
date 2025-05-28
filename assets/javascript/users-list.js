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
                { data: 'role' },
                { data: 'created_at' },
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

function openAddModal() {
    $('#modalTitle').text('Add User');
    $('#userForm')[0].reset();
    $('#userId').val('');
    $('#userModal').fadeIn(300);
}

function editUser(id) {
    const user = usersData.find(u => u.id === id);
    if (user) {
        $('#modalTitle').text('Edit User');
        $('#userId').val(user.id);
        $('#userName').val(user.name);
        $('#userEmail').val(user.email);
        $('#userRole').val(user.role);
        $('#userModal').fadeIn(300);
    }
}

function deleteUser(id) {
    if (confirm('Are you sure you want to delete this user?')) {
        usersData = usersData.filter(user => user.id !== id);
        initializeDataTable(usersData);
        alert('User deleted successfully (demo only)');
    }
}

function saveUser() {
    const formData = {
        id: $('#userId').val(),
        name: $('#userName').val(),
        email: $('#userEmail').val(),
        role: $('#userRole').val()
    };

    if (!formData.name || !formData.email || !formData.role) {
        alert('Please fill in all fields');
        return;
    }

    if (formData.id) {
        const index = usersData.findIndex(u => u.id == formData.id);
        if (index !== -1) {
            usersData[index] = formData;
        }
    } else {
        formData.id = Math.max(...usersData.map(u => u.id)) + 1;
        usersData.push(formData);
    }

    initializeDataTable(usersData);
    closeModal();
    alert('User saved successfully (demo only)');
}

function closeModal() {
    $('#userModal').fadeOut(300);
}

window.onclick = function (event) {
    const modal = document.getElementById('userModal');
    if (event.target === modal) {
        closeModal();
    }
};

$(document).keydown(function (e) {
    if (e.key === 'Escape' && $('#userModal').is(':visible')) {
        closeModal();
    }

    if (e.ctrlKey && e.shiftKey && e.key === 'A') {
        e.preventDefault();
        openAddModal();
    }
});
