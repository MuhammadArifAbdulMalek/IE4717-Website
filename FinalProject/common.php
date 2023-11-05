<?php
session_start();

function setUserSession(){
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "shoeshop";
    
    $conn = new mysqli($hostname, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        // The user is a guest, assign a temporary guest ID
        $guest_user_id = generateGuestID();
        $_SESSION['user_id'] = $guest_user_id;
        $user_id = $guest_user_id;
    }

    return $user_id;
}


function generateGuestID() {
    // Generate a unique guest ID, for example, using a combination of a prefix and a random number
    return 'guest_' . uniqid();
}


?>
