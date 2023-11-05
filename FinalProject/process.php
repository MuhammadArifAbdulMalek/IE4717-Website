<?php
session_start();

if (isset($_POST['addtocart'])) {
    $product_id = $_POST['id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['price'];
    $product_size = $_POST['size'];
    $product_quantity = $_POST['quantity'];
    $product_color = $_POST['colorway'];

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id][$product_size])) {
        // Increment the quantity if it's already in the cart
        $_SESSION['cart'][$product_id][$product_size]['quantity']++;
        
    } else {
        // Add the product to the cart
        $_SESSION['cart'][$product_id][$product_size] = [
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $product_quantity,
            'size' => $product_size,
            'color' => $product_color,
        ];
        echo '<script>alert("Added to Cart");</script>';
    }

    // Redirect back to the product listing
    header('Location: product_list.php');
    exit();
}
?>
