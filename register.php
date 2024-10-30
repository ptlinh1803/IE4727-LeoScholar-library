<?php
// Database connection
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get form data
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $password = $_POST['password'];

  // Extract university ID from email domain
  preg_match('/@e.([a-z]+)\./i', $email, $matches);
  $university_id = strtoupper($matches[1] ?? 'UNKNOWN');

  // Prepare current timestamp
  $created_at = date("Y-m-d H:i:s");

  // Hash the password with md5
  $password = md5($password);

  // Insert data into database
  $sql = "INSERT INTO users (name, email, password, phone, university_id, created_at) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssss", $name, $email, $assword, $phone, $university_id, $created_at);

  if ($stmt->execute()) {
    // Success message and redirect
    echo "<script>alert('Registration successful! You will be redirected to the login page.'); window.location.href = 'login.html';</script>";
  } else {
    // Error handling
    echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
}

// Close connections
$stmt->close();
$conn->close();
}
?>
