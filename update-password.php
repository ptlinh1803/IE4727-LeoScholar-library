<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Check if session user_id is set
if (!isset($_SESSION['user_id'])) {
  echo "Session id is not yet set!";
  exit;
}

$user_id = $_SESSION['user_id'];

// Database connection
require "db-connect.php";

// Retrieve POST variables
if (!isset($_POST['settings-current-password']) || !isset($_POST['settings-new-password'])) {
  $_SESSION['message'] = "Required fields missing!";
  header("Location: user-settings.php");
  exit;
}

$current_password = $_POST['settings-current-password'];
$new_password = $_POST['settings-new-password'];

// Check if new password is different from current password
if ($current_password === $new_password) {
  $_SESSION['message'] = "New password must be different from the current password!";
  header("Location: user-settings.php");
  exit;
}

// Encrypt passwords using md5 for comparison
$current_password_hashed = md5($current_password);
$new_password_hashed = md5($new_password);

// Retrieve current password from database
$stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
  $_SESSION['message'] = "Error retrieving password: " . $stmt->error;
  header("Location: user-settings.php");
  exit;
}

$stmt->bind_result($db_password);
$stmt->fetch();
$stmt->close();

// Check if the current password matches the database password
if ($current_password_hashed !== $db_password) {
  $_SESSION['message'] = "Incorrect current password!";
  header("Location: user-settings.php");
  exit;
}

// Check if the new password is the same as the current password in the database
if ($new_password_hashed === $db_password) {
  $_SESSION['message'] = "Nothing to update. New password matches the current password!";
  header("Location: user-settings.php");
  exit;
}

// Update the password in the database
$update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
$update_stmt->bind_param("si", $new_password_hashed, $user_id);

if (!$update_stmt->execute()) {
  $_SESSION['message'] = "Error updating password: " . $update_stmt->error;
} else {
  $_SESSION['message'] = "Password updated successfully!";
}

$update_stmt->close();
$conn->close();

// Redirect to user-settings page with the session message
header("Location: user-settings.php");
exit;
?>
