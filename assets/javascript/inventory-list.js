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
            console.log('API Response:', response); // Debug log
            if (response.status === 'success') {
                inventoryData = response.items;
                initializeDataTable(response.items);
            } else {
                showError('Failed to load inventory: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr.responseText); // Debug log
            let errorMessage = 'Failed to connect to the server.';
            if (xhr.status === 404) errorMessage = 'API endpoint not found.';
            else if (xhr.status === 500) errorMessage = 'Server error.';
            else if (xhr.responseText) {
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    errorMessage = errorResponse.message || xhr.responseText;
                } catch (e) {
                    errorMessage = 'Server response: ' + xhr.responseText;
                }
            }
            showError(errorMessage);
        }
    });
}

function initializeDataTable(items) {
    if ($.fn.DataTable.isDataTable('#inventoryTable')) {
        $('#inventoryTable').DataTable().destroy();
    }

    $('#table-content').html(`
        <table id="inventoryTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Buyer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price (₱)</th>
                    <th>Order ID</th>
                    <th>Current Stock</th>
                    <th>Purchased At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    `);

    inventoryTable = $('#inventoryTable').DataTable({
        data: items,
        columns: [
            { 
                data: 'id', 
                render: data => `<span class="item-id">#${data}</span>` 
            },
            { 
                data: 'user_name', 
                render: data => `<span class="buyer-name">${data}</span>` 
            },
            { 
                data: 'product_name', 
                render: data => `<span class="product-name">${data}</span>` 
            },
            { data: 'quantity' },
            { 
                data: 'price_at_purchase', 
                render: data => `₱${parseFloat(data).toFixed(2)}` 
            },
            { data: 'order_id' },
            { data: 'current_stocks' },
            { 
                data: 'purchased_at',
                render: data => new Date(data).toLocaleDateString()
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: id => `
                    <div class="action-buttons">
                        <button class="btn btn-view" onclick="viewItem(${id})" title="View Details">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </div>
                `
            }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        autoWidth: false,
        responsive: true,
        language: {
            search: "Search Inventory:",
            lengthMenu: "Show _MENU_ items per page",
            info: "Showing _START_ to _END_ of _TOTAL_ inventory items",
            emptyTable: "No inventory records found",
            zeroRecords: "No matching inventory records found"
        },
        dom: 'lfrtip',
        columnDefs: [
            { targets: [8], orderable: false } // Actions column
        ]
    });
}

function showError(message) {
    $('#table-content').html(`
        <div class="error-state">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>Error Loading Inventory</h3>
            <p>${message}</p>
            <button class="btn btn-primary" onclick="loadInventory()">
                <i class="fas fa-refresh"></i> Try Again
            </button>
        </div>
    `);
}

function viewItem(id) {
    const item = inventoryData.find(i => i.id === id);
    if (item) {
        alert(`
            ID: ${item.id}
            Product: ${item.product_name}
            Buyer: ${item.user_name}
            Quantity: ${item.quantity}
            Price: ₱${parseFloat(item.price_at_purchase).toFixed(2)}
            Order ID: ${item.order_id}
            Current Stock: ${item.current_stocks}
            Purchased: ${new Date(item.purchased_at).toLocaleString()}
        `);
    }
}

function editItem(id) {
    // Edit functionality removed - inventory is managed by triggers
    console.log('Edit functionality disabled for inventory records');
}

function refreshInventory() {
    loadInventory();
}

// Logout function
function logout() {
    fetch('../API/logout.php', { method: 'POST' })
        .then(() => {
            window.location.href = '../Public/login.php';
        })
        .catch(err => {
            console.error('Logout error:', err);
            window.location.href = '../Public/login.php';
        });
}