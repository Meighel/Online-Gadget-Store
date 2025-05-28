// Sample cart data (this should be fetched from the server)
const cartItems = [
    { id: 1, name: "Product 1", price: 29.99, quantity: 2 },
    { id: 2, name: "Product 2", price: 19.99, quantity: 1 },
    { id: 3, name: "Product 3", price: 39.99, quantity: 1 },
];

// Function to render cart items
function renderCartItems() {
    const cartItemsContainer = document.getElementById('cart-items');
    cartItemsContainer.innerHTML = ''; // Clear existing items
    let totalPrice = 0;

    cartItems.forEach(item => {
        const total = item.price * item.quantity;
        totalPrice += total;

        const row = `
            <tr id="item-${item.id}">
                <td>${item.name}</td>
                <td>$${item.price.toFixed(2)}</td>
                <td>
                    <input type="number" value="${item.quantity}" min="1" class="form-control" onchange="updateQuantity(${item.id}, this.value)">
                </td>
                <td class="item-total" data-price="${item.price}">$${total.toFixed(2)}</td>
                <td><button class="btn btn-danger" onclick="removeItem(${item.id})">Remove</button></td>
            </tr>
        `;
        cartItemsContainer.innerHTML += row;
    });

    document.getElementById('total-price').innerText = `$${totalPrice.toFixed(2)}`;
}

// Function to update quantity// Function to update quantity
async function updateQuantity(productId, quantity) {
    if (quantity < 1) {
        alert("Quantity must be at least 1.");
        return;
    }
    // Update the total price for this item
    const itemRow = document.getElementById(`item-${productId}`);
    const price = parseFloat(itemRow.querySelector('.item-total').dataset.price);
    const newTotal = price * quantity;
    itemRow.querySelector('.item-total').innerText = `$${newTotal.toFixed(2)}`;
    // Recalculate the total price
    calculateTotalPrice();
}

// Function to remove item
// async function removeItem(productId) {
//     if (confirm('Are you sure you want to remove this item from the cart?')) {
//         try {
//             const response = await fetch(`/API/delete_cart_item.php`, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json'
//                 },
//                 body: JSON.stringify({ product_id: productId })
//             });

//             const result = await response.json();
//             if (!result.success) {
//                 alert(result.message);
//             } else {
//                 alert(`Product ${productId} removed from cart!`);
//                 // Remove the item row from the table
//                 const itemRow = document.getElementById(`item-${productId}`);
//                 itemRow.remove();

//                 // Remove the item from the cartItems array
//                 const index = cartItems.findIndex(item => item.id === productId);
//                 if (index > -1) {
//                     cartItems.splice(index, 1); // Remove the item from the array
//                 }

//                 // Recalculate the total price
//                 calculateTotalPrice();
//             }
//         } catch (error) {
//             alert(`Error: ${error.message}`);
//         }
//     }
// }

// Function to calculate total price
function calculateTotalPrice() {
    const itemTotals = document.querySelectorAll('.item-total');
    let total = 0;
    itemTotals.forEach(item => {
        total += parseFloat(item.innerText.replace('$', ''));
    });
    document.getElementById('total-price').innerText = `$${total.toFixed(2)}`;
}

// Initial render
renderCartItems();
