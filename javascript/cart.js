document.addEventListener('DOMContentLoaded', () => {

    function recalculateTotal() {
      let total = 0;
      document.querySelectorAll('.product-checkbox:checked').forEach(checkbox => {
        const price = parseFloat(checkbox.dataset.price);
        const quantity = parseInt(document.querySelector(`.quantity-input[data-product-id="${checkbox.value}"]`).value);
        total += price * quantity;
      });
      document.getElementById('total-price').textContent = '₱' + total.toFixed(2);
    }
  
    // Update quantity in backend
    document.querySelectorAll('.update-btn').forEach(button => {
      button.addEventListener('click', async () => {
        const productId = button.dataset.id;
        const input = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
        const newQty = parseInt(input.value);
  
        const response = await fetch('../API/update_cart.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ product_id: productId, quantity: newQty })
        });
  
        const result = await response.json();
        if (result.status === 'success') {
          const price = parseFloat(input.dataset.price);
          const totalCell = input.closest('tr').querySelector('.item-total');
          totalCell.textContent = '₱' + (price * newQty).toFixed(2);
          alert('Quantity updated');
          recalculateTotal();
        } else {
          alert('Failed to update quantity');
        }
      });
    });
  
    // Delete from cart
    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', async () => {
        const productId = button.dataset.id;
  
        const response = await fetch('../API/delete_cart_item.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ product_id: productId })
        });
  
        const result = await response.json();
        if (result.status === 'success') {
          document.getElementById(`item-${productId}`).remove();
          alert('Item deleted');
          recalculateTotal();
        } else {
          alert('Failed to delete item');
        }
      });
    });
  
    // Recalculate when checkbox or quantity changes
    document.querySelectorAll('.product-checkbox, .quantity-input').forEach(el => {
      el.addEventListener('change', recalculateTotal);
    });
  
  });
  