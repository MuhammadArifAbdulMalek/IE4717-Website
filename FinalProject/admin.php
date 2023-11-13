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

if ($_SESSION['admin'] != 1) {
    // Redirect to another page or display an error message
    header("Location: index.php"); // You can create an unauthorized.php page
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['removeProduct'])) {
        $existingProductName = $_POST['existingProductName'];
        $existingProductColor = $_POST['existingProductColor'];

        // Check if the product exists in the "inventory" table
        $sqlCheckInventory = "SELECT * FROM inventory WHERE product_name = ? AND colorway = ?";
        $stmtCheckInventory = $conn->prepare($sqlCheckInventory);
        $stmtCheckInventory->bind_param("ss", $existingProductName, $existingProductColor);

        // Execute the check query
        $stmtCheckInventory->execute();
        $resultCheckInventory = $stmtCheckInventory->get_result();

        // Check if the product exists in the "products" table
        $sqlCheckProducts = "SELECT * FROM products WHERE product_name = ? AND colourway = ?";
        $stmtCheckProducts = $conn->prepare($sqlCheckProducts);
        $stmtCheckProducts->bind_param("ss", $existingProductName, $existingProductColor);

        // Execute the check query
        $stmtCheckProducts->execute();
        $resultCheckProducts = $stmtCheckProducts->get_result();

        // Check if both the product and color exist in both tables
        if ($resultCheckInventory->num_rows > 0 && $resultCheckProducts->num_rows > 0) {
            // Rows exist, proceed with deletion

            // Delete from the "inventory" table
            $sqlRemoveInventory = "DELETE FROM inventory WHERE product_name = ? AND colorway = ?";
            $stmtRemoveInventory = $conn->prepare($sqlRemoveInventory);
            $stmtRemoveInventory->bind_param("ss", $existingProductName, $existingProductColor);

            // Delete from the "products" table
            $sqlRemoveProducts = "DELETE FROM products WHERE product_name = ? AND colourway = ?";
            $stmtRemoveProducts = $conn->prepare($sqlRemoveProducts);
            $stmtRemoveProducts->bind_param("ss", $existingProductName, $existingProductColor);

            // Execute the deletion queries
            if ($stmtRemoveInventory->execute() && $stmtRemoveProducts->execute()) {
                echo '<script>alert("Product sucessfully removed.");</script>';
            } else {
                echo '<script>alert("Error removing product.");</script>';
            }

        } else {
            // Rows do not exist, display an error message
            echo '<script>alert("Product does not exist.");</script>';
        }

        // Close the check statements
        $stmtCheckInventory->close();
        $stmtCheckProducts->close();
    } else {
        $productName = $_POST['newProductName'];
        $productColor = $_POST['newProductColor'];
        $productReleaseDate = $_POST['newProductReleaseDate'];
        $productDetails = $_POST['newProductDetails'];
        $productBrand = $_POST['newProductBrand'];
        $productPrice = $_POST['newProductPrice'];
        $productGender = $_POST['newProductGender'];

        // Set default values for 'M' and 'F'
        $MValue = 0;
        $FValue = 0;

        // Set values for 'M' and 'F' based on gender
        if ($productGender == 'MEN') {
            $MValue = 1;
        } elseif ($productGender == 'WOMEN') {
            $FValue = 1;
        } elseif ($productGender == 'MEN/WOMEN') {
            $MValue = 1;
            $FValue = 1;
        }

        // Insert into inventory table
        $sqlInventory = "INSERT INTO inventory (product_name, colorway, release_date, brand, price, image_data, M, F, `US 4.0`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)";
        $stmtInventory = $conn->prepare($sqlInventory);
        $stmtInventory->bind_param("ssssdbii", $productName, $productColor, $productReleaseDate, $productBrand, $productPrice, $imageData, $MValue, $FValue);

        $imageFieldName = "newProductImage1";
        validateAndProcessImage($imageFieldName, $stmtInventory, $imageData);
        $stmtInventory->send_long_data(5, $imageData);

        // Execute the statement
        if ($stmtInventory->execute()) {
            // Get the last inserted ID
            $lastInsertID = $conn->insert_id;

            // Insert into products table using the same ID
            $sqlProducts = "INSERT INTO products (id, product_name, colourway, release_date, product_details, price, image_data, image_data2, image_data3, image_data4, gender)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Prepare the statement
            $stmtProducts = $conn->prepare($sqlProducts);

            // Bind the parameters
            $stmtProducts->bind_param("issssdbbbbs", $lastInsertID, $productName, $productColor, $productReleaseDate, $productDetails, $productPrice, $imageData, $imageData2, $imageData3, $imageData4, $productGender);

            $imageFieldName = "newProductImage1";
            validateAndProcessImage($imageFieldName, $stmtInventory, $imageData);
            $stmtProducts->send_long_data(6, $imageData);
            
            // Process and insert the second image for the products table
            $imageFieldName = "newProductImage2";
            validateAndProcessImage($imageFieldName, $stmtProducts, $imageData2);
            $stmtProducts->send_long_data(7, $imageData2);

            // Process and insert the third image for the products table
            $imageFieldName = "newProductImage3";
            validateAndProcessImage($imageFieldName, $stmtProducts, $imageData3);
            $stmtProducts->send_long_data(8, $imageData3);

            // Process and insert the fourth image for the products table
            $imageFieldName = "newProductImage4";
            validateAndProcessImage($imageFieldName, $stmtProducts, $imageData4);
            $stmtProducts->send_long_data(9, $imageData4);

            // Execute the statement
            if ($stmtProducts->execute()) {
                echo '<script>alert("Product data inserted successfully.");</script>';
            } else {
                echo '<script>alert("Error inserting product data.");</script>';
            }

            // Close the statement
            $stmtProducts->close();
        } else {
            echo '<script>alert("Error inserting inventory data.");</script>';
        }
        // Close the statement
        $stmtInventory->close();
    }
}

