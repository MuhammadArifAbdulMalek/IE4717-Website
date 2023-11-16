<?php
include 'common.php';
$hostname = "localhost";
$username = "root";
$password = "";
$database = "shoeshop";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$user_id = setUserSession();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Shop - Product Listing</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,600&family=Lato:wght@700&display=swap" rel="stylesheet">
    <script src="scripts.js" defer></script>
    <script type="text/JavaScript">
    var message="Current Promotions Latest News Get it Here! Promo Code 15OFF for 15% off your next purchase.";
    var space=" ";
    var position=0;
    function scroller(){
         var newtext = space + message.substring(position,message.length) + space + message.substring(0,position);
         var td = document.getElementById("tabledata");
         td.firstChild.nodeValue = newtext;
         position++;
         if (position > message.length){position=0;}
         window.setTimeout("scroller()",200);
    }
 
 </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.4.0/nouislider.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.4.0/nouislider.min.js"></script>

    <script>
        var registrationSuccess = <?php echo $registrationSuccess ? 'true' : 'false'; ?>;
        var registrationMessage = "<?php echo $registrationMessage; ?>";
        var loginSuccess = <?php echo $loginSuccess ? 'true' : 'false'; ?>;
        var loginMessage = "<?php echo $loginMessage; ?>";

        function showMessage() {
            if (registrationSuccess) {
                alert("Account created successfully.");
            } else if (registrationMessage) {
                alert(registrationMessage);
            }
            if (loginSuccess) {
                alert("Login successful.");
            } else if (loginMessage) {
                alert(loginMessage);
            }
        }

        showMessage(); // Call the function on page load
    
    </script>

    <script>

        document.addEventListener("DOMContentLoaded", function () {
            var stars = document.querySelectorAll('.rating input[type="radio"]');
            
            for (var i = 0; i < stars.length; i++) {
                stars[i].addEventListener('change', function () {
                    fillPreviousStars(this);
                });
            }
        });

        function fillPreviousStars(selectedStar) {
            var stars = document.querySelectorAll('.rating input[type="radio"]');
            var selectedIndex = Array.from(stars).indexOf(selectedStar);

            for (var i = 0; i <= selectedIndex; i++) {
                var star = stars[i];
                var label = star.nextElementSibling;

                label.classList.add('filled');
            }

            for (var i = selectedIndex + 1; i < stars.length; i++) {
                var star = stars[i];
                var label = star.nextElementSibling;

                label.classList.remove('filled');
            }
        }
                
                function showReviewPopup() {
                document.getElementById('reviewPopup').style.display = 'block';
                
                }

                function closeReviewPopup() {
                document.getElementById('reviewPopup').style.display = 'none';
        }
    </script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    var reviewButtons = document.querySelectorAll('.review-button');

    reviewButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            
            var productname = button.closest('.orderpastpurchasedetails').dataset.productname;
            var colourway = button.closest('.orderpastpurchasedetails').dataset.colourway;
            var datetime = button.closest('.orderpastpurchasedetails').dataset.datetime;
            var size = button.closest('.orderpastpurchasedetails').dataset.size;

            document.getElementById('product_name').value = productname;
            document.getElementById('colourway').value = colourway;
            document.getElementById('orderdate').value = datetime;
            document.getElementById('size').value = size;

            showReviewPopup();
        });
    });
});
    </script>

    <style>
        .orderhistory{
            text-align:center;
            height:auto;
        }

        .orderpast{
            height:25vh;
            margin-bottom: 10px;
        }
        .orderpastpurchase{
            display: flex;
            text-align:center;
            margin: auto;
            margin-top:20px;
        }

        .orderpastpurchaseimage {
            flex:1;
            text-align:right;
        }
        .orderpastpurchaseimage img{
            width:200px; 
            height:auto;
        }

        .orderpastpurchasedetails{
            flex:1;
            padding-left:20px;
            text-align: left;
            
        }

        .orderpastpurchasedetails p{
            text-align:left;
            
        }

        .horizontal-line{
            border: none;
            height: 1px;
            background-color: #ccc;
            width:50vw;
            margin: auto;
            margin-bottom:30px;
        }

        .footer {
            margin-top: 80px;
        }

        .popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border: 1px solid #ccc;
        padding: 20px;
        background: #fff;
        }

        .popup-content {
        text-align: center;
        }

        .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
        }

        .rating {
        display: flex;
        justify-content: center;
        }

        .rating input {
        display: none;
        }

        .rating label.filled {
        background-image: url('assets/Images/filled_star.png'); /* Replace with your filled star icon */
        background-size: cover;
        }

        /* Adjust the order of your existing styles if needed */
        .rating label {
        cursor: pointer;
        width: 40px;
        height: 40px;
        background-image: url('assets/Images/star.png'); /* Replace with your star icon */
        background-size: cover;
        }

        .review-button {
            background-color: #000000;
            color: #fff;
            border: 1px solid #000000;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            width: 200px;
            margin-bottom: 30px;
        }

    </style>
</head>

