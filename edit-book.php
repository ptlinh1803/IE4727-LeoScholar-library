<?php
// Start session
session_start();

// Get book_id from URL parameter and validate it
$book_id = isset($_GET['book_id']) && is_numeric($_GET['book_id']) ? (int) $_GET['book_id'] : null;
if ($book_id === null) {
  echo "Invalid book ID.";
  exit;
}

// Include database connection
require "db-connect.php";

// Prepare and execute the query
$query = "SELECT cover_path, title, author, about_author, ebook_file_path, audio_file_path FROM books WHERE book_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);

if ($stmt->execute()) {
  $stmt->bind_result($cover_path, $title, $author, $about_author, $ebook_file_path, $audio_file_path);
  if ($stmt->fetch()) {
    // Assign empty string for any NULL fields
    $cover_path = $cover_path ?? "";
    $title = $title ?? "";
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Book Details</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="search-styles.css">
  <link rel="stylesheet" href="edit-book.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <!-- Navigation placeholder -->
  <nav class="navbar">
    <a href="homepage-member.php">
      <img src="img/ui/leoscholar-logo-transparent.png" alt="Logo" />
    </a>
    <span class="menu-toggle">&#9776;</span>
    <div class="nav-links">
      <a href="homepage-member.php">Home</a>
      <a href="search-page.php">Search</a>
      <a href="#">My Shelf</a>
      <a href="#">Contribute</a>
      <div class="dropdown">
        <a href="#" class="profile-link active-page">
          Profile
          <img src="img/ui/drop-down-icon.svg" alt="Arrow Down Icon" />
        </a>
        <div class="dropdown-content">
          <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="user-settings.php" class="active-page">Settings</a>
            <a href="#">Payment</a>
            <a href="#" onclick="confirmLogout()">Logout</a>
          <?php } else { ?>
            <a href="login.html">Log in</a>
            <a href="register.html">Register</a>
          <?php } ?>
        </div>
      </div>
    </div>
  </nav>

  <!-- Edit Book Details Container -->
  <section id="edit-book-content">
  <div id="edit-book-details-container">
    <h1 class="big-blue-h1">Edit Book Details</h1>
    <table id="edit-book-table">
      <!-- Row 1: Book Cover -->
      <tr>
        <form method="POST" action="update-book-details.php">
          <td class="cell-detail-name">
          <h4>Book Cover</h4>  
          <img src="img/books/<?php echo htmlspecialchars($cover_path); ?>" alt="Book Cover" />
          </td>
          <td class="cell-edit upload-cell">
            <p>Current book cover: <?php echo htmlspecialchars($cover_path); ?></p>
            <button type="button" class="upload-btn main-btn">Upload</button>
          </td>
          <td class="cell-save"><button type="submit" class="main-btn">Save</button></td>
        </form>
      </tr>

      <!-- Row 2: Title -->
      <tr>
        <form method="POST" action="update-book-details.php">
          <td class="cell-detail-name"><h4>Title</h4></td>
          <td class="cell-edit">
            <input type="text" id="title" value="<?php echo htmlspecialchars($title); ?>" readonly>
            <button class="edit-icon" type="button" onclick="toggleEdit('title')">
              <i class="fa-solid fa-pen"></i>
            </button>
          </td>
          <td class="cell-save"><button type="submit" class="main-btn">Save</button></td>
        </form>
      </tr>

      <!-- Row 3: Author -->
      <tr>
        <form method="POST" action="update-book-details.php">
          <td class="cell-detail-name"><h4>Author</h4></td>
          <td class="cell-edit">
            <input type="text" id="author" value="<?php echo htmlspecialchars($author); ?>" readonly>
            <button class="edit-icon" type="button" onclick="toggleEdit('author')">
              <i class="fa-solid fa-pen"></i>
            </button>
          </td>
          <td class="cell-save"><button type="submit" class="main-btn">Save</button></td>
        </form>
      </tr>

      <!-- Row 4: About Author -->
      <tr>
        <form method="POST" action="update-book-details.php">
          <td class="cell-detail-name"><h4>About Author</h4></td>
          <td class="cell-edit">
            <textarea readonly id="about_author"><?php echo htmlspecialchars($about_author); ?></textarea>
            <button class="edit-icon" type="button" onclick="toggleEdit('about_author')">
              <i class="fa-solid fa-pen"></i>
            </button>
          </td>
          <td class="cell-save"><button type="submit" class="main-btn">Save</button></td>
        </form>
      </tr>

      <!-- Row 5: E-book -->
      <tr>
        <form method="POST" action="update-book-details.php">
          <td class="cell-detail-name"><h4>E-book</h4></td>
          <td class="cell-edit upload-cell">
            <p>Current e-book file: <?php echo htmlspecialchars($ebook_file_path); ?></p>
            <button type="button" class="upload-btn main-btn">Upload</button>
          </td>
          <td class="cell-save"><button type="submit" class="main-btn">Save</button></td>
        </form>
      </tr>

      <!-- Row 6: Audio -->
      <tr>
        <form method="POST" action="update-book-details.php">
          <td class="cell-detail-name"><h4>Audio</h4></td>
          <td class="cell-edit upload-cell">
            <p>Current audio file: <?php echo htmlspecialchars($audio_file_path); ?></p>
            <button type="button" class="upload-btn main-btn">Upload</button>
          </td>
          <td class="cell-save"><button type="submit" class="main-btn">Save</button></td>
        </form>
      </tr>
    </table>
  </div>

  <!-- Edit Book Availability Container (empty for now) -->
  <div id="edit-book-availability-container" style="width: 100%;">
    <!-- Content to be added later -->
  </div>
  </section>

  <script src="toggle-edit.js"></script> <!-- Placeholder for future JS for toggle functionality -->
</body>
</html>
