
<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "shoeshop";

$conn = new mysqli($hostname, $username, $password, $database);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = $_POST['product_name'];
    $colorway = $_POST['colorway'];
    $quantity = $_POST['quantity'];
    echo "Product Name: " . $productName;
    echo "Colorway: " . $colorway;
    echo "Quantity: " . $quantity;
} else {
    // Handle invalid or unexpected requests, or show an error message.
    echo "Invalid request";
}
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
        $productName = $_GET['product_name'];
        $colorway = $_GET['colorway'];
        
        
        $productsSql = "SELECT colourway, image_data, image_data2, image_data3, image_data4, product_name, price, product_details, gender
                        FROM products 
                        WHERE product_name = '$productName' AND colourway = '$colorway' ";

        $productspageResult = $conn->query($productsSql);

        // Initialize an array to store the retrieved product data
        $products = array();

        if ($productspageResult->num_rows > 0) {
            while ($row = $productspageResult->fetch_assoc()) {
                $products[] = $row;
            }
        }

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

        if (quantity <1) {
            alert("No quantity has been selected");
                return false;
        } else {
            return true;
        }
        }
    </script>
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
            <span>MEN</span>
            <span>WOMEN</span>
            <span>UNISEX</span>
        </div>
        <div class="navcenter">
            <span><a href="index.php"> Logo </a></span>
        </div>
        <div class="navright">
            <span><a href= "accounts.php"> <img src="assets/Images/Icons/account.png"> </a></span>
            <span><a href= "faq.php"> <img src="assets/Images/Icons/FAQ.png"> </a></span>
            <span><a href= "findus.php"> <img src="assets/Images/Icons/map.png"> </a></span>
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
            <a style="font-size:15px; margin-top:20px;"> COLOURS </a> 
            <?php
           $sql = "SELECT colourway, image_data, product_name, product_details FROM products WHERE product_name = '" . $products[0]['product_name'] . "' AND gender = '" . $products[0]['gender'] . "'";

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
                       echo '<a id="colourbutton" href="product_page.php?product_name=' . urlencode($cell['product_name']) . '&colorway=' . urlencode($cell['colourway']) . '">';
                       echo '<img src="data:image/jpeg;base64,' . base64_encode($cell['image_data']) . '">';
                       echo '</a>';
                       echo '</div>';
                       
                   }
                echo '</div>';
               echo '</div>';
           }
                     
            ?>
            <a style="font-size:15px; margin-top:20px;"> SIZES </a> 
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
            
            
            if ($resultData->num_rows > 0) {
                while ($row = $resultData->fetch_assoc()) {
                    $stock[] = $row;
                }
                    foreach ($columnNames as $columnName) {
                        if ($stock[0][$columnName] >0 ){
                            echo '<div class="productsizecell">';
                            echo '<a href="product_page.php?product_name=' . urlencode($productName) . '&colorway=' . urlencode($colorway) .'&size=' . urlencode($columnName).'">';
                            if (isset($_GET['size'])) {
                                $size = $_GET['size'];
                                if ($columnName == $size){                  
                                    echo '<button id="sizebuttonactive" type="button" data-price="$price" data-stock="$stock[$columnName]" title="'.$columnName.'">'.$columnName.' </button>';
                                }   else {
                                    echo '<button id="sizebutton" type="button" data-price="$price" data-stock="$stock[$columnName]" title="'.$columnName.'">'.$columnName.' </button>';
                                }
                            } else {
                                echo '<button id="sizebutton" type="button" data-price="$price" data-stock="$stock[$columnName]" title="'.$columnName.'">'.$columnName.' </button>';
                            }
                            echo '</a>';
                            echo '</div>';
                        }
                    }
                }
                
             else {
                echo 'empty';
            }
            echo '</div>';
            echo '</div>';    
            
            $conn->close();
            
            ?>
            <p style="font-size:15px; margin-top:20px;   text-decoration: underline;"> PRODUCT DETAILS </p>
            <p style="font-size:14px; "> <?php echo $productdetails; ?> </p> 
            <label for="quantity" class="quantity">Quantity</label> <br>
            <div class="quantity-input">
                <button id="decrement">-</button>
                <input type="number" id="quantity" value="0" min="0">

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
            
            <form method="POST" onsubmit="return validateaddtocart()">
                <div class="hidden-input">
                    <input type="text" name="product_name" value="<?php echo $_GET['product_name']; ?>">
                    <input type="text" name="colorway" value="<?php echo $_GET['colorway']; ?>">
                    <input type="number" id="quantity" name="quantity" value="<?php echo $_GET['quantity']; ?>">
                </div>
                <button id="productpageaddtocart"> ADD TO CART </button>
            </form>
            
        </div>
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



