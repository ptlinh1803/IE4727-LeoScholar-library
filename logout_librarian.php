<?php
  // Create new session if it has not been created yet (somehow???)
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  // Store to test if they *were* logged in
  $old_user = $_SESSION['librarian_id'];
  
  // Destroy session
  unset($_SESSION['librarian_id']);
  session_destroy();

  // Take the user back
  header("Location: index.php");
?>