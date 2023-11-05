<?php
session_start();
include 'common.php';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Unset or destroy the session variables
    session_unset();
    session_destroy();
}

// Check if a return URL parameter is set
if (isset($_GET['return_url'])) {
    // Redirect the user back to the page they clicked "Logout" from
    $return_url = $_GET['return_url'];
    header("Location: " . $return_url);
} else {
    // If no return URL is provided, redirect to a default page (e.g., account.php)
    header("Location: account.php");
}
exit();
?>