<body onload="scroller();">
    <div class="promo">
        <table border="0">
            <tr>
                <td id="tabledata">Current Promotion</td>
            </tr>
        </table>
    </div>
    <nav class="navbar">
        <div class="navleft">
        <span><a style="text-decoration:none; color:black;"href="product_list.php">MEN </a></span>
        <span><a style="text-decoration:none; color:black;" href="product_list2.php"> WOMEN  </a></span> 
        <a style="text-decoration:none; color:black;" href="product_list3.php"><span>UNISEX</span></a>
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
                                    <div class="dropdown-content" style="text-align:right; display: none; position: absolute; background-color: white; padding: 10px; top: 100%; z-index: 1;">
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
                                    <div class="dropdown-content" style="text-align:right; display: none; position: absolute; background-color: white; padding: 10px; top: 100%; z-index: 1;">
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


    <div class="orderhistory">
       <h1> Order History </h1>
       <?php
            $query = "SELECT datetime, DATE_FORMAT(datetime, '%d %M %Y %h:%i%p') AS formatted_datetime from confirmedorder
            WHERE user_id = ?
            GROUP BY datetime
            ORDER BY datetime DESC";
  
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $user_id); // Assuming user_id is an integer
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Display order details for each order on the same date
                echo "Order Date: " . $row['formatted_datetime'] . "<br>";
                $subquery = "SELECT rating, product_id, size, quantity, price, subtotal FROM confirmedorder WHERE user_id = ? AND datetime = ?";
                $substmt = $conn->prepare($subquery);
                $substmt->bind_param("ss", $user_id, $row['datetime']);
                $substmt->execute();
                $subresult = $substmt->get_result();
                $total = 0;
                // Display product IDs
                   
                    while ($subrow = $subresult->fetch_assoc()) {
                    echo '<div class="orderpast">';
                        
                        
                        $productquery = "SELECT product_name, colourway, image_data FROM products WHERE id = ?";
                        $productstmt = $conn->prepare($productquery);
                        $productstmt->bind_param("i", $subrow['product_id']);
                        $productstmt->execute();
                        $productresult = $productstmt->get_result();

                        while ($productrow = $productresult->fetch_assoc()) {
                        echo '<div class="orderpastpurchase">';
                            echo '<div class="orderpastpurchaseimage">';
                                echo '<img src="data:image/jpeg;base64,'  . base64_encode($productrow['image_data']) . '" >';
                            echo '</div>';
                            echo '<div class="orderpastpurchasedetails" data-productname="'.$productrow['product_name'].'" data-colourway="'.$productrow['colourway'].'" data-datetime="'.$row['datetime'].'" data-size="'.$subrow['size'].'">';
                                echo '<p style="font-size:24px;">'.$productrow['product_name'].'</p>';
                                echo '<p style="font-size:20px;">'.$productrow['colourway'].'&nbsp;&nbsp;&nbsp;'.' Size: '.$subrow['size']. '</p>';
                                echo '<p style="font-size:16px;"> Quantity: '.$subrow['quantity'].'&nbsp;&nbsp;&nbsp;'.' Price: $'.$subrow['price']. '</p>';
                                echo '<p style="font-size:16px;"> Subtotal: $'.$subrow['subtotal'].'</p>';
                                $total += $subrow['subtotal'];

                                if ($subrow['rating'] != 0 ){
                                    echo 'You gave a rating of: ';
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $subrow['rating']) {
                                        echo '★';
                                    } else {
                                        echo '☆'; 
                                    }
                                }
                                } else {
                                    echo '<div id="reviewPopup" class="popup">';
                                    echo '<div class="popup-content">';
                                        echo '<span class="close" onclick="closeReviewPopup()">&times;</span>';
                                        echo '<h2>Rate us out of 5 stars</h2>';
                                        echo '<form id="reviewForm" action="submit_review.php" method="POST">';
                                        echo '<div class="rating">';
                                            echo '<input type="hidden" id="orderdate" name="orderdate" value="'. $row['datetime'] .'">';
                                            echo '<input type="hidden" id="product_name" name="product_name" value="'.$productrow['product_name'].'">';
                                            echo '<input type="hidden" id="colourway" name="colourway" value="'.$productrow['colourway'].'">';
                                            echo '<input type="hidden" id="size" name="size" value="'.$subrow['size'].'">';
                                            echo '<input type="radio" id="star1" name="rating" value="1">';
                                            echo '<label for="star1"></label>';
                                            echo '<input type="radio" id="star2" name="rating" value="2">';
                                            echo '<label for="star2"></label>';
                                            echo '<input type="radio" id="star3" name="rating" value="3">';
                                            echo '<label for="star3"></label>';
                                            echo '<input type="radio" id="star4" name="rating" value="4">';
                                            echo '<label for="star4"></label>';
                                            echo '<input type="radio" id="star5" name="rating" value="5" required>';
                                            echo '<label for="star5"></label>';    
                                        echo '</div>';
                                        echo '<button type="submit">Submit</button>';
                                        echo '</form>';
                                    echo '</div>';
                                echo '</div>';
                                echo '<button  class="review-button"">Review</button>';
                                }
                                
                                

                               
                            echo '</div>';
                        echo '</div>';
                        
                        }
                    
                       
                     echo '</div>';
                     
                
                 }
                 echo '<p style="font-size:20px; margin-top:0px;"> Total: $'.$total.'</p>';
                 echo ' <hr class="horizontal-line">';
            }

            $substmt->close();
        } else {
            echo '<div class="orderpast" style="height:20vw;">';
            echo '<p style="font-size:40px;"> No order has been made </p>';
            echo '<a href="index.php"> <button class="checkoutbutton"> Buy Now </button> </a>';
            echo '</div>';
            
        }

            
       ?>
    </div>


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

    
<?php
$conn->close();
?>
</body>
</html>