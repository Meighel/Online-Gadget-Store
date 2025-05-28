<?php
session_start();
require '../db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/shop_styles.css">
    <link rel="stylesheet" href="../assets/css/cart.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="../index.php">TechNest</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center gap-2">
        <li class="nav-item">
          <a href="client_dashboard.php" class="nav-link text-white">Dashboard</a>
        </li>
        <li class="nav-item">
          <a href="../Public/shop.php" class="nav-link text-white">Shop</a>
        </li>

          <li class="nav-item position-relative">
            <a class="nav-link text-white" href="../User/cart.php">
              <i class="fas fa-shopping-cart me-1"></i>
              <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" id="cartCount" style="font-size: 0.7rem;">0</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="../User/my_orders.php" class="nav-link text-white">Orders</a>
          </li>
          <li class="nav-item">
            <button class="btn btn-outline-light" id="logoutBtn">Logout</button>
          </li>
      </ul>
    </div>
  </div>
</nav>


  <div class="container mt-5">
    <h2 class="text-center">Your Shopping Cart</h2>
    <a href="../Public/shop.php" class="btn btn-primary mb-4">Back to Shop</a>

    <form id="checkoutForm" action="checkout.php" method="POST">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Select</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="cart-items">
          <tr><td colspan="6" class="text-center">Loading cart...</td></tr>
        </tbody>
      </table>

      <div class="text-right">
        <h4>Total: <span id="total-price">₱0.00</span></h4>
        <button type="submit" class="btn btn-primary" id="checkout-btn">Proceed to Checkout</button>
      </div>
    </form>
  </div>

  <footer class="text-center mt-5">
    <p>&copy; 2023 TechNest. All rights reserved.</p>
  </footer>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script>
    function formatPrice(num) {
      return '₱' + Number(num).toLocaleString(undefined, { minimumFractionDigits: 2 });
    }

    function loadCart() {
      $.getJSON('../API/get_cart.php', function (res) {
        if (res.status !== 'success') {
          $('#cart-items').html('<tr><td colspan="6">Failed to load cart.</td></tr>');
          return;
        }

        let rows = '';
        res.cart.forEach(item => {
        const itemTotal = item.price * item.quantity;

        rows += `
            <tr id="item-${item.id}">
            <td><input type="checkbox" class="product-checkbox" name="selected_products[]" value="${item.id}" data-price="${item.price}" data-quantity="${item.quantity}"></td>
            <td>${item.name}</td>
            <td>${formatPrice(item.price)}</td>
            <td>
                <input type="number" min="1" value="${item.quantity}" class="form-control quantity-input" data-id="${item.id}" data-price="${item.price}">
            </td>
            <td class="item-total">${formatPrice(itemTotal)}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="${item.id}">Delete</button>
            </td>
            </tr>
        `;
        });

        $('#cart-items').html(rows);
        recalculateTotals(); // ✅ Only count selected items

      });
    }

    function recalculateTotals() {
      let total = 0;
      $('#cart-items tr').each(function () {
        const row = $(this);
        const price = parseFloat(row.find('.quantity-input').data('price'));
        const quantity = parseInt(row.find('.quantity-input').val());
        const itemTotal = price * quantity;

        row.find('.item-total').text(formatPrice(itemTotal));
        if (row.find('.product-checkbox').is(':checked')) {
          total += itemTotal;
        }
      });
      $('#total-price').text(formatPrice(total));
    }

    $(document).ready(function () {
      loadCart();

      $(document).on('change', '.product-checkbox, .quantity-input', recalculateTotals);

      $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');

        $.ajax({
            url: '../API/delete_cart_item.php',
            type: 'POST',
            data: JSON.stringify({ product_id: id }),
            contentType: 'application/json', // crucial
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                $(`#item-${id}`).remove();
                recalculateTotals();
                } else {
                alert('Delete failed.');
                }
            },
            error: function () {
                alert('Error communicating with server.');
            }
        });

      });

      $('#checkoutForm').on('submit', function (e) {
        e.preventDefault();

        const selectedItems = [];
        let total = 0;

        $('.product-checkbox:checked').each(function () {
          const row = $(this).closest('tr');
          const id = $(this).val();
          const quantity = row.find('.quantity-input').val();
          const price = parseFloat($(this).data('price'));
          const itemTotal = price * quantity;

          total += itemTotal;

          selectedItems.push({
            id: id,
            quantity: quantity,
            price: price
          });
        });

        if (selectedItems.length === 0) {
          alert('Please select at least one product to checkout.');
          return;
        }

        $.ajax({
          url: '../API/create_order.php',
          type: 'POST',
          data: JSON.stringify({ items: selectedItems, total_amount: total }),
          contentType: 'application/json',
          dataType: 'json',
          success: function (response) {
            if (response.status === 'success') {
              window.location.href = 'checkout.php';
            } else {
              alert('Order creation failed.');
            }
          },
          error: function () {
            alert('Server error during checkout.');
          }
        });
      });
    });
  </script>
  <script>
    document.getElementById('logoutBtn').addEventListener('click', async () => {
      await fetch('../API/logout.php', { method: 'POST' });
      window.location.href = '../Public/login.php';
    });

    fetch('/API/get_cart_count.php')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('cartCount').innerText = data.count || 0;
                })
                .catch(err => console.error('Failed to fetch cart count:', err));
  </script>
</body>
</html>
