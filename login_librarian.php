<?php
  // The code is basically similar to the login page in lecture 9
  require "db_connect.php";

  // Create new session if it has not been created yet
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  // Detailed check
  if (isset($_POST["email"]) && isset($_POST["password"])) {
    // Get form data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Hash the received password for comparison with the db
    $password = md5($password);

    // Search database for any entry with the given email & password
    $sql = "SELECT librarian_id FROM librarians WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);

    if($stmt->execute()) {
      // Assign the session with their user ID
      $stmt->bind_result($librarian_id);
      $_SESSION['librarian_id'] = $librarian_id;
      // Redirect the user to home page
      header("Location: homepage-librarian.html");
    } else {
      // Error handling
      echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
  }
  
  // Close connections
  $stmt->close();
  $conn->close();

  }
?>