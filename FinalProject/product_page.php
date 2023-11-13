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

if (isset($_POST['id'], $_POST['product_name'], $_POST['price'], $_POST['size'], $_POST['quantity'], $_POST['colorway'])) {
    $product_id = $_POST['id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['price'];
    $product_size = $_POST['size'];
    $product_quantity = $_POST['quantity'];
    $product_color = $_POST['colorway'];
    $subtotal = $product_price * $product_quantity;

    $sql = "INSERT INTO cart (user_id, product_id,size, quantity, price, subtotal)
            VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Calculate the subtotal
    $subtotal = $product_price * $product_quantity;

    // Bind parameters and their data types
    $stmt->bind_param('sisidi', $user_id, $product_id, $product_size, $product_quantity, $product_price, $subtotal);
    
    /*// Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id][$product_size])) {
        // Increment the quantity if it's already in the cart
        $_SESSION['cart'][$product_id][$product_size]['quantity']++;
        
    } else {
        // Add the product to the cart
        $_SESSION['cart'][$product_id][$product_size] = [
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $product_quantity,
            'size' => $product_size,
            'color' => $product_color,
        ];
        
    }*/

    if ($stmt->execute()) {
        echo '<script>alert("Added to Cart");</script>';
    } else {
        echo "Error adding product to the cart: " . $stmt->error;
    }
}
/* if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productid = $_POST['id'];
    $productName = $_POST['product_name'];
    $colorway = $_POST['colorway'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];
}  */
        $productid = $_GET['id'];
        
        
        $productsSql = "SELECT id, colourway, image_data, image_data2, image_data3, image_data4, product_name, price, product_details, gender
                        FROM products 
                        WHERE id = '$productid'";

        $productspageResult = $conn->query($productsSql);

        // Initialize an array to store the retrieved product data
        $products = array();

        if ($productspageResult->num_rows > 0) {
            while ($row = $productspageResult->fetch_assoc()) {
                $products[] = $row;
            }
        }
        $id = $products[0]['id'];
        $productName = $products[0]['product_name'];
        $colorway = $products[0]['colourway'];
        $productprice = $products[0]['price'];
        $productdetails = $products[0]['product_details'];

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
            var selectedSize = document.querySelector('.productsizecell button[id="sizebuttonactive"]');
    
            if (!selectedSize) {
                alert("Please select a size before adding to the cart.");
                return false;
            }

            if (quantity < 1) {
                alert("No quantity has been selected.");
                return false;
            }

            return true;
        }
    </script>
    <style>
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
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
                                    <a href="orderhistory.php" style="text-align:left">Order History</a>
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
    <div class="productpagecontainer">
        <div class="productpageimages">
            <table class="imagecontainer">
                <tbody>
                    <tr>
                        <td id="gallery_display">  <img src="data:image/jpg;base64,<?php echo base64_encode($products[0]['image_data']); ?>"> </td>
                        <td id="gallery_display">  <img src="data:image/jpg;base64,<?php echo base64_encode($products[0]['image_data2']); ?>"></td>
                    </tr>
                    <tr>
                        <td id="gallery_display">  <img src="data:image/jpg;base64,<?php echo base64_encode($products[0]['image_data3']); ?>"></td>
                        <td id="gallery_display">  <img src="data:image/jpg;base64,<?php echo base64_encode($products[0]['image_data4']); ?>"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="productpagedetails">
            <a> <strong> <?php echo $products[0]['gender']; ?>  </strong> </a>
            <p style="font-size:80px; margin-bottom:0px;"> <?php echo $productName; ?> </p>
            <p style="font-size:15px; margin-top:0px;"> <strong> <?php echo $colorway; ?> </strong> </p>
            <p style="font-size:30px;"> <strong> $ <?php echo $productprice; ?> </strong> </p>
            <a style="font-size:15px; margin-top:20px;text-decoration:underline; S"> COLOURS </a> 
            <?php
           $sql = "SELECT id, colourway, image_data, product_name, product_details FROM products WHERE product_name = '" . $products[0]['product_name'] . "' AND gender = '" . $products[0]['gender'] . "'";

           // Execute the query
           $result = $conn->query($sql);
           
           $colourproducts = array();
           
           if ($result->num_rows > 0) {
               while ($row = $result->fetch_assoc()) {
                   $colourproducts[] = $row;
               }
           }
           
           if (!empty($colourproducts)) {
               echo '<div class="productcolourwaytable">';
               
               // Loop through the array to create table rows
               echo '<div class="productcolourwayrow">';
                   foreach ($colourproducts as $cell) {
                       // Create a table cell for each element in the row
                       
                       echo '<div class="productcolourwaycell">';
                       echo '<a id="colourbutton" href="product_page.php?id=' . urlencode($cell['id']) .'&product_name=' . urlencode($cell['product_name']) . '&colorway=' . urlencode($cell['colourway']) . '">';
                       echo '<img src="data:image/jpeg;base64,' . base64_encode($cell['image_data']) . '">';
                       echo '</a>';
                       echo '</div>';
                       
                   }
                echo '</div>';
               echo '</div>';
           }
                     
            ?>
            <a style="font-size:15px; margin-top:20px; text-decoration:underline;"> SIZES </a> 
            <?php
            $columnNamesQuery = "SHOW COLUMNS FROM inventory";
            $resultColumnNames = $conn->query($columnNamesQuery);
            
            if ($resultColumnNames->num_rows > 0) {
                $columnNames = [];
                while ($row = $resultColumnNames->fetch_assoc()) {
                    $columnName = $row['Field'];
                    if (strpos($columnName, 'US') === 0) {
                        $columnNames[] = $columnName;
                    }
                }
            }
            
            $dynamicConditions = [];
            
            foreach ($columnNames as $columnName) {
                $dynamicConditions[] = "`$columnName` <> 0";
            }
            
            // SQL query to get rows where the specified column's value is not 0
            $dataQuery = "SELECT `" . implode("`,`", $columnNames) . "` FROM inventory 
                          WHERE product_name = '$productName'
                          AND colorway = '$colorway'";
            
            $resultData = $conn->query($dataQuery);
            
            $stock = [];
            echo '<div class="productsizetable">';
            // Loop through the array to create table rows
            echo '<div class="productsizerow">';
            $allZero = true;
            
            if ($resultData->num_rows > 0) {
                while ($row = $resultData->fetch_assoc()) {
                    $stock[] = $row;
                }
                    foreach ($columnNames as $columnName) {
                        if ($stock[0][$columnName] >0 ){
                            $allZero = false;
                            echo '<div class="productsizecell">';
                            echo '<a href="product_page.php?id=' . urlencode($id) .'&product_name=' . urlencode($productName) . '&colorway=' . urlencode($colorway) .'&size=' . urlencode($columnName).'">';
                            if (isset($_GET['size'])) {
                                $size = $_GET['size'];
                                if ($columnName == $size){                  
                                    echo '<button id="sizebuttonactive" type="button" data-price="'.$productprice.'"  title="'.$columnName.'">'.$columnName.' </button>';
                                }   else {
                                    echo '<button id="sizebutton" type="button" data-price="'.$productprice.'"  title="'.$columnName.'">'.$columnName.' </button>';
                                }
                            } else {
                                echo '<button id="sizebutton" type="button" data-price="'.$productprice.'" title="'.$columnName.'">'.$columnName.' </button>';
                            }
                            echo '</a>';
                            echo '</div>';
                        }
                    }
                }
                
             else {
                echo 'No Stock Available. Please kindly wait for the next restock';
            }

            if ($allZero) {
                echo 'No Stock Available. Please kindly wait for the next restock';
            }
            echo '</div>';
            echo '</div>';    
            
            $conn->close();
            
            ?>
            <div>
            <p style="font-size:15px; margin-top:20px; padding-left:0px;  text-decoration: underline;"> PRODUCT DETAILS </p>
            <p style="font-size:14px; "> <?php echo $productdetails; ?> </p> 
            </div>
            <label for="quantity" class="quantity">Quantity</label> <br>
            <div class="quantity-input">
                <button id="decrement">-</button>
                <input type="number" id="quantity" value="0" min="0" onkeydown="handleEnterKey(event)" onblur="handleInput()">

                <button id="increment">+</button>
            </div>
            <?php
            if (isset($size)) {
                if ($stock[0][$size] < 5)  {
                    echo '<span style="color:red";> Only ' . $stock[0][$size] . ' pieces left</span>';
                }
            }
            ?>
            <script>
                const decrementButton = document.getElementById('decrement');
                const incrementButton = document.getElementById('increment');
                const quantityInput = document.getElementById('quantity');

                function updateURL(newQuantity) {
                const currentURL = window.location.href;
                const url = new URL(currentURL);
                url.searchParams.set('quantity', newQuantity);
                window.history.pushState({}, '', url);
            }
            
            function handleEnterKey(event) {
            if (event.key === "Enter") {
                var quantity = document.getElementById("quantity").value;
                updateURL(quantity);
                refreshPage();
            }
            }

            function handleInput() {
                var quantity = document.getElementById("quantity").value;
                updateURL(quantity);
                refreshPage();
            }
            function refreshPage() {
                location.reload();
            }
            decrementButton.addEventListener('click', () => {
                if (quantityInput.value > 0) {
                    quantityInput.value--;
                    updateURL(quantityInput.value);
                }
                refreshPage();
            });

            incrementButton.addEventListener('click', () => {
                if (quantityInput.value < <?php echo $stock[0][$size]; ?>) {
                    quantityInput.value++;
                    updateURL(quantityInput.value);
                }
                refreshPage(); 
            });

            // Initial URL update
            const initialQuantity = parseInt(new URLSearchParams(window.location.search).get('quantity'));
            if (!isNaN(initialQuantity) && initialQuantity >= 1) {
                quantityInput.value = initialQuantity;
            }
            </script>
            
            <form method="post" onsubmit="return validateaddtocart()">
                <div class="hidden-input">
                    <input type="text" name="id" value="<?php echo $_GET['id']; ?>">
                    <input type="text" name="product_name" value="<?php echo $_GET['product_name']; ?>">
                    <input type="text" name="colorway" value="<?php echo $_GET['colorway']; ?>">
                    <input type="text" name="size" value="<?php echo $_GET['size']; ?>">
                    <input type="number" step="any" name="price" value="<?php echo $productprice; ?>">
                    <input type="number" id="quantity" name="quantity" value="<?php echo $_GET['quantity']; ?>">
                </div>
                <button id="productpageaddtocart" name="addtocart"> ADD TO CART </button>
            </form>
            
        </div>
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
</body>
<script>

    // Get references to the input and buttons
const quantityInput = document.getElementById("quantity");
const incrementButton = document.getElementById("increment");
const decrementButton = document.getElementById("decrement");

// Function to handle the increment button click
incrementButton.addEventListener("click", function() {
    // Parse the current value and increment it
    let currentValue = parseInt(quantityInput.value);
    quantityInput.value = currentValue + 1;
});

// Function to handle the decrement button click
decrementButton.addEventListener("click", function() {
    // Parse the current value and decrement it, but not below 0
    let currentValue = parseInt(quantityInput.value);
    if (currentValue > 0) {
        quantityInput.value = currentValue - 1;
    }
});

// Function to handle input changes and ensure it's a valid number
function handleInput() {
    let currentValue = parseInt(quantityInput.value);
    if (isNaN(currentValue) || currentValue < 0) {
        quantityInput.value = 0; // Reset to 0 if input is invalid
    }
}

// Function to handle the Enter key press
function handleEnterKey(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Prevent form submission
        handleInput(); // Ensure the input value is valid
    }
}

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
</html>




