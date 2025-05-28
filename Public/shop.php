<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $_SESSION['user_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Shop | TechNest</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="../index.php">TechNest</a>
    <div class="d-flex gap-2">
      <a href="shop.php" class="btn text-white">Shop</a>
      <?php if ($isLoggedIn): ?>
        <a href="../User/cart.php" class="btn text-white">Cart</a>
        <a href="../User/my_orders.php" class="btn text-white">Orders</a>
        <button id="logoutBtn" class="btn btn-outline-light">Logout</button>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h2 class="mb-4">Explore Our Products</h2>
  <div id="productContainer" class="row g-4">
    <!-- Products will be injected here -->
  </div>
</div>

<script>
const isLoggedIn = <?= json_encode($isLoggedIn) ?>;
const userId = <?= json_encode($userId) ?>;

document.addEventListener('DOMContentLoaded', () => {
  fetch('../API/get_products.php')
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success' && Array.isArray(data.products)) {
        const products = data.products;
        const container = document.getElementById('productContainer');

        if (products.length === 0) {
          container.innerHTML = '<p>No products available.</p>';
          return;
        }

        products.forEach(product => {
          const imageSrc = product.image_url.startsWith('http')
            ? product.image_url
            : '../' + product.image_url.replace(/^\/+/, '');

          const card = document.createElement('div');
          card.className = 'col-md-4';

          card.innerHTML = `
            <div class="card h-100">
              <img src="${imageSrc}" class="card-img-top" alt="${product.name}" style="max-height: 200px; object-fit: contain;">
              <div class="card-body">
                <h5 class="card-title">${product.name}</h5>
                <p class="card-text">${product.description}</p>
                <p class="text-primary fw-bold">₱${Number(product.price).toLocaleString()}</p>
                <button class="btn btn-sm btn-outline-primary add-to-cart-btn" data-product-id="${product.id}">Add to Cart</button>
              </div>
            </div>
          `;
          container.appendChild(card);
        });

        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
          button.addEventListener('click', async () => {
            const productId = button.dataset.productId;

            if (!isLoggedIn) {
              window.location.href = 'login.php';
              return;
            }

            const response = await fetch('../API/add_to_cart.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                product_id: productId,
              })
            });

            const result = await response.json();
            if (result.status === 'success') {
              alert('Added to cart!');
            } else {
              alert('Failed to add to cart.');
            }
          });
        });

      } else {
        document.getElementById('productContainer').innerHTML = '<p>Error loading products.</p>';
      }
    })
    .catch(err => {
      console.error('Fetch error:', err);
      document.getElementById('productContainer').innerHTML = '<p>Unable to load products.</p>';
    });

  const logoutBtn = document.getElementById('logoutBtn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', async () => {
      await fetch('../API/logout.php', { method: 'POST' });
      window.location.href = 'login.php';
    });
  }
});
</script>

</body>
</html>
