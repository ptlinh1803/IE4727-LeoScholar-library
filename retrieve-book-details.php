<?php
// Step 3: Include database connection
  require "db-connect.php";

  // Step 4: Prepare and execute the query
  $query = "SELECT cover_path, title, author, about_author, ebook_file_path, audio_file_path FROM books WHERE id = $book_id";
  $result = mysqli_query($conn, $query);

  if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit();
  }

  // Step 5: Store the query result into variables
  if ($row = mysqli_fetch_assoc($result)) {
    $cover_path = $row['cover_path'];
    $title = $row['title'];
    $author = $row['author'];
    $about_author = $row['about_author'];
    $ebook_file_path = $row['ebook_file_path'];
    $audio_file_path = $row['audio_file_path'];
  } else {
    echo "Error: Book not found.";
    exit();
  }

  // Close the result set
  mysqli_free_result($result);
?>