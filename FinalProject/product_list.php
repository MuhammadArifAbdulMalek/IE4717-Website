<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "shoeshop";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select unique colors and brands for filtering
$filterSql = "SELECT DISTINCT colorway FROM inventory";
$filter2Sql = "SELECT DISTINCT brand FROM inventory";
$filter3Sql = "SHOW COLUMNS FROM inventory WHERE Field LIKE 'US%'";

// Execute the filter SQL query
$filterResult = $conn->query($filterSql);
$filterResult2 = $conn->query($filter2Sql);
$filterResult3 = $conn->query($filter3Sql);

// Initialize arrays to store unique colors and brands for filtering
$colors = array();
$brands = array();
$sizes = array();

if ($filterResult->num_rows > 0) {
    while ($row = $filterResult->fetch_assoc()) {
        $colors[] = $row['colorway'];
    }
}

if ($filterResult2->num_rows > 0) {
    while ($row = $filterResult2->fetch_assoc()) {
        $brands[] = $row['brand'];
    }
}

if ($filterResult3->num_rows > 0) {
    while ($row = $filterResult3->fetch_assoc()) {
        // Extract the size from the column name and store it
        $sizeColumns[] = str_replace('US ', '', $row['Field']);
    }
}

if (isset($_GET['brand'])) {
    $selectedBrand = $_GET['brand'];
    $productsSql = "SELECT  id, image_data, product_name, price, release_date, colorway, brand FROM inventory WHERE brand = '$selectedBrand'";

    // Execute the products SQL query
    $productsResult = $conn->query($productsSql);

    // Initialize an array to store the retrieved product data
    $products = array();

    if ($productsResult->num_rows > 0) {
        while ($row = $productsResult->fetch_assoc()) {
            $products[] = $row;
        }
    }

}  else {

// SQL query to select all fields for displaying products
$productsSql = "SELECT id, image_data, product_name, price, release_date, colorway, brand FROM inventory";

// Execute the products SQL query
$productsResult = $conn->query($productsSql);

// Initialize an array to store the retrieved product data
$products = array();

if ($productsResult->num_rows > 0) {
    while ($row = $productsResult->fetch_assoc()) {
        $products[] = $row;
    }
}
}

// Close the database connection
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.4.0/nouislider.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.4.0/nouislider.min.js"></script>
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
            <span>MEN</span>
            <span>WOMEN</span>
            <span>UNISEX</span>
        </div>
        <div class="navcenter">
            <span><a href="index.php"> Logo </a></span>
        </div>
        <div class="navright">
            <span><a href= "account.php"> <img src="assets/Images/Icons/account.png"> </a></span>
            <span><a href= "faq.php"> <img src="assets/Images/Icons/FAQ.png"> </a></span>
            <span><a href= "findus.php"> <img src="assets/Images/Icons/map.png"> </a></span>
        </div>
    </nav>

    <div class="listing">

        <div class="mensheader">
                <h2>MEN'S SHOES</h2>
                    <p>Footshop offers men's Nike, Converse, Supra, Vans, Jordan, Puma, New Balance, adidas Originals and more street style shoes.<br> These original designs are made from high quality materials.</p>
                    <br>
                    <span>Link 1</span> 
                    <span>Link 2</span>
                    <span>Link 3</span>
                    <span>Link 4</span>
        </div>

        <div class="mensfilter">

            <div class="size-filter">
                <div class="dropdown">
                    <div class="dropdown-bar">
                        <label for="size-filter">Sizes</label>
                    </div>
                    <div class="checkbox-form">
                        <form>
                            <?php foreach ($sizeColumns as $size): ?>
                                <?php
                                // Count the number of rows with available size and get the product IDs
                                $sizeCount = 0;
                                $availableProductIds = array();
                                $conn = new mysqli($hostname, $username, $password, $database);

                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                $sizeQuery = "SELECT COUNT(*) as count, GROUP_CONCAT(id) as product_ids FROM inventory WHERE `US $size` > 0";
                                $sizeResult = $conn->query($sizeQuery);

                                if ($sizeResult->num_rows > 0) {
                                    $sizeRow = $sizeResult->fetch_assoc();
                                    $sizeCount = $sizeRow['count'];
                                    $productIds = $sizeRow['product_ids'];
                                    $availableProductIds[$size] = explode(',', $productIds);
                                }

                                $conn->close();
                                ?>

                                <label for="size-<?php echo $size; ?>">
                                    <input type="checkbox" id="size-<?php echo $size; ?>" class="size-filter" value="<?php echo $size; ?>" data-product-ids="<?php echo implode(',', $availableProductIds[$size]); ?>">
                                    Size <?php echo $size; ?> <span style="font-weight: normal; color: #B0B0B0;"> (<?php echo $sizeCount; ?>) </span>
                                    <!-- <span style="font-weight: normal; color: #B0B0B0;"> IDs: <?php echo implode(',', $availableProductIds[$size]); ?></span> -->
                                </label>
                            <?php endforeach; ?>
                        </form>
                    </div>
                </div>
            </div>

            <hr class="dropdown-divider">

            <div class="brand-filter">
                <div class="dropdown">
                    <div class="dropdown-bar">
                        <label for="brand-filter">Brands</label>
                    </div>
                    <div class="checkbox-form">
                        <form>
                            <?php foreach (array_unique($brands) as $brand): ?>
                                <label for="brand-<?php echo $brand; ?>">
                                    <input type="checkbox" id="brand-<?php echo $brand; ?>" class="brand-filter" value="<?php echo $brand; ?>"> <?php echo $brand; ?>
                                </label>
                            <?php endforeach; ?>
                        </form>
                    </div>
                </div>
            </div>

            <hr class="dropdown-divider">

            <div class="color-filter">
                <div class="dropdown">
                    <div class="dropdown-bar">
                        <label for="color-filter">Colors</label>
                    </div>
                    <div class="checkbox-form">
                        <form>
                            <?php foreach ($colors as $color): ?>
                                <label for="color-<?php echo $color; ?>">
                                    <input type="checkbox" id="color-<?php echo $color; ?>" class="color-filter" value="<?php echo $color; ?>">
                                    <?php echo $color; ?>
                                </label>
                            <?php endforeach; ?>
                        </form>
                    </div>
                </div>
            </div>

            <hr class="dropdown-divider">

            
            <!-- <button id="clear-filters">Clear Filters</button> -->
            
            
            <!-- <div class="price-range-filter">
                <label for="price-range">Price Range</label>
                <div id="price-range"></div>
                <div id="min-max-values">
                    <input type="number" id="min-value" class="no-spinners" placeholder="Min" step="1">
                    <input type="number" id="max-value" class="no-spinners" placeholder="Max" step="1">
                </div>
                <button type="button" id="apply-price-filter">Apply Filter</button>
            </div>

            <hr class="dropdown-divider"> -->
                    
        </div>

        <div class="product_listing">
            <div class="product_listing_header">
                <div class="product_listing_total_products">
                    Products:
                </div>
                <!-- <div class="product_listing_sorting">
                    <select id="sort-select">
                        <option value="default" disabled selected>Sort By:</option>
                        <option value="highest-price">Highest Price</option>
                        <option value="lowest-price">Lowest Price</option>
                        <option value="new-arrivals">New Arrivals</option>
                    </select>
                </div> -->
            </div>
            
            <div class="product_listing_grids">
                <div class="product_listing_grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product_listing_product" data-id="<?php echo $product['id']; ?>" data-colorway="<?php echo $product['colorway']; ?>" data-brand="<?php echo $product['brand']; ?>">
                            <a href="product_page.php?id=<?php echo urlencode($product['id']); ?>&product_name=<?php echo urlencode($product['product_name']); ?>&colorway=<?php echo urlencode($product['colorway']); ?>">
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image_data']); ?>" alt="<?php echo $product['product_name']; ?>">
                                <p><?php echo $product['product_name']; ?></p>
                                <p>$<?php echo $product['price']; ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                    
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





