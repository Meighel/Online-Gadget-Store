let productsTable;
let productsData = [];

$(document).ready(function() {
    loadProducts();
});

function loadProducts() {
    // Show loading state
    $('#table-content').html(`
        <div class="loading-state">
            <i class="fas fa-spinner fa-spin"></i>
            <h3>Loading Products...</h3>
            <p>Please wait while we fetch your products from the database.</p>
        </div>
    `);

    $.ajax({
        url: '../API/manage-products/products-list.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Response received:', response);
            if (response.status === 'success') {
                productsData = response.products;
                initializeDataTable(response.products);
            } else {
                console.error('API Error:', response);
                showError('Failed to load products: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error Details:');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response Text:', xhr.responseText);
            console.error('Status Code:', xhr.status);
            
            let errorMessage = 'Failed to connect to the server.';
            
            if (xhr.status === 404) {
                errorMessage = 'API endpoint not found. Please check the file path.';
            } else if (xhr.status === 500) {
                errorMessage = 'Server error. Please check your PHP code and database connection.';
            } else if (xhr.responseText) {
                errorMessage = 'Server response: ' + xhr.responseText;
            }
            
            showError(errorMessage);
        }
    });
}

function initializeDataTable(products) {
    if (!$.fn.DataTable.isDataTable('#productsTable')) {
        $('#table-content').html(`
            <table id="productsTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        `);

        productsTable = $('#productsTable').DataTable({
            data: products,
            columns: [
                { data: 'id', render: data => `<span class="product-id">#${data}</span>` },
                {
                    data: 'image_url',
                    orderable: false,
                    searchable: false,
                    render: (data, type, row) => {
                        const url = data || 'https://via.placeholder.com/50x50?text=No+Image';
                        return `<img src="${url}" alt="${row.name}" class="product-image" style="width:50px;height:50px;" onerror="this.src='https://via.placeholder.com/50x50?text=No+Image'">`;
                    }
                },
                { data: 'name', render: data => `<span class="product-name">${data}</span>` },
                {
                    data: 'description',
                    render: data => `<span class="description" title="${data}">${data}</span>`
                },
                {
                    data: 'price',
                    render: data => `<span class="price">$${parseFloat(data).toFixed(2)}</span>`
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: id => `
                        <div class="action-buttons">
                            <button class="btn btn-edit" onclick="editProduct(${id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-delete" onclick="deleteProduct(${id})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>`
                }
            ],
            processing: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            order: [[0, 'desc']],
            autoWidth: false,
            responsive: false,
            scrollX: false,
            deferRender: true,
            language: {
                search: "Search Products:",
                lengthMenu: "Show _MENU_ products per page",
                info: "Showing _START_ to _END_ of _TOTAL_ products",
                infoEmpty: "No products found",
                infoFiltered: "(filtered from _MAX_ total products)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                },
                emptyTable: "No products available in the database",
                processing: "Loading products..."
            },
            dom: 'lfrtip'
        });
    } else {
        productsTable.clear().rows.add(products).draw();
    }
}


function showError(message) {
    $('#table-content').html(`
        <div class="error-state">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>Error Loading Products</h3>
            <p>${message}</p>
            <button class="btn btn-primary" onclick="loadProducts()">
                <i class="fas fa-refresh"></i>
                Try Again
            </button>
        </div>
    `);
}

function openAddModal() {
    $('#modalTitle').text('Add Product');
    $('#productForm')[0].reset();
    $('#productId').val('');
    $('#productModal').fadeIn(300);
}

function editProduct(id) {
    const product = productsData.find(p => p.id === id);
    
    if (product) {
        $('#modalTitle').text('Edit Product');
        $('#productId').val(product.id);
        $('#productName').val(product.name);
        $('#productDescription').val(product.description);
        $('#productPrice').val(product.price);
        $('#productImage').val(product.image_url);
        $('#productModal').fadeIn(300);
    }
}

function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        // For demo purposes, remove from local data
        productsData = productsData.filter(product => product.id !== id);
        
        // Reinitialize the table with updated data
        initializeDataTable(productsData);
        
        // In a real application, you would make an AJAX call to delete from the server
        /*
        $.ajax({
            url: '../API/manage-products/delete-product.php',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Reload products after successful deletion
                    loadProducts();
                } else {
                    alert('Failed to delete product: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                alert('Error deleting product: ' + error);
            }
        });
        */
        
        alert('Product deleted successfully (demo only - no server call made)');
    }
}

function saveProduct() {
    const formData = {
        id: $('#productId').val(),
        name: $('#productName').val(),
        description: $('#productDescription').val(),
        price: $('#productPrice').val(),
        image_url: $('#productImage').val()
    };

    // Simple client-side validation
    if (!formData.name || !formData.description || !formData.price || !formData.image_url) {
        alert('Please fill in all fields');
        return;
    }

    // For demo purposes, update our local data
    if (formData.id) {
        // Edit existing product
        const index = productsData.findIndex(p => p.id == formData.id);
        if (index !== -1) {
            productsData[index] = formData;
        }
    } else {
        // Add new product - generate a new ID
        formData.id = Math.max(...productsData.map(p => p.id)) + 1;
        productsData.push(formData);
    }

    // Reinitialize the table with updated data
    initializeDataTable(productsData);
    closeModal();
    
    // In a real application, you would make an AJAX call to save to the server
    /*
    const url = formData.id ? '../API/manage-products/update-product.php' : '../API/manage-products/add-product.php';
    
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Reload products after successful save
                loadProducts();
                closeModal();
            } else {
                alert('Failed to save product: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr, status, error) {
            alert('Error saving product: ' + error);
        }
    });
    */
    
    alert('Product saved successfully (demo only - no server call made)');
}

function closeModal() {
    $('#productModal').fadeOut(300);
}

function logout() {t
    window.location.href = '../logout.php';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target === modal) {
        closeModal();
    }
};

// Keyboard shortcuts
$(document).keydown(function(e) {
    // Escape key closes modal
    if (e.key === 'Escape' && $('#productModal').is(':visible')) {
        closeModal();
    }
    
    // Ctrl+Shift+A opens add modal
    if (e.ctrlKey && e.shiftKey && e.key === 'A') {
        e.preventDefault();
        openAddModal();
    }
});