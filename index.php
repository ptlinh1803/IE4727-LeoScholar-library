<?php
include 'db-connect.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// if librarian --> cannot access, redirect to homepage-librarian.php
if (isset($_SESSION['librarian_id'])) {
  header('Location: homepage-librarian.php');
  exit();
}

// for testing only
// $_SESSION['user_id'] = 1;

// If not log in
if (!isset($_SESSION['user_id'])) {
  $message = "Hello, Guest! It seems you're not logged in. Please log in to access exclusive features and personalized content!";
}
else {
  // Fetch user's name
  $user_id = $_SESSION['user_id'];
  $get_user_query = "SELECT name FROM users WHERE user_id = ?";
  $stmt = $conn->prepare($get_user_query);
  $stmt->bind_param("i", $user_id); // Bind user_id as an integer
  $stmt->execute();
  $user_result = $stmt->get_result();

  $user_name = '';
  if ($user_result->num_rows > 0) {
      $user_row = $user_result->fetch_assoc();
      $user_name = $user_row['name'];
  } else {
    $message = "User not found.";
  }

  $stmt->close(); // Close the statement

  // Fetch suggested books based on user's liked categories
  $get_books_query = "
      SELECT * FROM books 
      WHERE category IN (
          SELECT category FROM category_preference WHERE user_id = ?
      )";
  $stmt = $conn->prepare($get_books_query);
  $stmt->bind_param("i", $user_id); // Bind user_id as an integer
  $stmt->execute();
  $books_result = $stmt->get_result();

  $suggested_books = []; // Initialize the array for suggested books
  if ($books_result->num_rows > 0) {
    $message = "Discover our personalized book recommendations tailored just for you, based on your preferred categories!";
    while ($book_row = $books_result->fetch_assoc()) {
        $suggested_books[] = $book_row; // Store each book in the array
    }
  } else {
    $message = "It looks like you haven't set any preference categories yet. Please visit your User Settings to customize your preferences and enhance your experience!";
  }

  $stmt->close(); // Close the statement
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LeoScholar</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles.css" />
    <script src="logout_confirmation.js" defer></script>
  </head>
  <body>
    <!-- After login.php redirect to here, display the session message (do the same for homepage-librarian.php) -->
    <?php include "display-session-message.php"?>
    
    <!-- Navbar -->
    <nav class="navbar">
      <a href="index.php">
        <img src="img/ui/leoscholar-logo-transparent.png" alt="Logo" />
      </a>

      <!-- Hamburger Menu Icon -->
      <span class="menu-toggle">&#9776;</span>

      <div class="nav-links">
        <a href="index.php" class="active-page">Home</a>
        <a href="search-page.php">Search</a>
        <a href="my-shelf.php">My Shelf</a>
        <a href="user-contribution.php">Contribute</a>
        <div class="dropdown">
          <a href="#" class="profile-link active-page">
            Profile
            <img src="img/ui/drop-down-icon.svg" alt="Arrow Down Icon" />
          </a>
          <div class="dropdown-content">
            <?php if (isset($_SESSION['user_id'])) { ?>
              <a href="user-settings.php">Settings</a>
              <a href="payment.php">Payment</a>
              <a href="#" onclick="confirmLogout()">Logout</a>
            <?php } else { ?>
              <a href="login.php">Log in</a>
              <a href="register.html">Register</a>
            <?php } ?>
          </div>
        </div>
      </div>
    </nav>

    <!-- Header -->
    <header class="header">
      <div>
      <h2>
          <?php
            if (empty($user_name)) {
                echo "Welcome to";
            } else {
                echo "Welcome back, " . htmlspecialchars($user_name) . "!";
            }
          ?>
      </h2>
        <img src="img/ui/leoscholar-transparent.png" alt="Logo" />
        <p>A Digital Library for a Knowledgeable Singapore</p>

        <form class="search-bar" action="search-page.php" method="GET">
          <input type="text" name="searchQuery" placeholder="Quick Search..." />
          <button type="submit" class="submit-button" style="color: white;">
            Go
          </button>
        </form>
      </div>
    </header>

    <!-- What would you like to do today section -->
    <section class="services">
      <h1 class="big-blue-h1">What would you like to do today?</h1>
      <div class="service-button-container">
        <button class="warm-gradient-button" onclick="redirectToPage('user-settings.php')">
          <img
            src="img/ui/register-membership.png"
            alt="Register Membership Icon"
          />
          <span>Account Settings</span>
        </button>
        <button class="warm-gradient-button" onclick="redirectToPage('search-page.php')">
          <img src="img/ui/search.png" alt="Advanced Search Icon" />
          <span>Discover Books</span>
        </button>
        <button class="warm-gradient-button" onclick="redirectToPage('my-shelf.php')">
          <img src="img/ui/book.png" alt="Loans & Reservations Icon" />
          <span>Loans & Reservations</span>
        </button>
        <button class="warm-gradient-button" onclick="redirectToPage('payment.php')">
          <img src="img/ui/pay.png" alt="Pay fines & fees Icon" />
          <span>Pay fines & fees</span>
        </button>
        <button class="warm-gradient-button" onclick="redirectToPage('search-page.php?format%5B%5D=E-book&format%5B%5D=Audio+Book')">
          <img src="img/ui/e-book.png" alt="Digital Resources Icon" />
          <span>Digital Resources</span>
        </button>
        <button class="warm-gradient-button" onclick="redirectToPage('user-contribution.php')">
          <img src="img/ui/donate.png" alt="Donate a book Icon" />
          <span>Donate a book</span>
        </button>
      </div>
    </section>

    <!-- Discover books section -->
    <section class="discover-books">
      <h1 class="big-blue-h1">Discover books</h1>
      <p><?php echo htmlspecialchars($message); ?></p>

      <?php if (!empty($suggested_books)) { ?>
        <div class="books-grid">
            <?php 
              // Limit the number of books displayed initially
              // $initial_display_count = min(count($suggested_books), 6);
              foreach ($suggested_books as $book) { 
              ?>
                <a href="book-details.php?book_id=<?php echo $book['book_id']; ?>" class="book-wrapper"> 
                    <img src="img/books/<?php echo $book['cover_path']; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" />
                    <p class="title"><?php echo htmlspecialchars($book['title']); ?></p>
                    <p class="author"><?php echo htmlspecialchars($book['author']); ?></p>
                </a>
            <?php } ?>
        </div>
        
        <?php if (count($suggested_books) > 6) { ?>
            <button class="see-all-button" id="toggle-button">See all</button>
        <?php } ?>
        
      <?php } else { ?>
          <div class="no-books-message">
              <img src="img/ui/nothing-here.png" alt="Nothing here" />
          </div>
          <?php if (!isset($_SESSION['user_id'])) { ?>
            <div class="login-register-buttons">
              <button class="see-all-button" onclick="location.href='login.php'">Login</button>
              <button class="see-all-button" onclick="location.href='register.html'">Register</button>
            </div>
          <?php } else { ?>
            <button class="see-all-button" onclick="location.href='user-settings.php'">Set your preference</button>
          <?php } ?>
      <?php } ?>

    </section>

    <!-- About us section -->
    <section class="about-us">
      <div class="about-container">
        <div class="about-columns">
          <div class="logo-column">
            <img
              src="img/ui/leoscholar-logo-vertical-transparent.png"
              alt="LeoScholar Logo"
            />
          </div>
          <div class="description-column">
            <h1 class="big-blue-h1">About us</h1>
            <p>
              LeoScholar is an integrated library portal that unites scholarly
              resources from public tertiary institutions in Singapore.
              <br /><br />
              By providing access to resources across universities, it fosters
              interdisciplinary research and collaboration, essential for
              addressing multifaceted problems.
            </p>
          </div>
        </div>
        <div class="university-logos">
          <img src="img/ui/ntu-logo.png" alt="NTU Logo" />
          <img src="img/ui/nus-logo.png" alt="NUS Logo" />
          <img src="img/ui/smu-logo.jpg" alt="SMU Logo" />
          <img src="img/ui/sutd-logo.png" alt="SUTD Logo" />
          <img src="img/ui/sit-logo.svg" alt="SIT Logo" />
          <img src="img/ui/suss-logo.png" alt="SUSS Logo" />
        </div>
      </div>
    </section>

    <!-- footer -->
    <footer class="footer">
      <p>&copy; 2024 LeoScholar. All rights reserved.</p>
    </footer>

    <!-- JavaScript for toggling nav-links on mobile -->
    <script>
      document
        .querySelector(".menu-toggle")
        .addEventListener("click", function () {
          document.querySelector(".navbar").classList.toggle("active");
        });
    </script>

    <!-- JavaScript for toggling "See all/Collapse" button -->
    <script>
      let isExpanded = false;
      const toggleButton = document.getElementById('toggle-button');

      // Initial load: Collapse and show only the first 6 books
      const booksGrid = document.querySelector('.books-grid');
      const allBooks = Array.from(booksGrid.children);
      allBooks.forEach((book, index) => {
          book.style.display = index < 6 ? 'block' : 'none';
      });

      toggleButton.addEventListener('click', function() {
          if (isExpanded) {
              // Collapse: Show only the first 6 books
              allBooks.forEach((book, index) => {
                  book.style.display = index < 6 ? 'block' : 'none';
              });
              toggleButton.textContent = 'See all';
          } else {
              // Expand: Show all books
              allBooks.forEach(book => {
                  book.style.display = 'block';
              });
              toggleButton.textContent = 'Collapse';
          }
          isExpanded = !isExpanded; // Toggle the state
      });
    </script>

    <!-- Redirect to page from button -->
    <script>
      function redirectToPage(url) {
        // Redirect to search-page.php with both formats as a GET parameter
        window.location.href = url;
}

    </script>


  </body>
</html>
