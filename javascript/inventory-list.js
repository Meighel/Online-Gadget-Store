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
                { data: 'location' }
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

function logout() {
    window.location.href = '../logout.php';
}
