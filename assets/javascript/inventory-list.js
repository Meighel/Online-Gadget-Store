let inventoryTable;
let inventoryData = [];

$(document).ready(function() {
    loadInventory();
});

function loadInventory() {
    $('#table-content').html(`
        <div class="loading-state">
            <i class="fas fa-spinner fa-spin"></i>
            <h3>Loading Inventory...</h3>
            <p>Please wait while we fetch inventory data from the database.</p>
        </div>
    `);

    $.ajax({
        url: '../API/manage-inventory/inventory-list.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                inventoryData = response.items;
                initializeDataTable(response.items);
            } else {
                showError('Failed to load inventory: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr) {
            let errorMessage = 'Failed to connect to the server.';
            if (xhr.status === 404) errorMessage = 'API endpoint not found.';
            else if (xhr.status === 500) errorMessage = 'Server error.';
            else if (xhr.responseText) errorMessage = 'Server response: ' + xhr.responseText;
            showError(errorMessage);
        }
    });
}

function initializeDataTable(items) {
    if (!$.fn.DataTable.isDataTable('#inventoryTable')) {
        $('#table-content').html(`
            <table id="inventoryTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        `);

        inventoryTable = $('#inventoryTable').DataTable({
            data: items,
            columns: [
                { data: 'id', render: data => `<span class="item-id">#${data}</span>` },
                { data: 'name', render: data => `<span class="item-name">${data}</span>` },
                { data: 'category' },
                { data: 'quantity' },
                { data: 'location' },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: id => {
                        if (currentUserRole === 'admin') {
                            return `
                                <div class="action-buttons">
                                    <button class="btn btn-edit" onclick="editItem(${id})"><i class="fas fa-edit"></i> Edit</button>
                                    <button class="btn btn-delete" onclick="deleteItem(${id})"><i class="fas fa-trash"></i> Delete</button>
                                </div>`;
                        } else {
                            return `<span class="text-muted">View Only</span>`;
                        }
                    }
                }
            ],
            order: [[0, 'desc']],
            pageLength: 10,
            autoWidth: false,
            deferRender: true,
            language: {
                search: "Search Inventory:",
                lengthMenu: "Show _MENU_ items",
                info: "Showing _START_ to _END_ of _TOTAL_ items",
                emptyTable: "No inventory available"
            },
            dom: 'lfrtip'
        });
    } else {
        inventoryTable.clear().rows.add(items).draw();
    }
}

function showError(message) {
    $('#table-content').html(`
        <div class="error-state">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>Error Loading Inventory</h3>
            <p>${message}</p>
            <button class="btn btn-primary" onclick="loadInventory()"><i class="fas fa-refresh"></i> Try Again</button>
        </div>
    `);
}

function openAddModal() {
    if (currentUserRole !== 'admin') return alert('Access Denied: Only admins can add items.');
    $('#modalTitle').text('Add Inventory Item');
    $('#inventoryForm')[0].reset();
    $('#itemId').val('');
    $('#inventoryModal').fadeIn(300);
}

function editItem(id) {
    if (currentUserRole !== 'admin') return alert('Access Denied: Only admins can edit items.');
    const item = inventoryData.find(i => i.id === id);
    if (item) {
        $('#modalTitle').text('Edit Inventory Item');
        $('#itemId').val(item.id);
        $('#itemName').val(item.name);
        $('#itemCategory').val(item.category);
        $('#itemQuantity').val(item.quantity);
        $('#itemLocation').val(item.location);
        $('#inventoryModal').fadeIn(300);
    }
}

function deleteItem(id) {
    if (currentUserRole !== 'admin') return alert('Access Denied: Only admins can delete items.');
    if (confirm('Are you sure you want to delete this inventory item?')) {
        inventoryData = inventoryData.filter(i => i.id !== id);
        initializeDataTable(inventoryData);
        alert('Item deleted (demo only - no server call)');
    }
}

function saveItem() {
    if (currentUserRole !== 'admin') return alert('Access Denied: Only admins can save items.');
    const formData = {
        id: $('#itemId').val(),
        name: $('#itemName').val(),
        category: $('#itemCategory').val(),
        quantity: $('#itemQuantity').val(),
        location: $('#itemLocation').val()
    };

    if (!formData.name || !formData.category || !formData.quantity || !formData.location) {
        alert('Please fill in all fields');
        return;
    }

    if (formData.id) {
        const index = inventoryData.findIndex(i => i.id == formData.id);
        if (index !== -1) inventoryData[index] = formData;
    } else {
        formData.id = Math.max(...inventoryData.map(i => i.id), 0) + 1;
        inventoryData.push(formData);
    }

    initializeDataTable(inventoryData);
    closeModal();
    alert('Item saved (demo only - no server call)');
}

function closeModal() {
    $('#inventoryModal').fadeOut(300);
}

window.onclick = function(event) {
    if (event.target === document.getElementById('inventoryModal')) closeModal();
};

$(document).keydown(function(e) {
    if (e.key === 'Escape' && $('#inventoryModal').is(':visible')) closeModal();
    if (e.ctrlKey && e.shiftKey && e.key === 'A') {
        e.preventDefault();
        openAddModal();
    }
});

function logout() {
    window.location.href = '../logout.php';
}
