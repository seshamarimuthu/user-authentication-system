<?php

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "user_authentication";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
