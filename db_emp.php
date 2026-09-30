<?php

// Database Configuration
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "attendance";


$conn = mysqli_connect($servername, $username, $password, $database,3307);


if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

 //echo "Database Connected Successfully!";

?>