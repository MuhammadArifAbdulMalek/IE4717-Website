<?php
include 'common.php';
$hostname = "localhost";
$username = "root";
$password = "";
$database = "shoeshop";

$conn = new mysqli($hostname, $username, $password, $database);
$user_id = setUserSession();

$sqlUpdate = "UPDATE cart
    JOIN (
        SELECT user_id, product_id, size, SUM(quantity) AS total_quantity
        FROM cart
        GROUP BY user_id, product_id, size
    ) AS subquery
    ON cart.user_id = subquery.user_id
    AND cart.product_id = subquery.product_id
    AND cart.size = subquery.size
    SET cart.quantity = subquery.total_quantity";

if ($conn->query($sqlUpdate) === TRUE) {
    echo "Quantity updated successfully.";
} else {
    echo "Error updating quantity: " . $conn->error;
}

$sqlDelete = "DELETE c1
            FROM cart c1
            JOIN cart c2
            ON c1.user_id = c2.user_id
            AND c1.product_id = c2.product_id
            AND c1.size = c2.size
            AND c1.cart_id < c2.cart_id;";

if ($conn->query($sqlDelete) === TRUE) {
    echo "Redundant rows deleted successfully.";
} else {
    echo "Error deleting redundant rows: " . $conn->error;
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
            <span><a href="index.php"> Logo </a></span>
        </div>
        <div class="navright" >
            <span style="margin:0px;">
                <?php if (isset($_SESSION['first_name'])): ?>
                    <div class="dropdown" style="width: 140px; position: relative;">
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
    <form action="checkout.php" method="post" class="cartdetails" id="checkout-form">
    
    <div class="cart">
        <h1 style="padding-top:15px;"> Cart </h1>
        <?php
        $cartSql = "SELECT product_id,size, quantity, price, subtotal FROM cart WHERE user_id = '$user_id'";
        $cartResult = $conn->query($cartSql);
        $cartData = array();

        while ($row = $cartResult->fetch_assoc()) {
            $cartData[] = array(
                'product_id' => $row['product_id'],
                'size' => $row['size'],
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
        
                // Query to fetch the stock for the specified size and product
                $size = $cartItem['size'];
                $stockSql = "SELECT `$size` FROM inventory WHERE id = $product_id";
                $stockResult = $conn->query($stockSql);
        
                if ($stockResult) {
                    $stockRow = $stockResult->fetch_assoc();
                    $stock = $stockRow[$size];
                } else {
                    echo "Failed to fetch stock information.";
                    $stock = "N/A"; // Set a default value or handle the error as needed
                }
        
                $productDetails[] = array(
                    'product_id' => $productRow['id'],
                    'product_name' => $productRow['product_name'],
                    'image_data' => $productRow['image_data'],
                    'colourway' => $productRow['colourway'],
                    'quantity' => $cartItem['quantity'],
                    'price' => $cartItem['price'],
                    'subtotal' => $cartItem['subtotal'],
                    'size' => $cartItem['size'],
                    'stock' => $stock // Include the stock value
                );
            } else {
                echo "<h2>Your shopping cart is empty.</h2>";
            }
        }
        

        // Add buttons for updating the cart or proceeding to checkout
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
                    $stock = $product['stock'];
                    $price = $product['price'];
                    $size = $product['size'];
                    $subtotal = $product['subtotal'];
                    $total += $subtotal;
                    echo '<tr>';
                    echo '<td id="cartimagecolumn"><a class="cartimage" href=""><img src="data:image/jpeg;base64,' . base64_encode($image_data) . '"></td>';
                    echo '<td id="cartdetailscolumn">';
                    echo '<h2 style="font-size:30px;">' . $product_name . '</h2>';
                    echo '<p style="font-size:20px;">Colour: ' . " " . $colourway . '</p>';
                    echo '<p style="font-size:20px;">Size: ' . " " . $size . '</p>';
                    echo '<p style="font-size:20px;">Quantity: ' . " ";
                    echo '<input type="number" class="quantity-input" name="quantity[]" min="0" max="'. $stock.'" value="' . $quantity . '" data-price="' . $price . '" data-subtotal="' . $subtotal . '">';
                    echo '</p>';
                    echo '<p> Subtotal: <span class="subtotal">$' . $subtotal . '</span></p>';
                    echo '</td>';
                    echo '</tr>';
                    echo '<input type="hidden" name="product_id[]" value="'. $product_id .'">';
                    echo '<input type="hidden" name="product_name[]" value="'. $product_name .'">';
                    echo '<input type="hidden" name="colourway[]" value="'. $colourway .'">';
                    echo '<input type="hidden" name="size[]" value="'. $size .'">';
                    echo '<input type="hidden" name="price[]" value="'. $price .'">';
                }
                ?>

            </table>
        </div>
        <h2 id="total">Total: $ <?php echo $total ?></h2>
        <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
       
    
 
  
       </div>
        <div class="cartcheckoutbuttons">
         <button class="checkoutbutton" type="submit">Checkout</button>
        </div>
        </form>
        <div class="footer">
            <div class="footerupper">
                <div class="sitemap">
                    <a style="font-size: 25px; text-decoration: underline;"> <strong>Quick Directory </strong> </a> <br>
                    <table class = "sitemaplinks">
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
       
       
            
        ?>
        
    
</body>
</html>

<script>
    // Add JavaScript code to handle quantity changes and update subtotal
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const totalElement = document.getElementById('total');
    let total = <?php echo $total ?>; // Initialize the total from the PHP value

    quantityInputs.forEach(input => {
        input.addEventListener('input', () => {
            const quantity = parseInt(input.value, 10); // Parse the input value as an integer
            if (!isNaN(quantity) && quantity >= 0) {
                const price = input.getAttribute('data-price');
                const subtotal = quantity * price;
                const subtotalElement = input.closest('tr').querySelector('.subtotal');
                subtotalElement.textContent = '$' + subtotal.toFixed(2); // Format as currency
                input.setAttribute('data-subtotal', subtotal);
                total = calculateTotal();
                updateTotal();
            }
        });
    });

    function calculateTotal() {
        const subtotals = Array.from(document.querySelectorAll('.subtotal')).map(subtotalElement => parseFloat(subtotalElement.textContent.replace('$', '')));

        return subtotals.reduce((acc, subtotal) => acc + subtotal, 0);
    }

    function updateTotal() {
        totalElement.textContent = 'Total: $ ' + total.toFixed(2); // Update the total element
    }

    // Call updateTotal initially to display the total based on the initial values
    updateTotal();
    
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