<script>

// Checkbox Filters
// Select all dropdown bars and checkbox forms
const dropdownBars = document.querySelectorAll(".dropdown-bar");
const checkboxForms = document.querySelectorAll(".checkbox-form");

// Add a click event listener to each dropdown bar
dropdownBars.forEach((dropdownBar, index) => {
    dropdownBar.addEventListener("click", () => {
        const form = checkboxForms[index];
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    });
});

// Filtering
// Get all brand, color, and size checkboxes
const brandCheckboxes = document.querySelectorAll('.brand-filter input[type="checkbox"]');
  const colorCheckboxes = document.querySelectorAll('.color-filter input[type="checkbox"]');
  const sizeCheckboxes = document.querySelectorAll('.size-filter input[type="checkbox"]');

  // Add event listeners to all checkboxes
  brandCheckboxes.forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
      filterProducts();
    });
  });

  colorCheckboxes.forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
      filterProducts();
    });
  });

  sizeCheckboxes.forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
      filterProducts();
    });
  });

  // Function to filter products by brand, color, and size
  function filterProducts() {
    // Get all selected brand checkboxes
    const selectedBrands = Array.from(brandCheckboxes)
      .filter(checkbox => checkbox.checked)
      .map(checkbox => checkbox.value);

    // Get all selected color checkboxes
    const selectedColors = Array.from(colorCheckboxes)
      .filter(checkbox => checkbox.checked)
      .map(checkbox => checkbox.value);

    // Get all selected size checkboxes
    const selectedSizes = Array.from(sizeCheckboxes)
      .filter(checkbox => checkbox.checked)
      .map(checkbox => checkbox.getAttribute('data-product-ids').split(','))
      .flat();

    // Get all product containers
    const productContainers = document.querySelectorAll('.product_listing_product');

    // Iterate through product containers and filter based on selected brands, colors, and sizes
    productContainers.forEach(function (container) {
      const brand = container.getAttribute('data-brand');
      const colorway = container.getAttribute('data-colorway');
      const productID = container.getAttribute('data-id');

      if (
        (selectedBrands.length === 0 || selectedBrands.includes(brand)) &&
        (selectedColors.length === 0 || selectedColors.includes(colorway)) &&
        (selectedSizes.length === 0 || selectedSizes.includes(productID))
      ) {
        container.style.display = 'block';
      } else {
        container.style.display = 'none';
      }
    });

    
  }

  // Call the filterProducts function to initialize the filtering
  filterProducts();

  // Function to clear all selected checkboxes
  function clearFilters() {
        brandCheckboxes.forEach(function (checkbox) {
            checkbox.checked = false;
        });

        colorCheckboxes.forEach(function (checkbox) {
            checkbox.checked = false;
        });

        sizeCheckboxes.forEach(function (checkbox) {
            checkbox.checked = false;
        });

        // Reset the filtering
        filterProducts();
    }

    // Add an event listener to the "Clear Filters" button
    const clearFiltersButton = document.getElementById('clear-filters');
    if (clearFiltersButton) {
        clearFiltersButton.addEventListener('click', clearFilters);
    }

</script>


</body>

</html>
