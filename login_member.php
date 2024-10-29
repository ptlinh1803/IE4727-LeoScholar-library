<?php
  // The code is basically similar to the login page in lecture 9
  require "db_connect.php";

  // Create new session
  session_start();

  // Detailed check
  if (isset($_POST["email"]) && isset($_POST["password"])) {
    // Get form data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Create hash of password to retrieve accordingly from the DB
    // Identical algorithm to register.php
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Search database for any entry with the given email & password
    $sql = "SELECT * FROM users WHERE email=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $hashed_password);

    if($stmt->execute()) {
      // Assign the session with their user ID
      // Redirect the user to home page
    } else {
      // Error handling
      echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
  }
  
  // Close connections
  $stmt->close();
  $conn->close();

  }
?>