// Modify your function to handle the case where no file is uploaded
function validateAndProcessImage($imageFieldName, $stmt, &$imageData) {
    if (!isset($_FILES[$imageFieldName]) || $_FILES[$imageFieldName]['error'] == UPLOAD_ERR_NO_FILE) {
        // No file uploaded for this image
        $imageData = null;
        return;
    }

    $allowedTypes = ['image/jpeg', 'image/png'];
    $maxFileSize = 40 * 1024 * 1024; // 40 MiB

    $fileType = $_FILES[$imageFieldName]['type'];
    $fileSize = $_FILES[$imageFieldName]['size'];

    // Check file type
    if (!in_array($fileType, $allowedTypes)) {
        die("Error: Invalid file type for $imageFieldName");
    }

    // Check file size
    if ($fileSize > $maxFileSize) {
        die("Error: File size exceeds the limit for $imageFieldName");
    }

    // Process the file
    $imageTmpName = $_FILES[$imageFieldName]["tmp_name"];
    $imageData = file_get_contents($imageTmpName);
}

$conn->close();
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
    var message="Current Promotions Latest News Get it Here! Promo Code:15OFF for 15% Off";
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.4.0/nouislider.min.js">
</script>

<style>

    .adminbodycontent {
        display: center;
        height: auto;
        margin: 20vh;
        margin-left: 50vh;
        margin-right: 50vh;
        background-color: #f9f9f9;
        border: 1px solid #ccc; /* Add border to the container */
        justify-content: center;
        align-items: center;
    }

    .dropdownform {
        text-align: center;
    }
    
    .dropdownform-container{
        display:flex;
        flex-direction: column;
        padding-top: 7vh;
        padding-bottom: 7vh;
        align-items: center;
    }

    .dropdownform-content {
        width: fit-content;
    }

    .dropdownform-content form{
        display: flex;
        flex-direction: column;
    }

    .dropdownform-content button {
        margin-top: 10px;
        background-color: #000000;
            color: #fff;
            border: 1px solid #000000;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
    }

    .form-group {
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
    }

    #newProductDetails {
        word-wrap: break-word;
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
            <span><a href="product_list.php">MEN </a></span>
            <span><a href="product_list2.php"> WOMEN  </a></span>   
            <span><a href="product_list3.php"> UNISEX  </a></span>   
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

    <div class="adminbody">
        <div class="adminbodycontent">   
            <div class="dropdownform-container">         
                <div class="dropdownform">
                    <label for="add_product_form"><h2>Add New Product</h2></label>
                </div>
                <div id="addProductDropdown" class="dropdownform-content" style="display: none;">
                    <form action="" method="post" enctype="multipart/form-data">
                        
                        <div class="form-group">
                            <label for="newProductName">Product Name:</label>
                            <input type="text" id="newProductName" name="newProductName" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="newProductColor">Color:</label>
                            <input type="text" id="newProductColor" name="newProductColor" required>
                        </div>

                        <div class="form-group">    
                            <label for="newProductReleaseDate">Release Date:</label>
                            <input type="date" id="newProductReleaseDate" name="newProductReleaseDate" required>
                        </div>

                        <div class="form-group">
                            <label for="newProductDetails">Product Details:</label>
                            <input type="text" id="newProductDetails" name="newProductDetails" required>
                        </div>

                        <div class="form-group">
                            <label for="newProductBrand">Brand:</label>
                            <input type="text" id="newProductBrand" name="newProductBrand" required>
                        </div>

                        <div class="form-group">
                            <label for="newProductGender">Gender:</label>
                            <select id="newProductGender" name="newProductGender" required>
                                <option value="MEN">MEN</option>
                                <option value="WOMEN">WOMEN</option>
                                <option value="MEN/WOMEN">MEN/WOMEN</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="newProductPrice">Price:</label>
                            <input type="number" id="newProductPrice" name="newProductPrice" min="0.01" step="0.01" required>
                        </div>

                        <div class="form-group">
                            <label for="newProductImage1">Product Image 1:</label>
                            <input type="file" id="newProductImage1" name="newProductImage1" accept="image/*" required>
                        </div>

                        <div class="form-group">
                            <label for="newProductImage2">Product Image 2:</label>
                            <input type="file" id="newProductImage2" name="newProductImage2" accept="image/*" required>
                        </div>

                        <div class="form-group">    
                            <label for="newProductImage3">Product Image 3:</label>
                            <input type="file" id="newProductImage3" name="newProductImage3" accept="image/*" required>
                        </div>

                        <div class="form-group">
                            <label for="newProductImage4">Product Image 4:</label>
                            <input type="file" id="newProductImage4" name="newProductImage4" accept="image/*" required>
                        </div> 
                        
                        
                        <button type="submit">Add Product</button>
                    </form>
                </div>
            </div>    

            <hr class="dropdown-divider">
            
            <div class="dropdownform-container"> 
                <div class="dropdownform">
                    <label for="remove_product_form"><h2>Remove Existing Product</h2></label>
                </div>
                <div id="removeProductDropdown" class="dropdownform-content" style="display: none;">
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="existingProductName">Product Name:</label>
                            <input type="text" id="existingProductName" name="existingProductName" required>
                        </div>

                        <div class="form-group">
                            <label for="existingProductColor">Product Color:</label>
                            <input type="text" id="existingProductColor" name="existingProductColor" required>
                        </div>

                        <button type="submit" name="removeProduct">Remove Product</button>
                    </form>
                </div>
            </div>
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


    const dropdownForms = document.querySelectorAll(".dropdownform");
        const dropdownFormContent = document.querySelectorAll(".dropdownform-content");
        // Add a click event listener to each dropdown bar
        dropdownForms.forEach((dropdownForm, index) => {
            dropdownForm.addEventListener("click", () => {
                const form2 = dropdownFormContent[index];
                if (form2.style.display === "none" || form2.style.display === "") {
                    form2.style.display = "block";
                } else {
                    form2.style.display = "none";
                }
            });
        });

    </script>

</body>
</html>
