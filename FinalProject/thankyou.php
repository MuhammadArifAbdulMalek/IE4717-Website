<?php
include 'common.php';
$hostname = "localhost";
$username = "root";
$password = "";
$database = "shoeshop";
    
$conn = new mysqli($hostname, $username, $password, $database);

$user_id = setUserSession();

$product_ids = $_POST['product_id'];
$product_names = $_POST['product_name'];
$colourways = $_POST['colourway'];
$sizes = $_POST['size'];
$prices = $_POST['price'];
$quantities = $_POST['quantity'];
$subtotals = $_POST['subtotal'];

$order_data = [];

for ($i = 0; $i < count($product_ids); $i++) {
    $order_data[] = [
        'product_id' => $product_ids[$i],
        'product_name' => $product_names[$i],
        'colourway' => $colourways[$i],
        'size' => $sizes[$i],
        'price' => $prices[$i],
        'quantity' => $quantities[$i],
        'subtotal' => $subtotals[$i],
    ];
}

$insertQuery = "INSERT INTO confirmedorder (user_id, product_id, size, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insertQuery);
foreach ($order_data as $order) {
        $stmt->bind_param("sisidd", $user_id, $order['product_id'],$order['size'], $order['quantity'], $order['price'], $order['subtotal']);
        $stmt->execute();
        }
    $stmt->close();


$deleteQuery = "DELETE FROM cart WHERE user_id = ?";
$stmt2 = $conn->prepare($deleteQuery);
$stmt2->bind_param("s", $user_id);
$stmt2->execute();
$stmt2->close();

$conn->close();
?>

