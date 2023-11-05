<?php
session_start();

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo "<h2>Shopping Cart</h2>";
    foreach ($_SESSION['cart'] as $product_id => $product) {
        echo "<li>{$product['name']} - \${$product['price']} (Quantity: {$product['quantity']})(Size: {$product_size}, Color: {$product['color']})</li>";
    }
} else {
    echo "<h2>Your shopping cart is empty.</h2>";
}

// Add buttons for updating the cart or proceeding to checkout
echo "<a href='update_cart.php'>Update Cart</a> | <a href='checkout.php'>Proceed to Checkout</a>";
?>