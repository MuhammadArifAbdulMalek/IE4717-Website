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

$registrationSuccess = false;
$registrationMessage = "";
$loginSuccess = false;
$loginMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login-username']) && isset($_POST['login-password'])) {
        // Handle login form submission
        $loginInput = $_POST['login-username'];
        $loginPassword = $_POST['login-password'];
    
        // Query the database to check the credentials
        $loginQuery = "SELECT user_id, username, email, password, first_name FROM users WHERE username = ? OR email = ?";
        $loginStmt = $conn->prepare($loginQuery);
        $loginStmt->bind_param("ss", $loginInput, $loginInput); // Check both username and email
        $loginStmt->execute();
        $loginStmt->bind_result($dbUser_Id,$dbUsername, $dbEmail, $dbPassword, $dbFirst_Name);
    
        if ($loginStmt->fetch()) {
            if (password_verify($loginPassword, $dbPassword)) {
                // Login successful
                $loginSuccess = true;
                $loginMessage = "var_dump($dbUser_Id)";
                $_SESSION['user_id'] = $dbUser_Id;
                $_SESSION['first_name'] = $dbFirst_Name;
                $_SESSION['username'] = $dbUsername;
            } else {
                // Password doesn't match
                $loginMessage = "Invalid password.";
            }
        } else {
            // Username or email not found in the database
            $loginMessage = "User not found.";
        }
    
        $loginStmt->close();
    } else {
        // Get form data for registration
        $first_name = $_POST['first-name'];
        $last_name = $_POST['last-name'];
        $username = $_POST['create-username'];
        $email = $_POST['create-email'];
        $password = $_POST['create-password'];
    
        // Check for duplicate username or email
        $checkQuery = "SELECT username, email FROM users WHERE username = ? OR email = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("ss", $username, $email);
        $checkStmt->execute();
    
        $checkStmt->bind_result($foundUsername, $foundEmail);
    
        $duplicateFound = false;
    
        while ($checkStmt->fetch()) {
            if ($foundUsername === $username) {
                $registrationMessage = "Error: Username already exists.";
                $duplicateFound = true;
                break; // No need to continue checking
            }
            if ($foundEmail === $email) {
                $registrationMessage = "Error: Email already registered.";
                $duplicateFound = true;
                break; // No need to continue checking
            }
        }
    
        $checkStmt->close();
    
        if (!$duplicateFound) {
            // No duplicate found, proceed with the registration
            // Assuming $password contains the user's password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            // Insert the hashed password into the database
            $sql = "INSERT INTO users (username, email, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $username, $email, $hashedPassword, $first_name, $last_name);

            if ($stmt->execute()) {
                // Registration successful
                $registrationSuccess = true;
                $registrationMessage = "Account created successfully.";
            } else {
                // Error occurred during data insertion
                $registrationMessage = "Error creating the account.";
            }
    
            $stmt->close();
        }
    }
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
        var message = "Current Promotions Latest News Get it Here";
        var space = " ";
        var position = 0;

        function scroller() {
            var newtext = space + message.substring(position, message.length) + space + message.substring(0, position);
            var td = document.getElementById("tabledata");
            td.firstChild.nodeValue = newtext;
            position++;
            if (position > message.length) {
                position = 0;
            }
            setTimeout(scroller, 200);
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


    <style>
        .accounts {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-top: 70px;
            margin-bottom: 180px;
        }

        .accounts-container {
            display: flex;
            background-color: #f9f9f9;
            justify-content: center;
            align-items: flex-start;
            border: 1px solid #ccc; /* Add border to the container */
            width: 66%; /* Set width to 66% of the page */
            margin: 0 auto; /* Center the container horizontally */
            padding-bottom: 20px;
        }

        .accounts-form {
            width: 100%; /* Make each form fill the container width */
            text-align: left;
            height: 710px;
            padding-left: 80px;
            padding-top: 40px;
            font-size: 150%;
        }

        .accounts-form h2 {
            margin: 0;
        }

        .accounts-form label {
            display: block;
            font-size: 80%;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .accounts-form input {
            display: block;
            width: 80%;
            padding: 8px;
        }

        .accounts-form button {
            padding: 14px;
            background-color: #000000;
            color: #fff;
            border: 1px solid #000000;
            cursor: pointer;
            margin-top: 30px;
            width: 80%;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
        }

        .accounts-form button:hover {
            background-color: #0056b3;
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
        <a style="text-decoration:none; color:black;" href="product_list.php"><span>UNISEX</span></a>
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

    <div class="accounts">
        <div class="accounts-container">
            <div class="accounts-form">
            <?php if (!isset($_SESSION['user_id']) || !isset($_SESSION['first_name'])): ?>
                    <h2>Login</h2>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="login-username">Username or Email:</label>
                            <input type="text" id="login-username" name="login-username" required>
                        </div>

                        <div class="form-group">
                            <label for="login-password">Password:</label>
                            <input type="password" id="login-password" name="login-password" required>
                        </div>

                        <button type="submit" name="login">Login</button>
                    </form>
                <?php else: ?>
                    <h2>You are already logged in as <?php echo $_SESSION['username']; ?>.</h2>
                    <p style="font-size:66%;">If you want to log in as a different user, please <a href="logout.php">logout</a> first.</p>
                <?php endif; ?>
            </div>

            <div class="accounts-form">
                <h2>Create Account</h2>
                <form action="" method="post" onsubmit="return validateForm();">
                    <div class="form-group">
                        <label for="first-name">First Name:</label>
                        <input type="text" id="first-name" name="first-name" pattern="[A-Za-z]+" required>
                    </div>

                    <div class="form-group">
                        <label for="last-name">Last Name:</label>
                        <input type="text" id="last-name" name="last-name" pattern="[A-Za-z]+" required>
                    </div>

                    <div class="form-group">
                        <label for="create-username">Username:</label>
                        <input type="text" id="create-username" name="create-username" required>
                    </div>

                    <div class="form-group">
                        <label for="create-email">Email:</label>
                        <input type="email" id="create-email" name="create-email" required>
                    </div>

                    <div class="form-group">
                        <label for="create-password">Password:</label>
                        <input type="password" id="create-password" name="create-password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Confirm Password:</label>
                        <input type="password" id="confirm-password" name="confirm-password" required>
                    </div>

                    <button type="submit">Create Account</button>
                </form>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="footerupper">
            <div class="sitemap">
                <a style="font-size: 25px; text-decoration: underline;"> <strong>Quick Directory </strong> </a> <br>
                <table class="sitemaplinks">
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

        function validateForm() {
            if (!checkEmail()) {
                alert("Invalid email format. Please correct it.");
                return false; // Prevent form submission
            }

            if (!validatePassword()) {
                return false; // Prevent form submission
            }

            // Add other form validation logic here if needed
            return true; // Allow form submission if all checks pass
        }

        function checkEmail() {
            var email = document.getElementById("create-email").value;

            if (email.trim() === "") {
                alert("Please fill up your email.");
                return false;
            } else if (!/^[a-zA-Z0-9.-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email)) {
                return false;
            }

            return true;
        }

        function validatePassword() {
            var password = document.getElementById("create-password").value;
            var confirmPassword = document.getElementById("confirm-password").value;

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false; // Prevent form submission
            }

            return true;
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

</body>
</html>