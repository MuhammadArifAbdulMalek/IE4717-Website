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
$quantities = $_POST['quantity'];
$prices = $_POST['price'];
$total = 0;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput.min.js"></script>
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
            <a style="text-decoration:none; color:black;" href="product_list.php"><span>MEN</span></a>
            <a style="text-decoration:none; color:black;" href="product_list2.php"><span>WOMEN</span></a>
            <a style="text-decoration:none; color:black;" href="product_list.php"><span>UNISEX</span></a>
        </div>
        </div>
        <div class="navcenter">
            <span><a href="index.php"> SHOESHOE </a></span>
        </div>
        <div class="navright" >
            <span style="margin:0px;">
                <?php if (isset($_SESSION['first_name'])): ?>
                    <div class="dropdown" style="width: 110px; position: relative;">
                        <div class="dropdownbar" style="text-align:left; position: relative; display: inline-block; font-size: 90%;">                        
                                <label for=user-account>Hi, <?php echo $_SESSION['first_name']; ?></label>
                                <div class="dropdown-content" style="text-align:right; display: none; position: absolute; background-color: white; padding: 10px; top: 100%; right: 0; z-index: 1;">
                                    <a href="logout.php?return_url=<?php echo urlencode($_SERVER['REQUEST_URI']);?>">Logout</a>
                                </div>
                        </div>
                    </div>
                <?php else: ?>
                    <span><a href="account.php"><img src="assets/Images/Icons/account.png"></a></span>
                <?php endif; ?>
            </span>
            <span><a href= "faq.php"> <img src="assets/Images/Icons/FAQ.png"> </a></span>
            <span><a href= "cart.php"> <img src="assets/Images/Icons/cart.png"> </a></span>
        </div>
    </nav>

    <form action="thankyou.php" method="post" id="confirmation">
    <div class="checkoutcontainer"> 
        <div class="customerinformationcontainer">
        <div class="customerinformation">
            <div class="infoneeded">
                <h2> Billing Information </h2>
                    <label for=myName>Name: </label><label class="orderheader">* </label> <br>
                    <input type="text" name="myName" size="25" id="myName" oninput="checkName()"><br>
                    <label for="myEmail">Email:</label><label class="orderheader">* </label><br>
                    <input type="text" name="myEmail" size="25" id="myEmail" oninput="checkEmail()"><br>  
                    <label for="myphone">Phone: +65</label><label class="orderheader">* </label><br>
                    <input type="tel" id="phone" name="myphone" class="intl-tel-input" required><br>
                    <label for="myaddress">Delivery Address:</label><label class="orderheader">* </label><br>
                    <input type="text" name="myaddress" size="25" id="myaddress"  required><br>  
                    <label for="myaddress2">Delivery Address Line 2:</label><br>
                    <input type="text" name="myaddress2" size="25" id="myaddress2"><br>  
                    <label for="mycity">City:</label><label class="orderheader">* </label><br>
                    <input type="text" name="mycity" size="25" id="mycity" required><br>
                    <label for="mypostalcode">Postal Code:</label><label class="orderheader">* </label><br>
                    <input type="text" name="mypostalcode" size="25" id="mypostalcode" required><br>
                
                <h2> Payment </h2>
                <label for="mybillingaddress">Biling Address:</label><label class="orderheader">* </label><br>
                <input type="text" name="mybillingaddress" size="25" id="mybillingaddress"  required><br>
                <label for="mybillingaddress2">Billing Address Line 2:</label><br>
                <input type="text" name="mybillingaddress2" size="25" id="mybillingaddress2"><br>
                <label for="mybillingcity">City:</label><label class="orderheader">* </label><br>
                <input type="text" name="mybillingcity" size="25" id="mybillingcity" required><br>
                <label for="mybillingpostalcode">Postal Code:</label><label class="orderheader">* </label><br>
                <input type="text" name="mybillingpostalcode" size="25" id="mybillingpostalcode" required><br>   
            </div>
            <div class="orderbutton">
            <button class="checkoutbutton" type="submit" style="margin-top:10px; margin-left:20px;">Order Now</button>
            </div>
        </div>
        </div>
        <div class="cartorders">
            <h2 style="text-align:center; padding-top:5vh;"> In Your Cart </h2>
            <div class="cartdisplayitems">
            <table class="displayproductsorder">
                <?php
                    for ($i = 0; $i < count($product_ids); $i++) {
                        
                        $product_id = $product_ids[$i];
                        $product_name = $product_names[$i];
                        $colourway= $colourways[$i];
                        $size = $sizes[$i];
                        $quantity = $quantities[$i];
                        $price = $prices[$i];
                        $subtotal = $quantity * $price;
                        $total +=  $subtotal;

                        $sql = "SELECT image_data FROM products WHERE id = $product_id";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $image_data = $row['image_data'];


                        echo '<tr>';
                        echo '<td id="cartimagecolumn"><a class="cartimage" href=""><img src="data:image/jpeg;base64,' . base64_encode($image_data) . '"></td>';
                        echo '<td id="cartdetailscolumn">';
                        echo '<h2 style="font-size:30px;">' . $product_name . '</h2>';
                        echo '<p style="font-size:20px;">Colour: ' . " " . $colourway . '</p>';
                        echo '<p style="font-size:20px;">Size: ' . " " . $size . '</p>';
                        echo '<p style="font-size:20px;">Quantity: ' . " " . $quantity . '</p>';
                        echo '<p style="font-size:20px;">Subtotal: $ ' . " " . $subtotal . '</p>';
                        echo '</td>';
                        echo '</tr>';
                        echo '<input type="hidden" name="product_id[]" value="'. $product_id .'">';
                        echo '<input type="hidden" name="product_name[]" value="'. $product_name .'">';
                        echo '<input type="hidden" name="colourway[]" value="'. $colourway .'">';
                        echo '<input type="hidden" name="size[]" value="'. $size .'">';
                        echo '<input type="hidden" name="price[]" value="'. $price .'">';
                        echo '<input type="hidden" name="quantity[]" value="'. $quantity .'">';
                        echo '<input type="hidden" name="subtotal[]" value="'. $subtotal .'">';
                    }
                    echo '<input type="hidden" name="total[]" value="'. $total .'">';
                    
                ?>
            </table>
            </div>
            <div class="orderreturn">
            <h2 id="total" style="text-align:center">Total: $ <?php echo $total ?></h2>
            <a href="cart.php" class="button-link">Back to Cart</a>
            </div>
        </div>
    </div>
    </form>
    <div class="footer">
        <div class="footerupper">
            <div class="sitemap">
                <a style="font-size: 25px; text-decoration: underline;"> <strong>Quick Directory </strong> </a> <br>
                <table class = sitemaplinks>
                    <tr>
                        <td> <a> Size Guide</a> </td>
                        <td> <a> T&Cs</a> </td>
                    </tr>
                    <tr>
                        <td> <a> Contact Us</a> </td>
                        <td> <a> Privacy Policy</a> </td>
                    </tr>
                </table>
                    
            </div>
            <div class="socialmedia">
                <a><img src="assets/Images/Icons/facebook.png"></a>
                <a><img src="assets/Images/Icons/instagram.png"></a>
                <a><img src="assets/Images/Icons/tiktok.png"></a>
            </div>
        </div>
        <div class="copyright">
            <a> 2023 ShoeShoe Singapore Ltd</a>
        </div>
    </div>
</body>
</html>
<script>
  var input = document.querySelector("#phone");
  window.intlTelInput(input, {
    initialCountry: "auto",
  });
</script>