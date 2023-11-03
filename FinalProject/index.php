<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "shoeshop";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    global $conn;
    $sql = "SELECT inventory.product_name, inventory.colorway, inventory.sales, products.image_data ,products.price
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


    $productsSql = "SELECT product_name, colourway, price, release_date, image_data FROM products
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
            <span><a href="men.php">MEN </a></span>
            <span><a href="women.php"> WOMEN  </a></span>
            <span><a href="men.php"> MEN  </a></span>
            <span><a href="women.php"> WOMEN  </a></span>
           
        </div>
        <div class="navcenter">
            <span>Logo</span>
        </div>
        <div class="navright">
        <span><a href="men.php">MEN </a></span>
            <span><a href="women.php"> WOMEN  </a></span>
            <span><a href="men.php"> MEN  </a></span>
            <span><a href="women.php"> WOMEN  </a></span>
        </div>
    </nav>
    <div class="horizontal-scroll-container">
        <div class="content">
            <div class="section" id="section1"> 
                <div class="sectiondetails1">
                    <a style="font-size: 100px">NIKE AIR FORCE ONES</a> <br>
                    <a>The versatile lifestyle shoe</a> <br>
                    <button class="genderbutton" style="color:#ffffff; border: 2px solid #ffffff;">Shop Now</button> 
                </div>
            </div>
            <div class="section" id="section2">
                <div class="sectiondetails2">
                    <a style="font-size: 100px">DR MARTENS</a> <br>
                    <a>The boot that started it all</a> <br>
                    <button class="genderbutton" style="color:#fffac6; border: 2px solid #fffac6;">Shop Now</button> 
                </div>
                
            </div>
            <div class="section" id="section3">
            <div class="sectiondetails3">
                    <a style="font-size: 100px">CROCS</a> <br>
                    <a>The Biggest Selection Of Clogs, Sandals & More</a> <br>
                    <button class="genderbutton" style="background-color:#00000080; color:#ffffff; border: 2px solid #ffffff;">Shop Now</button> 
                </div>
            </div>
        </div>
        <div class="dot-indicator"></div>
    </div>
    <div class="latestnews">  
        <a id="LatestNews">Buy Our Latest Collection Now</a>
    </div>
    <div class="Title">
        <h1>OUR BESTSELLERS</h1>
            <table class="bestsellerhome"> 
                <tr>
                    <td>
                        <table class="productcontainer">
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
                        
                    </td>
                    <td>
                        <table class="productcontainer">
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
                    </td>
                    <td>
                        <table class="productcontainer">
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
                <a>More details on the new collection here</a>
                <div class="collectionbuttons">
                    <button class="genderbutton"> MEN </button>
                    <button class="genderbutton"> WOMEN </button>
                </div>
            </div>
        </div>
        <div class="newarrivals">
            <h1>NEW ARRIVALS</h1>
            <div class="scrollnewarrival">
                <div class="scroll-content">
                    <div class="productwrapper">
                        <div class="productimage">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($newarrivals[0]['image_data']); ?>">
                        </div>
                        <div class="productdetails">
                            <h3> <strong>  <?php echo $newarrivals[0]['product_name'] . ' (' . $newarrivals[0]['colourway'] . ')'; ?> </strong> </h3> 
                            <a> <small> <?php echo $newarrivals[0]['price']; ?></small></a>
                        </div>
                    </div>
                    <div class="productwrapper">
                        <div class="productimage">
                             <img src="data:image/jpeg;base64,<?php echo base64_encode($newarrivals[1]['image_data']); ?>">
                        </div>
                        <div class="productdetails">
                         <h3> <strong>  <?php echo $newarrivals[1]['product_name'] . ' (' . $newarrivals[1]['colourway'] . ')'; ?> </strong> </h3> 
                            <a> <small> <?php echo $newarrivals[1]['price']; ?></small></a>
                        </div>
                    </div>
                    <div class="productwrapper">
                        <div class="productimage">
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($newarrivals[2]['image_data']); ?>">
                        </div>
                        <div class="productdetails">
                         <h3> <strong>  <?php echo $newarrivals[2]['product_name'] . ' (' . $newarrivals[2]['colourway'] . ')'; ?> </strong> </h3> 
                            <a> <small> <?php echo $newarrivals[2]['price']; ?></small></a>
                        </div>
                    </div>
                    <div class="productwrapper">
                        <div class="productimage">
                            <img src ="assets/Images/Bestseller2.jpg">
                        </div>
                        <div class="productdetails">
                            <h3> <strong>  Product Name </strong> </h3> 
                            <a> <small> Product Details</small></a>
                        </div>
                    </div>
                    <div class="productwrapper">
                        <div class="productimage">
                            <img src ="assets/Images/Bestseller2.jpg">
                        </div>
                        <div class="productdetails">
                            <h3> <strong>  Product Name </strong> </h3> 
                            <a> <small> Product Details</small></a>
                        </div>
                    </div>
                    <div class="productwrapper">
                        <div class="productimage">
                            <img src ="assets/Images/Bestseller2.jpg">
                        </div>
                        <div class="productdetails">
                            <h3> <strong>  Product Name </strong> </h3> 
                            <a> <small> Product Details</small></a>
                        </div>
                    </div>
                    <div class="productwrapper">
                        <div class="productimage">
                            <img src ="assets/Images/Bestseller2.jpg">
                        </div>
                        <div class="productdetails">
                            <h3> <strong>  Product Name </strong> </h3> 
                            <a> <small> Product Details</small></a>
                        </div>
                    </div>
                    <div class="productwrapper">
                        <div class="productimage">
                            <img src ="assets/Images/Bestseller2.jpg">
                        </div>
                        <div class="productdetails">
                            <h3> <strong>  Product Name </strong> </h3> 
                            <a> <small> Product Details</small></a>
                        </div>
                    </div>       
                </div>
                <button id="scroll-button">
                    <img src="assets/Images/nextbutton.png">
                </button>
            </div>
        </div>    
        <div class="newsletter">
            <h1> NEWSLETTER</h1>
            <a> JOIN OUR NEWSLETTER TO BE UPDATED</a>
            <form>
                <input type="email" id="email" placeholder="Enter Your Email Address">
                <button type="submit" class="submit">SIGN UP </button>
            </form>
        </div>
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
               
    </body>
    </html>
</body>
</html>