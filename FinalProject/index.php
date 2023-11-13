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
if (isset($_POST['newsletter'])) {
        $sqlemail = $_POST['newsletter'];
        $sqldup = "SELECT * FROM newsletter WHERE email = '$sqlemail'";
        $result = $conn->query($sqldup);
        if ($result->num_rows > 0) {
            // Data already exists; you can update it or take appropriate action
            echo '<script>alert("You have already subscribed to our newsletter.");</script>';
        }
         else {   $insertSql = "INSERT INTO newsletter (email) VALUES (?)";
                    $stmt = $conn->prepare($insertSql);
                    $stmt->bind_param("s", $sqlemail);
                    $stmt->execute();
                    $stmt->close();
                    echo '<script>alert("You have successfully subscribed to our newsletter. \n We look forward to keeping you updated.");</script>';
        }
    }   

    global $conn;
    $sql = "SELECT inventory.id, inventory.product_name, inventory.colorway, inventory.sales, products.image_data ,products.price
            FROM inventory
            INNER JOIN products ON inventory.product_name = products.product_name AND inventory.colorway = products.colourway
            ORDER BY inventory.sales DESC LIMIT 3";


    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $products = array();
        
        while ($row = $result->fetch_assoc()) {
                $products[] = $row;
        }

    } else {
            echo "No products found.";
    }


    $productsSql = "SELECT id, product_name, colourway, price, release_date, image_data FROM products
                    ORDER BY release_date DESC";

    // Execute the products SQL query
    $productsResult = $conn->query($productsSql);
    
    // Initialize an array to store the retrieved product data
    $newarrivals = array();
    
    if ($productsResult->num_rows > 0) {
        while ($row = $productsResult->fetch_assoc()) {
            $newarrivals[] = $row;
        }
    }


    // Close the database connection
    $conn->close();



?>


<!DOCTYPE html>
<html lang="en">

