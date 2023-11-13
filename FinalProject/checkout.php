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
for ($i = 0; $i < count($product_ids); $i++) {
                        
    $product_id = $product_ids[$i];
    $product_name = $product_names[$i];
    $colourway= $colourways[$i];
    $size = $sizes[$i];
    $quantity = $quantities[$i];
    $price = $prices[$i];
    $subtotal = $quantities[$i] * $prices[$i];
    $total +=  $subtotal;
$sql = "SELECT image_data FROM products WHERE id = $product_id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$image_data = $row['image_data'];

$updatesql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ? AND size = ?";
$updatestmt = $conn->prepare($updatesql);

$updatestmt->bind_param("iiis", $quantity, $user_id, $product_id, $size);
$updatestmt->execute();
$updatestmt->close();

if ($quantity == 0){
$emptysql = "DELETE FROM cart WHERE user_id = ? AND product_id = ? AND size = ?";
$emptystmt = $conn->prepare($emptysql);

$emptystmt->bind_param("iis", $user_id, $product_id, $size);
$emptystmt->execute();
$emptystmt->close();
}

$countSql = "SELECT COUNT(*) AS instanceCount FROM cart WHERE user_id = ?";
                    $countStmt = $conn->prepare($countSql);
                    $countStmt->bind_param("i", $user_id);
                    $countStmt->execute();
                    $countStmt->bind_result($instanceCount);
                    $countStmt->fetch();
                    $countStmt->close();

}
if (!isset($_SESSION['promocodeapplied'])){
    $_SESSION['promocodeapplied'] = 0;
}


if (isset($_POST['promocodeaddition'])) {
    if (isset($_POST['promocode'])) {
        $promocode = $_POST['promocode'];
        
        if ($promocode == $_SESSION['promocodeapplied']) {
            echo '<script>alert("Promo Code has been applied previously");</script>';
        } else {
            // Use prepared statements to avoid SQL injection
            $promocodesql = "SELECT pricecut FROM promocode WHERE promocode = ?";
            $stmt = $conn->prepare($promocodesql);
            $stmt->bind_param("s", $promocode);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $promoCodeRow = $result->fetch_assoc();
                $priceCut = $promoCodeRow['pricecut'];
                $quantities = $_POST['quantity'];
                $prices = $_POST['price'];
                $_SESSION['promocodeapplied'] = $promocode;
                echo '<script>alert("'.$promocode.' has been applied");</script>';
                for ($i = 0; $i < count($prices); $i++) {
                    $prices[$i] = $prices[$i] * $priceCut;
                }
            } else {
                echo '<script>alert("Promo Code Invalid");</script>';
            }
        }
    }
}



    

?>


