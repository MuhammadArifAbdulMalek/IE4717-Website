<?php
include 'common.php';
$hostname = "localhost";
$username = "root";
$password = "";
$database = "shoeshop";

$conn = new mysqli($hostname, $username, $password, $database);
$user_id = setUserSession();
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
    <script>
        function validateaddtocart() {

        var quantity = document.getElementById("quantity").value;

        if (quantity <1) {
            alert("No quantity has been selected");
                return false;
        } else {
            return true;
        }
        }
    </script>
</head>
<body >
    <div class="promo">  
        <table>
            <tr>
               <td id="tabledata">Current Promotion</td>
             </tr>
        </table>
    </div>
    <nav class="navbar">
        <div class="navleft">
            <span>MEN</span>
            <span>WOMEN</span>
            <span>UNISEX</span>
        </div>
        <div class="navcenter">
            <span><a href="index.php"> Logo </a></span>
        </div>
        <div class="navright">
            <span><a href= "account.php"> <img src="assets/Images/Icons/account.png"> </a></span>
            <span><a href= "faq.php"> <img src="assets/Images/Icons/FAQ.png"> </a></span>
            <span><a href= "cart.php"> <img src="assets/Images/Icons/cart.png"> </a></span>
        </div>
    </nav>
    <div class="cart">
        <h1 style="padding-top:15px;"> Cart </h1>
        <?php
        $cartSql = "SELECT product_id, quantity, price, subtotal FROM cart WHERE user_id = '$user_id'";
        $cartResult = $conn->query($cartSql);
        $cartData = array();

        while ($row = $cartResult->fetch_assoc()) {
            $cartData[] = array(
                'product_id' => $row['product_id'],
                'quantity' => $row['quantity'],
                'price' => $row['price'],
                'subtotal' => $row['subtotal']
            );
        }
        $productDetails = array();

        // Loop through $cartData to fetch product details
        foreach ($cartData as $cartItem) {
            $product_id = $cartItem['product_id'];

            // Query to fetch product details based on product_id
            $productSql = "SELECT id, image_data, product_name, colourway FROM products WHERE id = '$product_id'";
            $productResult = $conn->query($productSql);

            if ($productResult && $productResult->num_rows > 0) {
                $productRow = $productResult->fetch_assoc();
                $productDetails[] = array(
                    'product_id' => $productRow['id'],
                    'product_name' => $productRow['product_name'],
                    'image_data' => $productRow['image_data'],
                    'colourway' => $productRow['colourway'],
                    'quantity' => $cartItem['quantity'],
                    'price' => $cartItem['price'],
                    'subtotal' => $cartItem['subtotal']
                );
            } else {
                echo "<h2>Your shopping cart is empty.</h2>";
            }
        }

        // Add buttons for updating the cart or proceeding to checkout
        echo "<a href='update_cart.php'>Update Cart</a> | <a href='checkout.php'>Proceed to Checkout</a>";
        ?>
        <div class="displaycart">
            <table class="displayproducts">
                <?php
                $total = 0 ;
                foreach ($productDetails as $product) {
                    $product_id = $product['product_id'];
                    $product_name = $product['product_name'];
                    $image_data = $product['image_data'];
                    $colourway = $product['colourway'];
                    $quantity = $product['quantity'];
                    $price = $product['price'];
                    $subtotal = $product['subtotal'];
                    $total += $subtotal;
                    echo '<tr>';
                    echo '<td id="cartimagecolumn"><a class="cartimage" href=""><img src="data:image/jpeg;base64,' . base64_encode($image_data) . '"></td>';
                    echo '<td id="cartdetailscolumn">';
                    echo '<h2 style="font-size:30px;">' . $product_name . '</h2>';
                    echo '<p style="font-size:20px;">Colour: ' . " " . $colourway . '</p>';
                    echo '<p style="font-size:20px;">Quantity: ' . " " . $quantity . '</p>';
                    echo '<p> Subtotal: $' . $subtotal . '</p>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
        <div class="cartcheckoutbuttons">
                <form action="updatecart.php"> method=
        </div>
        <h2> Total: $ <?php echo $total ?> </h2>
        <div class="footer">
            <div class="footerupper">
                <div class="sitemap">
                    <a style="font-size: 25px; text-decoration: underline;"> <strong>Quick Directory </strong> </a> <br>
                    <table class = sitemaplinks>
                        <tr>
                            <td> <a> Link 1</a> </td>
                            <td> <a> Link 2</a> </td>
                        </tr>
                        <tr>
                            <td> <a> Link 1</a> </td>
                            <td> <a> Link 2</a> </td>
                        </tr>
                    </table>
                    
                </div>
                <div class="socialmedia">
                    <a>facebook</a>
                    <a>instagram</a>
                    <a>youtube</a>
                </div>
            </div>
            <div class="copyright">
                <a> 2023 ShoeShoe Singapore Ltd</a>
            </div>
        </div>
        <?php
       
       if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $sizes) {
            foreach ($sizes as $product_size => $product) {
                echo "<li>{$product['name']} - \${$product['price']} (Quantity: {$product['quantity']})(Size: {$product_size}, Color: {$product['color']})</li>";
                
                    
                
            }
        }
        
    }
            
        ?>
        <div id="cart-overlay" class="cart-overlay">
        <div class="cart-content">
            <span class="close-button" id="close-cart">X</span>
            <h2>Your Cart</h2>
            <ul id="cart-items">
                <!-- Cart items will be dynamically added here -->
            </ul>
        </div>
    </div>
    </div>
</body>
</html>