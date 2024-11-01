<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require 'db-connect.php'; // Adjust the path if necessary

// Check if book_id is set
if (isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
} else {
    $_SESSION['message'] = "Book ID is missing.";
    header("Location: edit-book.php?book_id=" . urlencode($book_id));
    exit;
}

// Define the allowed fields
$allowed_fields = ['cover_path', 'title', 'author', 'description', 'about_author', 'ebook_file_path', 'audio_file_path'];

// Find which field to update
$field_to_update = null;
$value_to_update = null;

// Check for a single valid field in POST data
foreach ($allowed_fields as $field) {
    if (isset($_POST[$field])) {
        if ($field_to_update !== null) { // More than one field found
            $_SESSION['message'] = "Only one field can be updated at a time.";
            header("Location: edit-book.php?book_id=" . urlencode($book_id));
            exit;
        }
        $field_to_update = $field;
        $value_to_update = $_POST[$field];
    }
}

// If no valid field is found
if ($field_to_update === null) {
    $_SESSION['message'] = "No valid field to update.";
    header("Location: edit-book.php?book_id=" . urlencode($book_id));
    exit;
}

// Prepare the SQL statement
$query = "UPDATE books SET $field_to_update = ? WHERE book_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    $_SESSION['message'] = "Error preparing query: " . $conn->error;
    header("Location: edit-book.php?book_id=" . urlencode($book_id));
    exit;
}

// Bind parameters and execute the statement
$stmt->bind_param("si", $value_to_update, $book_id);
if ($stmt->execute()) {
    $_SESSION['message'] = ucfirst(str_replace('_', ' ', $field_to_update)) . " updated successfully.";
} else {
    $_SESSION['message'] = "Error updating " . $field_to_update . ": " . $stmt->error;
}

$stmt->close();
$conn->close();

// Redirect back to edit-book.php
header("Location: edit-book.php?book_id=" . urlencode($book_id));
exit;
