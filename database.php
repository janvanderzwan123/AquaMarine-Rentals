<?php

$servername = "127.0.0.1";
$username = "aquamarine";
$password = "admin123";
$dbname = "aquamarine"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo 'Connection Complete';
}


$conn->set_charset("utf8");

?>
