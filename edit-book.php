<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Get book_id from URL parameter
$book_id = isset($_GET['book_id']) && is_numeric($_GET['book_id']) ? (int) $_GET['book_id'] : null;

// Hardcode the librarian id for now:
// $_SESSION['librarian_id'] = 1;
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
  <script src="logout_confirmation.js"></script>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
      <a href="homepage-librarian.php">
        <img src="img/ui/leoscholar-logo-transparent.png" alt="Logo" />
      </a>

      <!-- Hamburger Menu Icon -->
      <span class="menu-toggle">&#9776;</span>

      <div class="nav-links">
        <a href="homepage-librarian.php">Home</a>
        <a href="#" onclick="confirmLogoutLibrarian()">Logout</a>
      </div>
    </nav>

  <!-- Check if book_id and librarian_id is set, if not, display nothing-here.png-->

  <?php
    if ($book_id === null || !isset($_SESSION['librarian_id'])) {
  ?>
    <div style='display: flex; flex-direction: column; align-items: center; justify-content: center; height: 60vh;'>
      <img src='img/ui/nothing-here.png' alt='Nothing here' style='height: 300px;' />
      <h4>Please select a book or log in if you haven't</h4>
    </div>
  <?php
    } else {
      // Retrieve the book details from the database here
      include "retrieve-book-details.php";
  ?>
<!-- Edit Book Details Container -->
<section id="edit-book-content">
  <div class="edit-book-container" id="edit-detail-container">
    <h1 class="big-blue-h1">Edit Book Details</h1>
    <h4>Editing details of book: <?php echo htmlspecialchars($title); ?></h4>
    <table id="edit-detail-table">

      <!-- Row 1: Book Cover -->
      <tr>
        <form method="POST" action="update-book-details.php" enctype="multipart/form-data">
          <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
          <td class="cell">
            <h4>Book Cover</h4>  
            <img src="img/books/<?php echo htmlspecialchars($cover_path); ?>" alt="Book Cover" />
          </td>
          <td class="cell upload-cell">
            <p>Current book cover: <?php echo htmlspecialchars($cover_path); ?></p>
            <input type="file" id="cover-file" name="cover_path" accept="image/*" required>
            <!-- <button type="button" class="upload-btn">Upload</button> -->
          </td>
          <td class="cell"><button type="submit" class="main-btn">Save</button></td>
        </form>
      </tr>

      <!-- Row 2: Title -->
      <tr>
        <form method="POST" action="update-book-details.php">
          <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
          <td class="cell"><h4>Title</h4></td>
          <td class="cell">
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" readonly>
            <button class="edit-icon" type="button" onclick="toggleEdit('title')">
              <i class="fa-solid fa-pen"></i>
            </button>
          </td>
          <td class="cell"><button type="submit" class="main-btn">Save</button></td>
        </form>
      </tr>

      <!-- Row 3: Author -->
      <tr>
        <form method="POST" action="update-book-details.php">
          <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
          <td class="cell"><h4>Author(s)</h4></td>
          <td class="cell">
            <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($author); ?>" readonly>
            <button class="edit-icon" type="button" onclick="toggleEdit('author')">
              <i class="fa-solid fa-pen"></i>
            </button>
          </td>
          <td class="cell"><button type="submit" class="main-btn">Save</button></td>
        </form>
      </tr>

      <!-- Row 4: Description -->
      <tr>
        <form method="POST" action="update-book-details.php">
          <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
          <td class="cell"><h4>Description</h4></td>
          <td class="cell">
            <textarea readonly id="description" name="description" rows="6"><?php echo htmlspecialchars($description); ?></textarea>
            <button class="edit-icon" type="button" onclick="toggleEdit('description')">
              <i class="fa-solid fa-pen"></i>
            </button>
          </td>
          <td class="cell"><button type="submit" class="main-btn">Save</button></td>
        </form>
      </tr>

      <!-- Row 5: About Author -->
      <tr>
        <form method="POST" action="update-book-details.php">
          <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
          <td class="cell"><h4>About Author(s)</h4></td>
          <td class="cell">
            <textarea readonly id="about_author" name="about_author" rows="6"><?php echo htmlspecialchars($about_author); ?></textarea>
            <button class="edit-icon" type="button" onclick="toggleEdit('about_author')">
              <i class="fa-solid fa-pen"></i>
            </button>
          </td>
          <td class="cell"><button type="submit" class="main-btn">Save</button></td>
        </form>
      </tr>

      <!-- Row 6: E-book -->
      <tr>
        <form method="POST" action="update-book-details.php" enctype="multipart/form-data">
          <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
          <td class="cell"><h4>E-book</h4></td>
          <td class="cell upload-cell">
            <p>Current e-book file: <?php echo htmlspecialchars($ebook_file_path); ?></p>
            <input type="file" id="ebook-file" name="ebook_file_path" accept=".pdf" required>
            <!-- <button type="button" class="upload-btn">Upload</button> -->
          </td>
          <td class="cell"><button type="submit" class="main-btn">Save</button></td>
        </form>
      </tr>

      <!-- Row 7: Audio -->
      <tr>
        <form method="POST" action="update-book-details.php" enctype="multipart/form-data">
          <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
          <td class="cell"><h4>Audio</h4></td>
          <td class="cell upload-cell">
            <p>Current audio file: <?php echo htmlspecialchars($audio_file_path); ?></p>
            <input type="file" id="audio-file" name="audio_file_path" accept=".mp3" required>
            <!-- <button type="button" class="upload-btn">Upload</button> -->
          </td>
          <td class="cell"><button type="submit" class="main-btn">Save</button></td>
        </form>
      </tr>
    </table>
  </div>

<!-- Edit Book Availability Container -->
<div class="edit-book-container" id="edit-availability-container">
  <h1 class="big-blue-h1">Edit Book Availabilities</h1>
  <h4>Editing availabilities of book: <?php echo htmlspecialchars($title); ?></h4>
  <div class="table-wrapper">
    <?php include "availability-table.php"; ?>
  </div>
</div>
</section>

  <?php } ?>
    <!-- footer -->
    <footer class="footer">
    <p>&copy; 2024 LeoScholar. All rights reserved.</p>
  </footer>


  <script src="toggle-edit.js"></script> <!-- Placeholder for future JS for toggle functionality -->
  <script src="validateAvailabilityForm.js"></script>
  <?php include "display-session-message.php" ?>
</body>
</html>