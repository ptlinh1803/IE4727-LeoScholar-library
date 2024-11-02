<?php
// Step 0: Set up
session_start();
require 'db-connect.php';

// Step 1: Validate 'book_id' in $_POST
if (!isset($_POST['book_id'])) {
  $_SESSION['message'] = 'Book ID is missing.';
  header("Location: edit-book.php");
  exit();
}
$book_id = $_POST['book_id'];

// Step 2: Get 'branch_id', 'available_copies', and 'shelf' from $_POST
$branch_id = $_POST['branch_id'] ?? null;
$available_copies = $_POST['available_copies'] ?? null;
$shelf = $_POST['shelf'] ?? null;

if (!$branch_id || !is_numeric($available_copies) || !is_string($shelf)) {
  $_SESSION['message'] = 'All fields are required and must be valid.';
  header("Location: edit-book.php?book_id=$book_id");
  exit();
}

// Step 3: Prepare and execute a query to retrieve 'available_copies' and 'shelf'
$query = "SELECT available_copies, shelf FROM book_availability WHERE book_id = ? AND branch_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt || !$stmt->bind_param("ii", $book_id, $branch_id) || !$stmt->execute()) {
  $_SESSION['message'] = 'Error retrieving availability data.';
  header("Location: edit-book.php?book_id=$book_id");
  exit();
}

$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);

// Step 4: Check the number of existing entries
if (count($rows) > 1) {
  $_SESSION['message'] = 'book_id and branch_id combination is not unique.';
  header("Location: edit-book.php?book_id=$book_id");
  exit();
}

// Step 5: If no entry is found, insert new record
if (count($rows) === 0) {
  // No existing record, we insert a new one
  $insertQuery = "INSERT INTO book_availability (book_id, branch_id, available_copies, shelf) VALUES (?, ?, ?, ?)";
  $insertStmt = $conn->prepare($insertQuery);
  if (!$insertStmt || !$insertStmt->bind_param("iiis", $book_id, $branch_id, $available_copies, $shelf) || !$insertStmt->execute()) {
    $_SESSION['message'] = 'Error inserting availability data.';
    header("Location: edit-book.php?book_id=$book_id");
    exit();
  }
  $_SESSION['message'] = 'Availability data successfully added.';
  header("Location: edit-book.php?book_id=$book_id");
  exit();
}

// Step 6: If one entry is found, check the available copies
$existing_entry = $rows[0];

// Step 7: If $available_copies is less than 0, store error and redirect
if ($available_copies < 0) {
  $_SESSION['message'] = 'Number of available copies must be greater than or equal to 0.';
  header("Location: edit-book.php?book_id=$book_id");
  exit();
}

// Step 8: If $available_copies is 0, delete the entry
if ($available_copies == 0) {
  $deleteQuery = "DELETE FROM book_availability WHERE book_id = ? AND branch_id = ?";
  $deleteStmt = $conn->prepare($deleteQuery);
  if (!$deleteStmt || !$deleteStmt->bind_param("ii", $book_id, $branch_id) || !$deleteStmt->execute()) {
    $_SESSION['message'] = 'Error deleting availability data.';
    header("Location: edit-book.php?book_id=$book_id");
    exit();
  }
  $_SESSION['message'] = 'Availability data successfully deleted.';
  header("Location: edit-book.php?book_id=$book_id");
  exit();
}

// Step 9: Update the entry if $available_copies > 0
$updateQuery = "UPDATE book_availability SET available_copies = ?, shelf = ? WHERE book_id = ? AND branch_id = ?";
$updateStmt = $conn->prepare($updateQuery);
if (!$updateStmt || !$updateStmt->bind_param("isii", $available_copies, $shelf, $book_id, $branch_id) || !$updateStmt->execute()) {
  $_SESSION['message'] = 'Error updating availability data.';
  header("Location: edit-book.php?book_id=$book_id");
  exit();
}

// Step 10: Store success message and redirect
$_SESSION['message'] = 'Availability data successfully updated.';
header("Location: edit-book.php?book_id=$book_id");
exit();
?>
