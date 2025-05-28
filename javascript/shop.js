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
        <a href="/Public/product.php?id=${product.id}" class="product-link">
            <div class="product-card">
                <img src="${product.image_url}" alt="${product.name}" class="product-image" />
                <h5>${product.name}</h5>
                <p>${product.category_name}</p>
                <p>$${parseFloat(product.price).toFixed(2)}</p>
                <p>Rating: ${product.rating}</p>
                ${product.badge ? `<span class="badge bg-primary">${product.badge}</span>` : ''}
                <p>${product.description}</p>
                <button class="btn btn-sm btn-primary" onclick="event.preventDefault(); addToCart(${product.id});">Add to Cart</button>
            </div>
        </a>
    `).join('');
}


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

async function addToCart(productId) {
    try {
        const response = await fetch(`/API/add_to_cart.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        });

        if (!response.ok) throw new Error('Failed to add to cart');

        const result = await response.json();
        if (result.success) {
            alert(`Product ${productId} added to cart!`);
            updateCartCount();
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert(`Error: ${error.message}`);
    }
}

async function updateCartCount() {
    try {
        const response = await fetch(`/API/get_cart.php`);
        const cart = await response.json();
        const count = cart.items.reduce((total, item) => total + item.quantity, 0);
        document.getElementById('cartCount').innerText = count;
    } catch (error) {
        console.error('Error fetching cart count:', error);
    }
}

fetchProducts('all');