<head>
<title>Home</title>
<link rel="stylesheet" href="styles.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,600&family=Lato:wght@700&display=swap" rel="stylesheet">
<script src="scripts.js"defer></script>
<script type="text/JavaScript">
    var message="Current Promotions Latest News Get it Here";
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
            <a style="text-decoration:none; color:black;" href="product_list.php"><span>MEN</span></a>
            <a style="text-decoration:none; color:black;" href="product_list2.php"><span>WOMEN</span></a>
            <a style="text-decoration:none; color:black;" href="product_list3.php"><span>UNISEX</span></a>
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
                                    <a href="logout.php?return_url=<?php echo urlencode($_SERVER['REQUEST_URI']);?>">Logout</a>
                                    <a href="admin.php">Admin</a>
                                    </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="dropdown" style="width: 110px; position: relative;">
                            <div class="dropdownbar" style="text-align:left; position: relative; display: inline-block; font-size: 90%;">                                      
                                    <label for=user-account>Hi, <?php echo $_SESSION['first_name']; ?></label>
                                    <div class="dropdown-content" style="text-align:right; display: none; position: absolute; background-color: white; padding: 10px; top: 100%; right: 0; z-index: 1;">
                                    <a href="logout.php?return_url=<?php echo urlencode($_SERVER['REQUEST_URI']);?>">Logout</a>
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
    <div class="horizontal-scroll-container">
        <div class="content">
            <div class="section" id="section1"> 
                <div class="sectiondetails1">
                    <a style="font-size: 100px">NIKE AIR FORCE ONES</a> <br>
                    <a>The versatile lifestyle shoe</a> <br>
                    <button class="genderbutton" style="color:#ffffff; border: 2px solid #ffffff;"><a style="text-decoration:none; color:#ffffff;" href="product_list3.php">Shop Now</a></button> 
                </div>
            </div>
            <div class="section" id="section3">
            <div class="sectiondetails3">
                    <a style="font-size: 100px">CROCS</a> <br>
                    <a>The Biggest Selection Of Clogs, Sandals & More</a> <br>
                    <button class="genderbutton" style="background-color:#00000080; color:#ffffff; border: 2px solid #ffffff;"><a style="text-decoration:none; color:white;" href="product_list2.php">Shop Now</a></button> 
                </div>
            </div>
            <div class="section" id="section2">
                <div class="sectiondetails2">
                    <a style="font-size: 100px">DR MARTENS</a> <br>
                    <a>The boot that started it all</a> <br>
                    <button class="genderbutton" style="color:#fffac6; border: 2px solid #fffac6;"><a style="text-decoration:none;  color:#fffac6;" href="product_list.php">Shop Now</a></button> 
                </div>
                
            </div>
        </div>
        <div class="dot-indicator"></div>
    </div>
    <div class="latestnews">  
        <a id="LatestNews" style="color: #ffffff;">Buy Our Latest Collection Now</a>
    </div>
    <div class="Title">
        <h1>OUR BESTSELLERS</h1>
            <table class="bestsellerhome"> 
                <tr>
                    <td>
                    <a class="clickable" style="text-decoration:none; color:black;" href="product_page.php?id=<?php echo urlencode($products[0]['id']);?>&product_name=<?php echo urlencode($products[0]['product_name']); ?>&colorway=<?php echo urlencode($products[0]['colorway']); ?>">
                        <table class="productcontainer" id="bestseller1">
                            <tr> <td class="productimage"> 
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($products[0]['image_data']); ?>">
                            </td> </tr>
                            <tr> <td class="productdetails">  
                            <h3><strong>
                                    <?php echo $products[0]['product_name'] . ' (' . $products[0]['colorway'] . ')'; ?>
                                </strong></h3>
                                    <a> <small> $ <?= $products[0]['price']?></small></a>
                                </td>
                            </tr>
                        </table>
                    </a>
                    </td>
                    <td>
                    <a class="clickable" style="text-decoration:none; color:black;" href="product_page.php?id=<?php echo urlencode($products[1]['id']);?>&product_name=<?php echo urlencode($products[1]['product_name']); ?>&colorway=<?php echo urlencode($products[1]['colorway']); ?>">
                        <table class="productcontainer"  id="bestseller2">
                            <tr> <td class="productimage"> 
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($products[1]['image_data']); ?>">
                            </td> </tr>
                            <tr> <td class="productdetails">  
                            <h3><strong>
                                    <?php echo $products[1]['product_name'] . ' (' . $products[1]['colorway'] . ')'; ?>
                                </strong></h3>
                                    <a> <small>$ <?= $products[1]['price']?></small></a>
                                </td>
                            </tr>
                        </table>
                    </a>
                    </td>
                    <td>
                    <a class="clickable" style="text-decoration:none; color:black;" href="product_page.php?id=<?php echo urlencode($products[2]['id']);?>&product_name=<?php echo urlencode($products[2]['product_name']); ?>&colorway=<?php echo urlencode($products[2]['colorway']); ?>">
                        <table class="productcontainer"  id="bestseller3">
                            <tr> <td class="productimage"> 
                             <img src="data:image/jpeg;base64,<?php echo base64_encode($products[2]['image_data']); ?>">
                            </td> </tr>
                            <tr> <td class="productdetails">  
                            <h3><strong>
                                    <?php echo $products[2]['product_name'] . ' (' . $products[2]['colorway'] . ')'; ?>
                                </strong></h3>
                                    <a> <small> $ <?= $products[2]['price']?></small></a>
                                </td>
                            </tr>
                        </table>
                    </a>
                    </td>
                </tr>
               
            </table>
        </div>
        <div class="newcollection">
            <div class="collectiondisplay">
                <table>
                    <tr>
                        <td class="collectionimage">
                          <img src ="assets/Images/collection1.jpg"> 
                        </td>
                        <td class="collectionimage">
                            <img src ="assets/Images/collection1.jpg"> 
                          </td>
                    </tr>
                </table>
            </div>
            <div class="collectionbottom">
                <h1>NEW COLLECTION</h1>
                <a>All the latest releases of the month<br>
                    Get the newest kicks, be in trend
                </a>
                <div class="collectionbuttons">
                    <button class="genderbutton" style="border: 2px solid #ffffff;"> <a style="text-decoration:none; color:#ffffff;" href="product_list.php">MEN</a> </button>
                    <button class="genderbutton" style="border: 2px solid #ffffff;"> <a style="text-decoration:none; color:#ffffff;" href="product_list.php">WOMEN</a> </button>
                </div>
            </div>
        </div>
        <div class="newarrivals">
            <h1>NEW ARRIVALS</h1>
            <div class="scrollnewarrival">
                <div class="scroll-content">
                    <a class="clickable" style="text-decoration:none; color:black;" href="product_page.php?id=<?php echo urlencode($newarrivals[0]['id']);?>&product_name=<?php echo urlencode($newarrivals[0]['product_name']); ?>&colorway=<?php echo urlencode($newarrivals[0]['colourway']); ?>">
                    <div class="productwrapper">
                        <div class="productimage">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($newarrivals[0]['image_data']); ?>">
                        </div>
                        <div class="productdetails">
                            <h3> <strong>  <?php echo $newarrivals[0]['product_name'] . ' <br> (' . $newarrivals[0]['colourway'] . ')'; ?> </strong> </h3> 
                            <a> <small> $<?php echo $newarrivals[0]['price']; ?></small></a>
                        </div>
                    </div>
                    </a>
                    <a class="clickable"style="text-decoration:none; color:black;" href="product_page.php?id=<?php echo urlencode($newarrivals[1]['id']);?>&product_name=<?php echo urlencode($newarrivals[1]['product_name']); ?>&colorway=<?php echo urlencode($newarrivals[0]['colourway']); ?>">
                    <div class="productwrapper">
                        <div class="productimage">
                             <img src="data:image/jpeg;base64,<?php echo base64_encode($newarrivals[1]['image_data']); ?>">
                        </div>
                        <div class="productdetails">
                         <h3> <strong>  <?php echo $newarrivals[1]['product_name'] . ' <br> (' . $newarrivals[1]['colourway'] . ')'; ?> </strong> </h3> 
                            <a> <small> $<?php echo $newarrivals[1]['price']; ?></small></a>
                        </div>
                    </div>
                    </a>
                    <a class="clickable" style="text-decoration:none; color:black;" href="product_page.php?id=<?php echo urlencode($newarrivals[2]['id']);?>&product_name=<?php echo urlencode($newarrivals[2]['product_name']); ?>&colorway=<?php echo urlencode($newarrivals[0]['colourway']); ?>">
                    <div class="productwrapper">
                        <div class="productimage">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($newarrivals[2]['image_data']); ?>">
                        </div>
                        <div class="productdetails">
                         <h3> <strong>  <?php echo $newarrivals[2]['product_name'] . ' <br> (' . $newarrivals[2]['colourway'] . ')'; ?> </strong> </h3> 
                            <a> <small> $<?php echo $newarrivals[2]['price']; ?></small></a>
                        </div>
                    </div>
                    </a>
                    <a class="clickable" style="text-decoration:none; color:black;" href="product_page.php?id=<?php echo urlencode($newarrivals[3]['id']);?>&product_name=<?php echo urlencode($newarrivals[3]['product_name']); ?>&colorway=<?php echo urlencode($newarrivals[0]['colourway']); ?>">
                    <div class="productwrapper">
                    <div class="productimage">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($newarrivals[3]['image_data']); ?>">
                        </div>
                        <div class="productdetails">
                         <h3> <strong>  <?php echo $newarrivals[3]['product_name'] . ' <br> (' . $newarrivals[3]['colourway'] . ')'; ?> </strong> </h3> 
                            <a> <small> $<?php echo $newarrivals[3]['price']; ?></small></a>
                        </div>
                    </div>
                    </a>
                    <a class="clickable" style="text-decoration:none; color:black;"  href="product_page.php?id=<?php echo urlencode($newarrivals[4]['id']);?>&product_name=<?php echo urlencode($newarrivals[4]['product_name']); ?>&colorway=<?php echo urlencode($newarrivals[0]['colourway']); ?>">
                    <div class="productwrapper">
                    <div class="productimage">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($newarrivals[4]['image_data']); ?>">
                        </div>
                        <div class="productdetails">
                         <h3> <strong>  <?php echo $newarrivals[4]['product_name'] . ' <br> (' . $newarrivals[4]['colourway'] . ')'; ?> </strong> </h3> 
                            <a> <small> $<?php echo $newarrivals[4]['price']; ?></small></a>
                        </div>
                    </div>
                    </a>
                    
                </div>
            </div>
        </div>    
        <div class="newsletter">
            <h1> NEWSLETTER</h1>
            <a> JOIN OUR NEWSLETTER TO BE UPDATED</a>
            <form method="POST" action="">
                <input type="email" id="email" name="newsletter" placeholder="Enter Your Email Address">
                <button type="submit" class="submit">SIGN UP </button>
            </form>
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
        <script>
            // Select all dropdown bars and checkbox forms
            const dropdownBars = document.querySelectorAll(".dropdownbar");
            const dropdownContent = document.querySelectorAll(".dropdown-content");
            // Add a click event listener to each dropdown bar
            dropdownBars.forEach((dropdownBar, index) => {
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
</body>
</html>