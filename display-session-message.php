<?php
session_start();
if (isset($_SESSION['message'])) {
  echo "<script>alert('" . $_SESSION['message'] . "');</script>";
  unset($_SESSION['message']); // Clear the message after displaying it
}
?>