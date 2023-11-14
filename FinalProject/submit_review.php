<?php
include 'common.php';
$hostname = "localhost";
$username = "root";
$password = "";
$database = "shoeshop";

$conn = new mysqli($hostname, $username, $password, $database);
$user_id = setUserSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $rating = $_POST['rating'];
  $product_name = $_POST['product_name'];
  $colourway = $_POST['colourway'];
  $size = $_POST['size'];
  $datetime = $_POST['orderdate'];

  // Save the rating to your database or perform any other necessary actions

  $sql = "SELECT id, noofreviews, averagerating FROM products WHERE product_name = ? AND colourway = ?";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $product_name, $colourway);

  $stmt->execute();
  $result = $stmt->get_result();

  
  if ($row = $result->fetch_assoc()) {
        $product_id = $row['id'];
        $noofreviews = $row['noofreviews'];
        $averagerating = $row['averagerating'];
    }
    $stmt->close();
    $totalrating = $noofreviews * $averagerating;
    $totalrating += $rating;
    $noofreviews += 1;
    $averagerating = $noofreviews > 0 ? $totalrating / $noofreviews : 0;

    $updatesql = "UPDATE products SET noofreviews = ?, averagerating = ? WHERE product_name = ? AND colourway = ?";

    $updatestmt = $conn->prepare($updatesql);
    $updatestmt->bind_param("idss", $noofreviews, $averagerating, $product_name, $colourway);

    $updatestmt->execute();

    $updatestmt->close();
    
    $usersql = "UPDATE confirmedorder SET rating = ? WHERE product_id = ? AND size = ? AND user_id = ? AND datetime = ?";

    $userstmt = $conn->prepare($usersql);
    $userstmt->bind_param("iisss", $rating, $product_id, $size, $user_id, $datetime);

    $userstmt->execute();

    $userstmt->close();
}
header("Location: orderhistory.php");
exit();

?>