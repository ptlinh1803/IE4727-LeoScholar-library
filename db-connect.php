<?php
// db-connect.php
$host = 'localhost'; // or your database host
$db = 'leoscholar';
$user = 'root';
$pass = '';
$port = 3307;

$conn = new mysqli($host, $user, $pass, $db, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
