<?php
include 'common.php';
$hostname = "localhost";
$username = "root";
$password = "";
$database = "shoeshop";

$conn = new mysqli($hostname, $username, $password, $database);
$user_id = setUserSession();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$productId = $_GET['productId'];
$productSize = $_GET['cleanedSize'];


$sql = "DELETE FROM cart WHERE product_id = $productId AND user_id=$user_id AND size LIKE '%$productSize%'";

if ($conn->query($sql) === TRUE) {
    header("Location: cart.php");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}

// Close the database connection
$conn->close();
?>