<?php
  // The code is basically similar to the login page in lecture 9
  require "db-connect.php";

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

    if ($stmt->execute()) {
      // Store the result to check the number of rows
      $stmt->store_result();
      
      if ($stmt->num_rows > 0) {
          // Bind the result and assign it to the session if a match is found
          $stmt->bind_result($librarian_id);
          $stmt->fetch();
          $_SESSION['librarian_id'] = $librarian_id;
  
          // Redirect the librarian to the home page
          // Temporarily redir to edit-book.php instead
          header("Location: homepage-librarian.php");
      } else {
          // Alert if no matching entry is found
          echo "<script>alert('No matching librarian found. Please check your credentials.'); window.history.back();</script>";
      }
  } else {
      // Error handling
      echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
  }
  
  // Close connections
  $stmt->close();
  $conn->close();

  }
?>