<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "shoeshop";

$conn = new mysqli($hostname, $username, $password, $database);

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
            <span>Link 1</span>
            <span>Link 2</span>
            <span>Link 3</span>
            <span>Link 4</span>
        </div>
        <div class="navcenter">
            <span>Logo</span>
        </div>
        <div class="navright">
            <span>Link 1</span>
            <span>Link 2</span>
            <span>Link 3</span>
            <span>Search</span>
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
            <p style="font-size:80px;"> <?php echo $productName; ?> </p>
            <p style="font-size:30px;"> <strong> <?php echo $productprice; ?> </strong> </p>
            <a style="font-size:15px; margin-top:20px;"> COLOURS </a> 
            <?php
           $sql = "SELECT colourway, image_data, product_name FROM products WHERE product_name = '" . $products[0]['product_name'] . "' AND gender = '" . $products[0]['gender'] . "'";

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
                       echo '<a href="product_page.php?product_name=' . urlencode($cell['product_name']) . '&colorway=' . urlencode($cell['colourway']) . '">';
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
                    $columnNames[] = $row['Field'];
                }
            }
            $dynamicConditions = [];
                foreach ($columnNames as $columnName) {
                    $dynamicConditions[] = "`$columnName` <> 0";
                }

             // SQL query to get rows where the specified column's value is not 0
             $dataQuery = "SELECT `US 4.0`,`US 4.5`, `US 5.0`, `US 5.5`, `US 6.0`, `US 6.5`, `US 7.0`, `US 7.5` , `US 8.0`, `US 8.5`, `US 9.0` , `US 9.5`, `US 10.0`, `US 10.5` FROM inventory 
              WHERE product_name = '$productName'
              AND colorway = '$colorway' 
              AND `US 4.0` <> 0 
              AND `US 4.5` <> 0 
              AND `US 5.0` <> 0 
              AND `US 5.5` <> 0 
              AND `US 6.0` <> 0 
              AND `US 6.5` <> 0 
              AND `US 7.0` <> 0 
              AND `US 7.5` <> 0 
              AND `US 8.0` <> 0 
              AND `US 8.5` <> 0 
              AND `US 9.0` <> 0 
              AND `US 9.5` <> 0 
              AND `US 10.0` <> 0 
              AND `US 10.5` <> 0 ";

             $resultData = $conn->query($dataQuery);
            
             echo '<div class="productsizetable">';
               
               // Loop through the array to create table rows
               echo '<div class="productsizerow">';
                    


                    if ($resultData->num_rows > 0) {
                        while ($row = $resultData->fetch_assoc()) {
                            foreach ($columnNames as $columnName) {
                                echo '<div class="productcolourwaycell">';
                                echo '<input type="checkbox" class="custom-checkbox" value="' . $columnName . '">';
                                echo '<label for="checkbox1" class="checkbox-label">' . $columnName . '</label>';
                                echo '</div>';
                            }
                        }
                    } else {
                        echo 'empty';
                    }
                echo '</div>';
               echo '</div>';    

            $conn->close();
            ?>

        </div>
    </div>

    
</body>
</html>


