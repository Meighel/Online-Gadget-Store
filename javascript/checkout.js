document.getElementById('checkoutForm').addEventListener('submit', function(event) {
    // Perform validation
    const address = document.getElementById('shippingAddress').value;
    if (!address) {
        alert('Please enter your shipping address.');
        event.preventDefault(); // Prevent form submission
        return;
    }

    // Optionally, you can show a loading spinner here
    // document.getElementById('loadingSpinner').style.display = 'block';
});

// Function to calculate total price if discounts are applied
function applyDiscount() {
    const discountCode = document.getElementById('discountCode').value;
    // Assume a fixed discount for demonstration
    let discount = 0;
    if (discountCode === 'SAVE10') {
        discount = 10; // $10 discount
    }
    const totalPriceElement = document.getElementById('total-price');
    const currentTotal = parseFloat(totalPriceElement.innerText.replace('$', ''));
    const newTotal = currentTotal - discount;
    totalPriceElement.innerText = `$${newTotal.toFixed(2)}`;
}
