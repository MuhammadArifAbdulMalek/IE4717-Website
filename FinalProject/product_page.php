<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "javajam";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];

    echo "Thank you for providing your details:<br>";
    echo "Name: " . $name . "<br>";
    echo "Email: " . $email . "<br>";
} else {
    echo "Invalid request!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,600&family=Lato:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="promo">  
        <table>
            <tr>
               <td id="tabledata">Current Promotion</td>
             </tr>
        </table>
    </div>
    <nav class="navbar">
        <div class="navleft">
            <span>Link 1</span>
            <span>Link 2</span>
            <span>Link 3</span>
            <span>Link 4</span>
        </div>
        <div class="navcenter">
            <span>Logo</span>
        </div>
        <div class="navright">
            <span>Link 1</span>
            <span>Link 2</span>
            <span>Link 3</span>
            <span>Search</span>
        </div>
    </nav>
    <div class="productpagecontainer">
        <div class="productpageimages">

        </div>
        <div class="productpagedetails">
            
        </div>
    </div>
</body>
</html>


