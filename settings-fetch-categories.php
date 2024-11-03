<?php
require "db-connect.php"; // Missing semicolon added here

$sql_list_categories = "SELECT DISTINCT category FROM books;";
$categories_results = $conn->query($sql_list_categories);

$categories_list = []; // Initialize an array to store the categories

// Get the array of categories
if ($categories_results && $categories_results->num_rows > 0) {
  while ($row = $categories_results->fetch_assoc()) {
      $categories_list[] = $row['category']; // Add each category to the array
  }
} else {
  // Handle query error or no results found
  echo "No categories available or query error: " . $conn->error;
}

// Output checkboxes if categories are found
if (!empty($categories_list)) {
  foreach ($categories_list as $category) {
    
    echo '<label>';
    echo '<input type="checkbox" class="category-checkbox" value="' . htmlspecialchars($category) . '">';
    echo htmlspecialchars($category);
    echo '</label><br>';
  }
}

echo "Category checkboxes generated below:";
?>
