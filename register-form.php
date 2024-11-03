<?php
// 0. Set up: require "db-connect.php"
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // 1. Get the form data from POST. If any of the 4 fields is not set, echo the error and back
  $name = $_POST['name'] ?? null;
  $email = $_POST['email'] ?? null;
  $phone = $_POST['phone'] ?? null;
  $password = $_POST['password'] ?? null;

  if (!$name || !$email || !$phone || !$password) {
    echo "<script>alert('Error: All fields are required.'); window.history.back();</script>";
    exit;
  }

  // 2. Prepare and execute a query to retrieve from table "users" WHERE email=$email
  $checkEmailSql = "SELECT * FROM users WHERE email = ?";
  $stmt = $conn->prepare($checkEmailSql);
  if (!$stmt) {
    echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
    exit;
  }

  $stmt->bind_param("s", $email);
  if (!$stmt->execute()) {
    echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    exit;
  }

  $stmt->store_result();
  // 3. If the query has >= 1 row (meaning the email is already used by some user), echo the error and back
  if ($stmt->num_rows >= 1) {
    echo "<script>alert('Error: Email is already registered.'); window.history.back();</script>";
    exit;
  }
  $stmt->close();

  // 4. Proceed with the rest of the script, but add error handling for database-related parts
  // Extract university ID from email domain
  preg_match('/@e.([a-z]+)\./i', $email, $matches);
  $university_id = strtoupper($matches[1] ?? 'UNKNOWN');

  // Prepare current timestamp
  $created_at = date("Y-m-d H:i:s");

  // Hash the password with md5
  $password = md5($password);

  // Insert data into database
  $insertSql = "INSERT INTO users (name, email, password, phone, university_id, created_at) VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($insertSql);
  if (!$stmt) {
    echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
    exit;
  }

  $stmt->bind_param("ssssss", $name, $email, $password, $phone, $university_id, $created_at);
  if (!$stmt->execute()) {
    echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    exit;
  }

  // 5. Success message and redirect
  echo "<script>alert('Registration successful! You will be redirected to the login page.'); window.location.href = 'login.php';</script>";

  // Close connections
  $stmt->close();
  $conn->close();
}
?>
