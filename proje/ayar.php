<?php
error_reporting(0);
$servername = "localhost";
$username = "root";
$password = "";
$dbname="adminpanel";

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);
$new=mysqli_set_charset($conn,"utf8");
// Check connection
if ($conn->connect_error) {
  die("Bağlantı hatası: " .$conn->connect_error );
}
?>