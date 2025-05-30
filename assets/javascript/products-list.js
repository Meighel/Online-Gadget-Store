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
                        <th>Category</th>
                        <th>Stocks</th>
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
                    render: data => `<span class="description" title="${data}">${data.substring(0, 50)}${data.length > 50 ? '...' : ''}</span>`
                },
                {
                    data: 'price',
                    render: data => `<span class="price">$${parseFloat(data).toFixed(2)}</span>`
                },
                {
                    data: 'category_name',
                    render: data => `<span class="category">${data || 'Uncategorized'}</span>`
                },
                {
                    data: 'stocks',
                    render: data => `<span class="stocks ${data <= 0 ? 'out-of-stock' : ''}">${data}</span>`
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: id => `
                        <div class="action-buttons">
                            <button class="btn btn-edit" data-id="${id}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-delete" data-id="${id}">
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

        // Add event delegation for edit and delete buttons
        setupTableEventHandlers();
    } else {
        productsTable.clear().rows.add(products).draw();
    }
}

// Add event delegation function for table buttons
function setupTableEventHandlers() {
    // Remove any existing handlers to prevent duplicates
    $(document).off('click', '.btn-edit');
    $(document).off('click', '.btn-delete');
    
    // Use event delegation for edit buttons
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        const id = parseInt($(this).data('id'));
        editProduct(id);
    });
    
    // Use event delegation for delete buttons  
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const id = parseInt($(this).data('id'));
        deleteProduct(id);
    });
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
    const product = productsData.find(p => p.id == id);
    
    if (product) {
        $('#modalTitle').text('Edit Product');
        $('#productId').val(product.id);
        $('#productName').val(product.name);
        $('#productDescription').val(product.description);
        $('#productPrice').val(product.price);
        $('#productImage').val(product.image_url);
        $('#productStocks').val(product.stocks);
        
        // Load categories first, then set the selected value
        fetchCategoriesForDropdown().then(() => {
            $('#productCategory').val(product.category_id || '');
        });
        
        $('#productModal').fadeIn(300);
    }
}

function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        // Show loading indicator
        const deleteBtn = $(`.btn-delete[data-id="${id}"]`);
        const originalText = deleteBtn.html();
        deleteBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        
        $.ajax({
            url: '../API/manage-products/delete-product.php',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message
                    alert('Product deleted successfully!');
                    // Reload products after successful deletion
                    loadProducts();
                } else {
                    alert('Failed to delete product: ' + (response.message || 'Unknown error'));
                    // Restore button state
                    deleteBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('Delete Error:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText
                });
                
                let errorMessage = 'Error deleting product: ';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += xhr.responseJSON.message;
                } else {
                    errorMessage += error;
                }
                
                alert(errorMessage);
                // Restore button state
                deleteBtn.prop('disabled', false).html(originalText);
            }
        });
    }
}

function saveProduct() {
    const formData = {
        id: $('#productId').val(),
        name: $('#productName').val().trim(),
        description: $('#productDescription').val().trim(),
        price: parseFloat($('#productPrice').val()),
        image_url: $('#productImage').val().trim(),
        category_id: $('#productCategory').val() || null,
        stocks: parseInt($('#productStocks').val()) || 0
    };

    // Client-side validation
    if (!formData.name) {
        alert('Please enter a product name');
        $('#productName').focus();
        return;
    }

    if (!formData.description) {
        alert('Please enter a product description');
        $('#productDescription').focus();
        return;
    }

    if (!formData.price || formData.price <= 0) {
        alert('Please enter a valid price greater than 0');
        $('#productPrice').focus();
        return;
    }

    if (!formData.image_url) {
        alert('Please enter an image URL');
        $('#productImage').focus();
        return;
    }

    if (isNaN(formData.stocks) || formData.stocks < 0) {
        alert('Please enter a valid stock quantity (0 or higher)');
        $('#productStocks').focus();
        return;
    }

    // Simple URL validation
    try {
        new URL(formData.image_url);
    } catch (e) {
        alert('Please enter a valid image URL');
        $('#productImage').focus();
        return;
    }

    // Show loading state
    const saveBtn = $('.btn-primary');
    const originalText = saveBtn.html();
    saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

    // Determine if we're adding or updating
    const url = formData.id ? '../API/manage-products/update-product.php' : '../API/manage-products/add-product.php';
    const action = formData.id ? 'updated' : 'added';
    
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert(`Product ${action} successfully!`);
                // Reload products after successful save
                loadProducts();
                closeModal();
            } else {
                alert(`Failed to ${action.slice(0, -1)} product: ` + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr, status, error) {
            console.error('Save Error:', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            
            let errorMessage = `Error ${action.slice(0, -1)}ing product: `;
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage += xhr.responseJSON.message;
            } else {
                errorMessage += error;
            }
            
            alert(errorMessage);
        },
        complete: function() {
            // Restore button state
            saveBtn.prop('disabled', false).html(originalText);
        }
    });
}

function closeModal() {
    $('#productModal').fadeOut(300);
}

function logout() {
    window.location.href = '../logout.php';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('productModal');
    if (event.target === modal) {
        closeModal();
    }
};

// Updated fetchCategoriesForDropdown function
async function fetchCategoriesForDropdown() {
    try {
        const response = await fetch('../API/manage-products/get-categories.php');
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Categories response:', data); // Debug log
        
        const select = $('#productCategory');
        select.empty().append('<option value="">-- Select Category --</option>');
        
        if (data.status === 'success' && Array.isArray(data.categories)) {
            if (data.categories.length > 0) {
                data.categories.forEach(category => {
                    select.append(`<option value="${category.id}">${category.name}</option>`);
                });
                console.log(`Successfully loaded ${data.categories.length} categories`);
            } else {
                select.append('<option value="" disabled>No categories available</option>');
                console.log('No categories found in database');
            }
        } else {
            throw new Error(data.message || 'Invalid response format');
        }
        
    } catch (error) {
        console.error('Error fetching categories:', error);
        const select = $('#productCategory');
        select.empty()
            .append('<option value="">-- Select Category --</option>')
            .append('<option value="" disabled>Error loading categories</option>');
        
        // Show user-friendly error
        alert('Failed to load categories. Please check console for details.');
    }
}

// Call this in your openAddModal function:
function openAddModal() {
    $('#modalTitle').text('Add Product');
    $('#productForm')[0].reset();
    $('#productId').val('');
    fetchCategoriesForDropdown(); // Load categories for dropdown
    $('#productModal').fadeIn(300);
}

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