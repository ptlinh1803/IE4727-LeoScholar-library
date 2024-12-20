<?php
// Include database connection
require "db-connect.php";
include "display-session-message.php";

// Prepare and execute the query
$query = "SELECT cover_path, title, description, author, about_author, ebook_file_path, audio_file_path FROM books WHERE book_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);

if ($stmt->execute()) {
  $stmt->bind_result($cover_path, $title, $description, $author, $about_author, $ebook_file_path, $audio_file_path);
  if ($stmt->fetch()) {
    // Assign empty string for any NULL fields, default.png for $cover_path specifically
    $cover_path = $cover_path ?? "default.png";
    $title = $title ?? "";
    $description = $description ?? "";
    $author = $author ?? "";
    $about_author = $about_author ?? "";
    $ebook_file_path = $ebook_file_path ?? "";
    $audio_file_path = $audio_file_path ?? "";
  } else {
    $_SESSION['alert'] = "No book found with the given ID.";
    header("Location: homepage-librarian.php");
    exit();
  }
} else {
  $_SESSION['alert'] = "Error retrieving book details.";
  header("Location: homepage-librarian.php");
  exit;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>