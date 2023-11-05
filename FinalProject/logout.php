<?php
session_start();
include 'common.php';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Unset or destroy the session variables
    session_unset();
    session_destroy();
}

// Generate a new guest user ID and set it in the session
$guest_user_id = generateGuestID();
$_SESSION['user_id'] = $guest_user_id;

// Redirect the user to the login page or any other desired page
header("Location: account.php");
exit();
?>