<?php
// Start the session
session_start();

// Check if session ID is set
if (!isset($_SESSION['user_id'])) {
  $_SESSION['message'] = "Session id is not yet set!";
  header('Location: user-settings.php');
  exit();
} else {
  $user_id = $_SESSION['user_id'];
}

// Attempt to connect to the database
require "db-connect.php";

// Check if settings-name and settings-phone are set in POST
if (!isset($_POST['settings-name']) || !isset($_POST['settings-phone'])) {
  $_SESSION['message'] = "Error: Name and phone information are required.";
  header('Location: user-settings.php');
  exit();
}

$name = $_POST['settings-name'];
$phone = $_POST['settings-phone'];

// Retrieve the current name and phone from the database for comparison
$query = "SELECT name, phone FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
  $_SESSION['message'] = "Error retrieving current user information.";
  header('Location: user-settings.php');
  exit();
}

$result = $stmt->get_result();
if ($result->num_rows == 0) {
  $_SESSION['message'] = "Error: User not found.";
  header('Location: user-settings.php');
  exit();
}

$current_user = $result->fetch_assoc();
$current_name = $current_user['name'];
$current_phone = $current_user['phone'];

// Compare the POST data with the current database data
if ($name === $current_name && $phone === $current_phone) {
  $_SESSION['message'] = "No changes detected. Your information is the same as in the database.";
  header('Location: user-settings.php');
  exit();
}

// Prepare and execute the update query
$update_query = "UPDATE users SET name = ?, phone = ? WHERE user_id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("ssi", $name, $phone, $user_id);

if ($update_stmt->execute()) {
  $_SESSION['message'] = "Your information has been updated successfully.";
} else {
  $_SESSION['message'] = "Error updating your information.";
}

// Close the statements and the database connection
$stmt->close();
$update_stmt->close();
$conn->close();

// Redirect back to user-settings.php
header('Location: user-settings.php');
exit();
?>
