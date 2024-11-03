<?php
// 0. Enable error reporting
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// 1. Start the session
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// 2. Check if session id is set
if (!isset($_SESSION['user_id'])) {
  echo "Error: User is not logged in.";
  exit;
} else {
  $user_id = $_SESSION['user_id']; // Store current session id
}

// 3. Require database connection
require "db-connect.php";

// 4. Prepare and execute query to retrieve all distinct categories
$query = "SELECT DISTINCT category FROM books";
$result = mysqli_query($conn, $query);

// Check for query failure
if (!$result) {
  echo "Error retrieving categories: " . mysqli_error($conn);
  exit;
}

// 5. Store query result into an array of categories
$categories = [];
while ($row = mysqli_fetch_assoc($result)) {
  $categories[] = $row['category'];
}

// 6. Prepare and execute query to retrieve the id of favorited categories
$fav_query = "SELECT category FROM category_preference WHERE user_id = '$user_id'";
$fav_result = mysqli_query($conn, $fav_query);

// Check for query failure
if (!$fav_result) {
  echo "Error retrieving favorite categories: " . mysqli_error($conn);
  exit;
}

// 7. Store query result into an array of favorite categories
$fav_categories = [];
while ($fav_row = mysqli_fetch_assoc($fav_result)) {
  $fav_categories[] = $fav_row['category'];
}

// 8. Display checkboxes for each category
foreach ($categories as $category) {
  $checked = in_array($category, $fav_categories) ? 'checked' : '';
  echo "
    <label>
      <input type='checkbox' name='categories[]' class='category-checkbox' value='$category' $checked />
      $category
    </label>
  ";
}
?>
