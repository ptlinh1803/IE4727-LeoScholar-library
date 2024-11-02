<?php
  // Establish connection with database
  // Syntax: IP address, username, password, db name 
  $conn = new mysqli('localhost', 'root', '', 'leoscholar');
  // Error message if fail to connect
  if ($conn->connect_error) {
    echo "Error: Could not connect to database. ". $conn->connect_error;
    exit;
  }
?>
