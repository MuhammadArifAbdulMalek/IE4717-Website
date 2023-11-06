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

$myName = $_POST['myName'];
$myEmail = $_POST['myEmail'];
$myphone = $_POST['myphone'];
$myaddress = $_POST['myaddress'];
if (isset($_POST['myaddress2'])){
    $myaddress2 = $_POST['myaddress2'];
} else {
    $myaddress2 = "";
}
$mycity = $_POST['mycity'];
$mypostalcode = $_POST['mypostalcode'];
$mybillingaddress = $_POST['mybillingaddress'];
if (isset($_POST['mybillingaddress2'])){
    $mybillingaddress2 = $_POST['mybillingaddress2'];
}else {
    $mybillingaddress2 = "";
}
$mybillingcity = $_POST['mybillingcity'];
$mybillingpostalcode = $_POST['mybillingpostalcode'];

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


foreach ($order_data as $order) {
    $insertQuery = "INSERT INTO confirmedorder (user_id, product_id, size, quantity, price, subtotal,name,email,phonenumber,address1,address2,addresscity,addresspostalcode,billingaddress1,billingaddress2,billingcity,billingpostalcode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sisdddsssssssssss", $user_id, $order['product_id'], $order['size'], $order['quantity'], $order['price'], $order['subtotal'],$myName,$myEmail,$myphone,$myaddress,$myaddress2,$mycity,$mypostalcode,$mybillingaddress,$mybillingaddress2,$mybillingcity,$mybillingpostalcode  );
    $stmt->execute();
    $stmt->close();
   
    $column = $order['size'];
    $productID = $order['product_id'];
    $sales = $order['quantity'];
    $query = "SELECT `$column` FROM inventory WHERE id = ?";
    $stmt4 = $conn->prepare($query);
    $stmt4->bind_param("i", $productID);
    $stmt4->execute();
    $result = $stmt4->get_result();
    $row = $result->fetch_assoc();
    $currentQuantity = $row[$column];
    $stmt4->close();

    // Calculate the new quantity after the order
    $newQuantity = $currentQuantity - $sales;

    // Update the inventory with the new quantity
    $updateInventoryQuery = "UPDATE inventory SET `$column` = ?, sales = sales + ?  WHERE id = ?";
    $stmt3 = $conn->prepare($updateInventoryQuery);
    $stmt3->bind_param("iii", $newQuantity, $sales, $order['product_id']);
    $stmt3->execute();
    $stmt3->close();

    // Delete items from the user's cart
    $deleteCartQuery = "DELETE FROM cart WHERE user_id = ? AND product_id = ? AND size = ?";
    $stmt2 = $conn->prepare($deleteCartQuery);
    $stmt2->bind_param("sii", $user_id, $order['product_id'], $order['size']);
    $stmt2->execute();
    $stmt2->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<title>Thank You</title>
<link rel="stylesheet" href="styles.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,600&family=Lato:wght@700&display=swap" rel="stylesheet">

</head>
<body>
    <div class="thankyoucontainer">
        <h1> Thank you for your purchase </h1>
        <p> Your items will be delivered promptly. </p>
        <a href="index.php"> <button class="checkoutbutton"> Go to Home </button> </a>
        <p><small style="float:bottom;"> 2023 Shoeshop Singapore Pte Ltd <small> </p>
    </div>
</body>
</head>
</html>
