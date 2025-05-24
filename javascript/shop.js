const productGrid = document.getElementById('productGrid');
const loadingSpinner = document.getElementById('loadingSpinner');
const categoryButtons = document.querySelectorAll('.category-btn');
const searchInput = document.getElementById('searchInput');

function showLoading(show) {
    loadingSpinner.style.display = show ? 'block' : 'none';
}

async function fetchProducts(action = 'all', param = '') {
    showLoading(true);

    let url = `/Public/shop.php?ajax=1&action=${action}`;
    if (param) {
        if (action === 'category') {
            url += `&category=${encodeURIComponent(param)}`;
        } else if (action === 'search') {
            url += `&term=${encodeURIComponent(param)}`;
        }
    }

    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error('Network response was not ok');
        const products = await response.json();

        if (products.error) {
            throw new Error(products.error);
        }

        renderProducts(products);
    } catch (error) {
        productGrid.innerHTML = `<p class="text-danger">Failed to load products: ${error.message}</p>`;
    } finally {
        showLoading(false);
    }
}

function renderProducts(products) {
    if (!products || products.length === 0) {
        productGrid.innerHTML = '<p>No products found.</p>';
        return;
    }

    productGrid.innerHTML = products.map(product => `
        <div class="product-card">
            <img src="${product.image_url}" alt="${product.name}" class="product-image" />
            <h5>${product.name}</h5>
            <p>${product.category_name}</p>
            <p>$${parseFloat(product.price).toFixed(2)}</p>
            <p>Rating: ${product.rating}</p>
            ${product.badge ? `<span class="badge bg-primary">${product.badge}</span>` : ''}
            <p>${product.description}</p>
            <button class="btn btn-sm btn-primary" onclick="addToCart(${product.id})">Add to Cart</button>
        </div>
    `).join('');
}

// Use data-category attribute instead of textContent
categoryButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        categoryButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const category = btn.dataset.category;
        if (category === 'all') {
            fetchProducts('all');
        } else {
            fetchProducts('category', category);
        }
    });
});

function searchProducts() {
    const term = searchInput.value.trim();
    if (term.length > 0) {
        fetchProducts('search', term);
        categoryButtons.forEach(b => b.classList.remove('active'));
    } else {
        fetchProducts('all');
        categoryButtons.forEach(b => b.classList.remove('active'));
        categoryButtons[0].classList.add('active');
    }
}

function addToCart(productId) {
    alert(`Add product ${productId} to cart (implement this)`);
}

// Initial load
fetchProducts('all');
