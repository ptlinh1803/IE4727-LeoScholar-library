<?php
// Step 1: Start a session
session_start();

// Step 2: Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
  echo "Error: User not logged in.";
  exit; // Do not redirect
} else {
  $user_id = $_SESSION['user_id'];
}

// Step 3: Require database connection
require "db-connect.php";

// Step 4: Check if categories are posted
if (!isset($_POST['categories'])) {
  $_SESSION['message'] = "Error: No categories selected.";
  header("Location: user-settings.php");
  exit;
} else {
  $selected_categories = $_POST['categories'];
}

// Step 5: Retrieve current favorite categories
$current_categories = [];
$query = "SELECT category FROM category_preference WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
  $_SESSION['message'] = "Error: Failed to retrieve categories.";
  header("Location: user-settings.php");
  exit;
}
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $current_categories[] = $row['category'];
}

// Step 7: Find the differences
$categories_to_add = array_diff($selected_categories, $current_categories);
$categories_to_remove = array_diff($current_categories, $selected_categories);

// Step 8: Insert new categories
foreach ($categories_to_add as $category) {
  $query = "INSERT INTO category_preference (user_id, category) VALUES (?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("is", $user_id, $category);
  if (!$stmt->execute()) {
    $_SESSION['message'] = "Error: Failed to add category.";
    header("Location: user-settings.php");
    exit;
  }
}

// Step 9: Delete removed categories
foreach ($categories_to_remove as $category) {
  $query = "DELETE FROM category_preference WHERE user_id = ? AND category = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("is", $user_id, $category);
  if (!$stmt->execute()) {
    $_SESSION['message'] = "Error: Failed to remove category.";
    header("Location: user-settings.php");
    exit;
  }
}

// Step 10: Success message
$_SESSION['message'] = "Successfully updated favorite categories!";
header("Location: user-settings.php");
exit;
?>
