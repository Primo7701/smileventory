<?php
$servername = "localhost";
$username = "root"; // default XAMPP username
$password = ""; // default no password
$dbname = "smileventory_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully"; // optional test
?>
