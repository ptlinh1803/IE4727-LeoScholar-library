<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Require database connection
require 'db-connect.php';

// Check if book_id is set
if (isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
} else {
    $_SESSION['message'] = "Book ID is missing.";
    header("Location: edit-book.php");
    exit;
}

// Define the target directories for each file type
$coverDir = "img/books";
$ebookDir = "database";
$audioDir = "database";

// Allowed fields for non-file inputs
$allowed_fields = ['title', 'author', 'description', 'about_author'];

// Track update status
$updateSuccessful = false;

// Process file inputs
$file_fields = [
    'cover_path' => ['dir' => $coverDir, 'extensions' => ['jpg', 'jpeg', 'png']],
    'ebook_file_path' => ['dir' => $ebookDir, 'extensions' => ['pdf']],
    'audio_file_path' => ['dir' => $audioDir, 'extensions' => ['mp3']]
];

foreach ($file_fields as $field => $info) {
    $targetDir = $info['dir'];
    $allowedExtensions = $info['extensions'];
    
    if (isset($_FILES[$field]) && $_FILES[$field]['error'] == UPLOAD_ERR_OK) {
        $tmpName = $_FILES[$field]['tmp_name'];
        $fileName = basename($_FILES[$field]['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Validate file type
        if (!in_array($fileExt, $allowedExtensions)) {
            $_SESSION['message'] = "Invalid file type for $field. Allowed types: " . implode(', ', $allowedExtensions) . ".";
            header("Location: edit-book.php?book_id=" . urlencode($book_id));
            exit;
        }

        // Append timestamp to the middle of the file name
        $fileNameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
        $timestamp = time();
        $newFileName = $fileNameWithoutExt . '_' . $timestamp . '.' . $fileExt;
        $targetPath = $targetDir . "/" . $newFileName;

        // Check if the directory is writable
        if (!is_writable($targetDir)) {
            die("Directory is not writable.");
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($tmpName, $targetPath)) {
            // Store only the file name in the database
            $fileNameForDb = $newFileName;

            // Update database with the path to the saved file
            $query = "UPDATE books SET $field = ? WHERE book_id = ?";
            $stmt = $conn->prepare($query);

            if ($stmt) {
                $stmt->bind_param("si", $fileNameForDb, $book_id);
                if ($stmt->execute()) {
                    $updateSuccessful = true;
                } else {
                    $_SESSION['message'] = "Error updating $field: " . $stmt->error;
                    header("Location: edit-book.php?book_id=" . urlencode($book_id));
                    exit;
                }
                $stmt->close();
            } else {
                $_SESSION['message'] = "Error preparing query for $field: " . $conn->error;
                header("Location: edit-book.php?book_id=" . urlencode($book_id));
                exit;
            }
        } else {
            $_SESSION['message'] = "Failed to upload file for $field. Temp file: " . $tmpName . ", Target path: " . $targetPath;
            header("Location: edit-book.php?book_id=" . urlencode($book_id));
            exit;
        }
    }
}

// Process other fields
foreach ($allowed_fields as $field) {
    if (isset($_POST[$field])) {
        $value = $_POST[$field];
        $query = "UPDATE books SET $field = ? WHERE book_id = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("si", $value, $book_id);
            if ($stmt->execute()) {
                $updateSuccessful = true;
            } else {
                $_SESSION['message'] = "Error updating $field: " . $stmt->error;
                header("Location: edit-book.php?book_id=" . urlencode($book_id));
                exit;
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = "Error preparing query for $field: " . $conn->error;
            header("Location: edit-book.php?book_id=" . urlencode($book_id));
            exit;
        }
    }
}

// Final success message if everything was successful
if ($updateSuccessful) {
    $_SESSION['message'] = "Book details updated successfully.";
} else {
    $_SESSION['message'] = "No changes were made.";
}

// Close the database connection and redirect
$conn->close();
header("Location: edit-book.php?book_id=" . urlencode($book_id));
exit;
?>
