<?php
// Include database connection
require "db-connect.php";

// Prepare and execute the query
$query = "SELECT cover_path, title, description, author, about_author, ebook_file_path, audio_file_path FROM books WHERE book_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);

if ($stmt->execute()) {
  $stmt->bind_result($cover_path, $title, $description, $author, $about_author, $ebook_file_path, $audio_file_path);
  if ($stmt->fetch()) {
    // Assign empty string for any NULL fields
    $cover_path = $cover_path ?? "";
    $title = $title ?? "";
    $description = $description ?? "";
    $author = $author ?? "";
    $about_author = $about_author ?? "";
    $ebook_file_path = $ebook_file_path ?? "";
    $audio_file_path = $audio_file_path ?? "";
  } else {
    echo "No book found with the given ID.";
    exit;
  }
} else {
  echo "Error retrieving book details.";
  exit;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>