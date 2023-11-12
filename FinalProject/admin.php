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
    if (isset($_POST['existingProduct'])) {
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

        $sqlInventory = "INSERT INTO inventory (product_name, colorway, release_date, brand, price, image_data, M, F) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtInventory = $conn->prepare($sqlInventory);
        $stmtInventory->bind_param("ssssdbii", $productName, $productColor, $productReleaseDate, $productBrand, $productPrice, $imageData, $MValue, $FValue);

        $imageFieldName = "newProductImage1";
        validateAndProcessImage($imageFieldName, $stmtInventory, 0);
        $imageFileName = $_FILES[$imageFieldName]["name"];
        $imageTmpName = $_FILES[$imageFieldName]["tmp_name"];
        $imageData = file_get_contents($imageTmpName);
        $stmtInventory->send_long_data(0, $imageData);

        if ($stmtInventory->execute()) {
            echo '<script>alert("Inventory data inserted successfully.");</script>';
        } else {
            echo '<script>alert("Error inserting inventory data.");</script>';
        }
        // Close the statement
        $stmtInventory->close();
        
        // Get the last inserted product ID
    $lastProductID = $conn->insert_id;


    // Insert into products table
    $sqlProducts = "INSERT INTO products (product_name, colourway, release_date, product_details, price, image_data, image_data2, image_data3, image_data4, gender)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmtProducts = $conn->prepare($sqlProducts);
    
    // Bind the parameters
    $stmtProducts->bind_param("ssssdbbbbs", $productName, $productColor, $productReleaseDate, $productDetails, $productPrice, $imageData, $imageData2, $imageData3, $imageData4, $productGender);

    // Insert image2, image3, and image4
    for ($i = 2; $i <= 4; $i++) {
        $imageFieldName = "newProductImage" . $i;
        validateAndProcessImage($imageFieldName, $stmtProducts, $i - 2);
        $imageFileName = $_FILES[$imageFieldName]["name"];
        $imageTmpName = $_FILES[$imageFieldName]["tmp_name"];
        $imageData = file_get_contents($imageTmpName);
        $stmtProducts->send_long_data($i - 2, $imageData);
    }

    // Execute the statement
    if ($stmtProducts->execute()) {
        echo '<script>alert("Products data inserted successfully.");</script>';
    } else {
        echo '<script>alert("Error inserting products data.");</script>';
    }

    // Close the statement
    $stmtProducts->close();

    }
 
}

function validateAndProcessImage($imageFieldName, $stmt, $paramIndex) {
    if (!isset($_FILES[$imageFieldName]) || $_FILES[$imageFieldName]['error'] == UPLOAD_ERR_NO_FILE) {
        // No file uploaded for this image
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
    $imageFileName = $_FILES[$imageFieldName]["name"];
    $imageTmpName = $_FILES[$imageFieldName]["tmp_name"];
    $imageData = file_get_contents($imageTmpName);
    $stmt->send_long_data($paramIndex, $imageData);
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
                                    </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="dropdown" style="width: 110px; position: relative;">
                            <div class="dropdownbar" style="text-align:left; position: relative; display: inline-block; font-size: 90%;">                                      
                                    <label for=user-account>Hi, <?php echo $_SESSION['first_name']; ?></label>
                                    <div class="dropdown-content" style="text-align:right; display: none; position: absolute; background-color: white; padding: 10px; top: 100%; right: 0; z-index: 1;">
                                    <a href="logout.php?return_url=<?php echo urlencode($_SERVER['REQUEST_URI']);?>">Logout</a>
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
                        <label for="existingProduct">Select Product to Remove:</label>
                        <input type="text" id="existingProduct" name="existingProduct" required>

                        <button type="submit">Remove Product</button>
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
