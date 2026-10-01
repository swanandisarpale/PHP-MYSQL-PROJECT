<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "emp_mng",
    3307
);

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);

}

?>