<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,600&family=Lato:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput.min.js"></script>
    
    
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
            <span><a href="index.php"> <img src="assets/Images/logo.png"> </a></span>
        </div>
        <div class="navright" >
            <span style="margin:0px;">
                <?php if (isset($_SESSION['first_name'])): ?>
                    <?php if ($_SESSION['admin'] == 1): ?>
                        <div class="dropdown" style="width: 110px; position: relative;">
                            <div class="dropdownbar" style="text-align:left; position: relative; display: inline-block; font-size: 90%;">                                      
                                    <label for=user-account>Hi, <?php echo $_SESSION['first_name']; ?></label>
                                    <div class="dropdown-content" style="text-align:right; display: none; position: absolute; background-color: white; padding: 10px; top: 100%; right: 0; z-index: 1;">
                                    <a href="logout.php?return_url=<?php echo urlencode('index.php');?>">Logout</a>
                                    <a href="admin.php">Admin</a>
                                    <a href="orderhistory.php" style="text-align:left">Order History</a>
                                    </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="dropdown" style="width: 110px; position: relative;">
                            <div class="dropdownbar" style="text-align:left; position: relative; display: inline-block; font-size: 90%;">                                      
                                    <label for=user-account>Hi, <?php echo $_SESSION['first_name']; ?></label>
                                    <div class="dropdown-content" style="text-align:right; display: none; position: absolute; background-color: white; padding: 10px; top: 100%; right: 0; z-index: 1;">
                                    <a href="logout.php?return_url=<?php echo urlencode('index.php');?>">Logout</a>
                                    <a href="orderhistory.php" style="text-align:left">Order History</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <span><a href="account.php"><img src="assets/Images/Icons/account.png"></a></span>
                <?php endif; ?>
            </span>
            <span><a href= "faq.php"> <img src="assets/Images/Icons/FAQ.png"> </a></span>
            <span><a href= "cart.php"> <img src="assets/Images/Icons/cart.png"> </a></span>
        </div>
    </nav>

    <?php if($instanceCount != 0){
     echo '<form action="thankyou.php" method="post" id="confirmation">';
     echo '<div class="checkoutcontainer">';
        echo '<div class="customerinformationcontainer">';
        echo '<div class="customerinformation">';
             echo '<div class="infoneeded">';
                echo '<h2> Billing Information </h2>';
                    echo '<label for=myName>Name: </label><label class="orderheader">* </label> <br>';
                    echo '<input type="text" name="myName" size="25" id="myName" pattern="[A-Za-z\s]+" required title="Please enter only alphabets" required><br>';
                 
                    echo '<label for="myEmail">Email:</label><label class="orderheader">* </label><br>';
                    echo '<input type="text" name="myEmail" size="25" id="myEmail" pattern="/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/" required title="Please enter following example (hello@domain.sg)" required><br>';
         
                    echo '<label for="myphone">Phone: +65</label><label class="orderheader">* </label><br>';
                    echo '<input type="tel" id="phone" name="myphone" class="intl-tel-input" required><br>';
  
                    echo '<label for="myaddress">Delivery Address:</label><label class="orderheader">* </label><br>';
                    echo '<input type="text" name="myaddress" size="25" id="myaddress"  required><br>  ';
         
                    echo '<label for="myaddress2">Delivery Address Line 2:</label><br>';
                    echo '<input type="text" name="myaddress2" size="25" id="myaddress2"><br>  ';
             
                    echo '<label for="mycity">City:</label><label class="orderheader">* </label><br> ';
                    echo '<input type="text" name="mycity" size="25" id="mycity"  required><br> ';

                    echo '<label for="mypostalcode">Postal Code:</label><label class="orderheader">* </label><br>';
                    echo '<input type="text" name="mypostalcode" size="25" id="mypostalcode"  pattern="[0-9]+" required title="Please enter a valid number (digits only)" required><br>';
                
                
                    echo '<h2> Payment </h2>';
                    echo '<label for="mybillingaddress">Biling Address:</label><label class="orderheader">* </label><br>';
                    echo '<input type="text" name="mybillingaddress" size="25" id="mybillingaddress"  required><br>';
      
                    echo '<label for="mybillingaddress2">Billing Address Line 2:</label>';
                    echo '<input type="text" name="mybillingaddress2" size="25" id="mybillingaddress2"><br>';
             
                    echo '<label for="mybillingcity">City:</label><label class="orderheader">* </label><br>';
                    echo '<input type="text" name="mybillingcity" size="25" id="mybillingcity" required><br>';
         
                    echo '<label for="mybillingpostalcode">Postal Code:</label><label class="orderheader">* </label><br>';
                    echo '<input type="text" name="mybillingpostalcode" size="25" id="mybillingpostalcode" pattern="[0-9]+" required title="Please enter a valid number (digits only)" required><br>   ';
         
                
                    for ($i = 0; $i < count($product_ids); $i++) {
                        $product_id = $product_ids[$i];
                        $product_name = $product_names[$i];
                        $colourway= $colourways[$i];
                        $size = $sizes[$i];
                        $quantity = $quantities[$i];
                        $price = $prices[$i];
                        $subtotal = $quantities[$i] * $prices[$i];

                        echo '<input type="hidden" name="product_id[]" value="'. $product_id .'">';
                        echo '<input type="hidden" name="product_name[]" value="'. $product_name .'">';
                        echo '<input type="hidden" name="colourway[]" value="'. $colourway .'">';
                        echo '<input type="hidden" name="size[]" value="'. $size .'">';
                        echo '<input type="hidden" name="price[]" value="'. $price .'">';
                        echo '<input type="hidden" name="quantity[]" value="'. $quantity .'">';
                        echo '<input type="hidden" name="subtotal[]" value="'. $subtotal .'">';
                        }
                    
               
            echo '</div>';
            echo '<div class="orderbutton">';
            echo '<button class="checkoutbutton" type="submit" style="margin-top:10px; margin-left:20px;">Order Now</button>';
            echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</form>';
                    }?>
        <div class="cartorders">
            <?php if($instanceCount != 0) {
             echo '<h2 style="text-align:center; padding-top:5vh;"> In Your Cart </h2>';
            }
            ?>
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
                            $subtotal = $quantities[$i] * $prices[$i];
                            $total +=  $subtotal;
                        $sql = "SELECT image_data FROM products WHERE id = $product_id";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $image_data = $row['image_data'];
                        
                         if ($quantity != 0) {


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
                        
                        
                    } 
                    
                    
                    }

                    
                    

                    echo '<input type="hidden" name="total[]" value="'. $total .'">';
                    
                ?>
            </table>
            </div>
            <div class="orderreturn">
            <div style="padding-top:20px; ">
            <?php if($instanceCount != 0){
            echo '<form action="" method="post" id="promocodeaddition" name="promocodeaddition" style="display: flex; align-items: center; justify-content: center;">';
            echo '<label for=promocode>Promo Code: </label>';
            echo '<input type="text" name="promocode" size="25" id="promocode" style="margin-left:20px;"><br>';
            
             for ($i = 0; $i < count($product_ids); $i++) {
            echo '<input type="hidden" name="product_id[]" value="'. $product_id .'">';
                        echo '<input type="hidden" name="product_name[]" value="'. $product_name .'">';
                        echo '<input type="hidden" name="colourway[]" value="'. $colourway .'">';
                        echo '<input type="hidden" name="size[]" value="'. $size .'">';
                        echo '<input type="hidden" name="price[]" value="'. $price .'">';
                        echo '<input type="hidden" name="quantity[]" value="'. $quantity .'">';
             }
                        
             echo ' <button class="checkoutbutton" id="promocodeaddition" name="promocodeaddition" type="submit" style="margin-top:10px; margin-left:20px;">Apply</button>';
             echo '</form>';
             echo '</div>';
             echo '<h2 id="total" style="text-align:center">Total: $ '.$total.' </h2>';

             echo '<a href="cart.php" id="backToCartLink" class="button-link">Back to Cart</a>';
            } 

            ?>
            <script>
        
             document.getElementById("backToCartLink").addEventListener("click", function (event) {
 
            event.preventDefault();

            var form = document.createElement("form");
            form.action = "cart.php"; // Set the target URL
            form.method = "post";

            
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "backtocart";

   
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        });
    </script>
            </div>
        </div>

    
    </div>
    <?php
    if ($instanceCount == 0) {
        echo '<div style="text-align:center; height:51vh;">';
        echo '<p style="font-size:40px; text-align:center"><strong>Your Cart is Empty</strong></p>';
        echo '<a href="index.php" id="backToCartLink" class="button-link">Add Items</a>';
        echo '</div>';
    }
    ?>
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

        <script>
                    // Select all dropdown bars and checkbox forms
        const dropdownBars2 = document.querySelectorAll(".dropdownbar");
        const dropdownContent = document.querySelectorAll(".dropdown-content");

        // Add a click event listener to each dropdown bar
        dropdownBars2.forEach((dropdownBar, index) => {
            dropdownBar.addEventListener("click", () => {
                const form = dropdownContent[index];
                if (form.style.display === "none" || form.style.display === "") {
                    form.style.display = "block";
                } else {
                    form.style.display = "none";
                }
            });
        });
        </script>
        
</body>
</html>
<script>
  var input = document.querySelector("#phone");
  window.intlTelInput(input, {
    initialCountry: "auto",
  });
</script>