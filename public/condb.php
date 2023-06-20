<?php

// Database credentials
$servername = "localhost";
$username = "u744544614_badet";
$password = "Chammy1234!";
$dbname = "u744544614_badet";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
