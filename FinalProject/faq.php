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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.4.0/nouislider.min.js"></script>

    <style>
        .faq {
            padding: 60px;
            background-color: #F5F5F5;
        }

        .faqheader {
            
            margin-left: 0px;
            font-size: 150%;
        }

        .faqdropdown{
            margin-top: 50px;
            display: flex;
            flex-direction: column; /* Display content vertically */
            align-items: center; /* Horizontally center content */
            max-height: 150vh; /* Make the container take up the full viewport height */
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            box-sizing: content-box;
        }

        .faqdropdownbar{
            width: 80%;
            font-size: 120%;
            border-top: 1px solid #ccc; /* Thin top border */
            max-height: 110px; /* Set a sufficiently large value */
            overflow: hidden;
        }

        .faqquestion {
            height: 46px;
            display: flex;
            align-items: center; /* Center vertically */
        }

        .faqcontent {
            font-family: 'Open Sans', 'DM Sans', sans-serif;
            font-weight: lighter;
            font-size: 70%;
            height: 0;
            overflow: hidden;
            transition: height 0.3s ease-in-out;
        }


        .faqdropdown-divider {
            /* Style for the horizontal line */
            border: none;
            height: 1px;
            background-color: #ccc;
            margin: 0px; /* Adjust margin as needed */
            width: 80%;
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

    <div class="faq">
        <div class="faqheader">
            <h2>FREQUENTLY ASKED QUESTIONS</h2>
        </div>

        <div class="faqdropdown">

            <div class="faqdropdownbar">
                <div class="faqquestion">
                    <span>MY SIZE IS SOLD OUT, WHAT NOW?</span>
                </div>
                <div class="faqcontent">
                    <span>Most sneakers are not restocked but you can sign up to receive an email notification when your size is restocked. Just click on the size you're interested in and a window will pop-up. Fill in your details and that's it!</span>
                </div>
            </div>

            <div class="faqdropdownbar">
                <div class="faqquestion">
                    <span>WHEN WILL YOU SHIP MY ORDER? WHEN WILL MY ORDER ARRIVE?</span>
                </div>
                <div class="faqcontent">
                    <span>We try to ship all orders the following business day, unless you’ve ordered products from an external warehouse. We don’t ship on the weekends and on rare occassions, it may take more than one day for your order to leave our warehouse. Your order will arrive 1-10 business days after shipment, you can track it from your profile or from the email we’ve sent you!</span>
                </div>
            </div>

            <div class="faqdropdownbar">
                <div class="faqquestion">
                    <span>HOW CAN I CANCEL MY ORDER?</span>
                </div>
                <div class="faqcontent">
                    <span>If you're a registered customer, just go to your profile and select the order you wish to cancel. Click on the button in the bottom right corner. You can also remove just one product from your order. In case the order was paid, you'll be refunded accordingly. Unfortunately, if you have placed the order as a guest, you have to contact our Customer Care team to help you out.</span>
                </div>
            </div>

            <div class="faqdropdownbar">
                <div class="faqquestion">
                    <span>WHEN WILL I RECEIVE FREE DELIVERY?</span>
                </div>
                <div class="faqcontent">
                    <span>We offer free delivery to VIP customers in selected countries with selected carriers. Check the delivery page.</span>
                </div>
            </div>

            <div class="faqdropdownbar">
                <div class="faqquestion">
                    <span>CAN I PLACE AN ORDER WITHOUT HAVING AN ACCOUNT?</span>
                </div>
                <div class="faqcontent">
                    <span>Yes, it is possible to create an order without having/creating an account. However, if you have an account, you can be a part of the great Footshop Club and get some great benefits with your purchases!</span>
                </div>
            </div>

            <div class="faqdropdownbar">
                <div class="faqquestion">
                    <span>HOW LONG DOES A REFUND TAKE?</span>
                </div>
                <div class="faqcontent">
                    <span>It depends on the payment method. If you've paid by card or bank transfer, the refund may take 2-3 business days to appear in your account from the moment we process your return. If you've paid with PayPal, the refund is the same day we receive your return.</span>
                </div>
            </div>

            <div class="faqdropdownbar">
                <div class="faqquestion">
                    <span>HOW DO I PROCEED WITH FAULTY GOODS?</span>
                </div>
                <div class="faqcontent">
                    <span>Claims work the same way as returns. Just fill out the claim form which you can find HERE and send the goods to our return address. You will get the answer within 30 days of receipt of the package. We will inform you about everything by e-mail. If your claim is accepted, you will get a refund. More info can be found HERE</span>
                </div>
            </div>

            <div class="faqdropdownbar">
                <div class="faqquestion">
                    <span>GLOBAL SHIPPING DISCLAIMER</span>
                </div>
                <div class="faqcontent">
                    <span>We reserve the right to charge higher shipping fees on orders of 3+ pairs of sneakers. Items shipped outside of the EU may be required to pay additional tax in the country of delivery. This does not apply to the USA. If your delivery country charges customs or import duties, you will have to pay these charges. Unfortunately, we have no control over them and cannot inform you of what they will be. Customs policies vary from country to country so if you have any questions, please contact your local customs office for more info. Nike: Due to Nike's new policy, we do not ship Nike and Jordan products outside of the European Union. Exceptions are Switzerland and the United Kingdom.</span>
                </div>
            </div>

            <div class="faqdropdownbar">
                <div class="faqquestion">
                    <span>HOW DO I FIND THE PARCEL TRACKING NUMBER?</span>
                </div>
                <div class="faqcontent">
                    <span>You will get the tracking number automatically by e-mail. If you can not find it or have trouble tracking your order, contact us!</span>
                </div>
            </div>

            <div class="faqdropdownbar">
                <div class="faqquestion">
                    <span>I RECEIVED DAMAGED GOODS, HOW DO I PROCEED NOW?</span>
                </div>
                <div class="faqcontent">
                    <span>We're so sorry! This definitely should not happen and we will, of course, find the solution for you. Please contact our customer support within 3 business days!</span>
                </div>
            </div>

            <div class="faqdropdownbar">
                <div class="faqquestion">
                    <span>HOW LONG WILL IT TAKE FOR MY MONEY TO BE REFUNDED AFTER I RETURN THE GOODS?</span>
                </div>
                <div class="faqcontent">
                    <span>We try to process refunds as soon as we receive packages at our central warehouse in Prague. If you've paid by card/ PayPal, you will receive the refund almost immediately. If you've paid with cash on delivery, only our finance dept. can refund you so they will need a few extra days to do so. We will send you an email when we receive the goods back and when we send you a refund.</span>
                </div>
            </div>

            <div class="faqdropdownbar">
                <div class="faqquestion">
                    <span>HOW CAN I GET A+ FOR IE4717?</span>
                </div>
                <div class="faqcontent">
                    <span>PRAY TO PROF WESLEY 24/7 PLS PLS PLS PLS PLS PLS</span>
                </div>
            </div>

            <hr class="faqdropdown-divider"></div>

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

        // Select all FAQ dropdown bars and content
        const faqdropdownBars = document.querySelectorAll(".faqdropdownbar");
        const faqdropdownContent = document.querySelectorAll(".faqcontent");

        // Add a click event listener to each FAQ dropdown bar
        faqdropdownBars.forEach((faqdropdownBar, index) => {
            faqdropdownBar.addEventListener("click", () => {
                const content = faqdropdownContent[index];
                if (content.style.height === "0px" || content.style.height === "") {
                    content.style.height = content.scrollHeight + 10 + "px"; // Show the content with the content's actual height plus 10px
                } else {
                    content.style.height = "0"; // Set back to 0 to hide
                }
            });
        });
        
    </script>

</body>
</html>
