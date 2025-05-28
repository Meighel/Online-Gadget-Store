let categoriesTable;
let categoriesData = [];

$(document).ready(function() {
    loadCategories();
});

function loadCategories() {
    $('#table-content').html(`
        <div class="loading-state">
            <i class="fas fa-spinner fa-spin"></i>
            <h3>Loading Categories...</h3>
            <p>Please wait while we fetch your categories from the database.</p>
        </div>
    `);

    $.ajax({
        url: '../API/manage-categories/categories-list.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                categoriesData = response.categories;
                initializeCategoryTable(response.categories);
            } else {
                showError('Failed to load categories: ' + (response.message || 'Unknown error'));
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

function initializeCategoryTable(categories) {
    if (!$.fn.DataTable.isDataTable('#categoriesTable')) {
        $('#table-content').html(`
            <table id="categoriesTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        `);

        categoriesTable = $('#categoriesTable').DataTable({
            data: categories,
            columns: [
                { data: 'id', render: data => `<span class="category-id">#${data}</span>` },
                { data: 'name', render: data => `<span class="category-name">${data}</span>` },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: id => `
                        <div class="action-buttons">
                            <button class="btn btn-edit" onclick="editCategory(${id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-delete" onclick="deleteCategory(${id})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>`
                }
            ],
            order: [[0, 'desc']],
            pageLength: 10,
            responsive: true,
            language: {
                search: "Search Categories:",
                lengthMenu: "Show _MENU_ categories per page",
                info: "Showing _START_ to _END_ of _TOTAL_ categories",
                infoEmpty: "No categories found",
                emptyTable: "No categories available",
                processing: "Loading categories..."
            }
        });
    } else {
        categoriesTable.clear().rows.add(categories).draw();
    }
}

function showError(message) {
    $('#table-content').html(`
        <div class="error-state">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>Error Loading Categories</h3>
            <p>${message}</p>
            <button class="btn btn-primary" onclick="loadCategories()">
                <i class="fas fa-refresh"></i> Try Again
            </button>
        </div>
    `);
}

function openAddModal() {
    $('#modalTitle').text('Add Category');
    $('#categoryForm')[0].reset();
    $('#categoryId').val('');
    $('#categoryModal').fadeIn(300);
}

function editCategory(id) {
    const category = categoriesData.find(c => c.id === id);
    if (category) {
        $('#modalTitle').text('Edit Category');
        $('#categoryId').val(category.id);
        $('#categoryName').val(category.name);
        $('#categoryDescription').val(category.description);
        $('#categoryModal').fadeIn(300);
    }
}

function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category?')) {
        categoriesData = categoriesData.filter(c => c.id !== id);
        initializeCategoryTable(categoriesData);
        alert('Category deleted (demo only)');
    }
}

function saveCategory() {
    const formData = {
        id: $('#categoryId').val(),
        name: $('#categoryName').val(),
        description: $('#categoryDescription').val()
    };

    if (!formData.name || !formData.description) {
        alert('Please fill in all fields');
        return;
    }

    if (formData.id) {
        const index = categoriesData.findIndex(c => c.id == formData.id);
        if (index !== -1) {
            categoriesData[index] = formData;
        }
    } else {
        formData.id = Math.max(...categoriesData.map(c => c.id)) + 1;
        categoriesData.push(formData);
    }

    initializeCategoryTable(categoriesData);
    closeModal();
    alert('Category saved (demo only)');
}

function closeModal() {
    $('#categoryModal').fadeOut(300);
}

window.onclick = function(event) {
    const modal = document.getElementById('categoryModal');
    if (event.target === modal) {
        closeModal();
    }
};

$(document).keydown(function(e) {
    if (e.key === 'Escape' && $('#categoryModal').is(':visible')) {
        closeModal();
    }
    if (e.ctrlKey && e.shiftKey && e.key === 'A') {
        e.preventDefault();
        openAddModal();
    }
});
