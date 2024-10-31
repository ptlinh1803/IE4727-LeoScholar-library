<?php
include 'db-connect.php';
session_start();

// If not log in
if (!isset($_SESSION['user_id'])) {
  $message = "Hello, Guest! Please log in to access exclusive features and personalized content!";
} else {
  $user_id = $_SESSION['user_id'];
  // Get favourite books----------------------------
  $get_favourite_books_query = "
      SELECT * FROM books 
      WHERE book_id IN (
          SELECT book_id FROM favourite_books WHERE user_id = ?
      )";

  $stmt = $conn->prepare($get_favourite_books_query);
  $stmt->bind_param("i", $user_id); // Bind user_id as an integer
  $stmt->execute();
  $favourite_books_result = $stmt->get_result();

  $favourite_books = [];
  if ($favourite_books_result->num_rows > 0) {
    while ($book_row = $favourite_books_result->fetch_assoc()) {
        $favourite_books[] = $book_row;
    }
  } else {
    $no_favourite_message = "Your favourites list is currently empty.";
  }

  $stmt->close();

  // Get borrowed books ----------------------------
  $get_borrowed_books_query = "
      SELECT 
          b.book_id,
          l.branch_id,
          b.title,
          b.cover_path,
          br.university_id,
          br.branch_name,
          l.loan_date,
          l.due_date,
          l.return_date,
          l.status
      FROM 
          loans l
      JOIN 
          books b ON l.book_id = b.book_id
      JOIN 
          branches br ON l.branch_id = br.branch_id
      WHERE 
          l.user_id = ?;
    ";
  
  $stmt = $conn->prepare($get_borrowed_books_query);
  $stmt->bind_param("i", $user_id); // Bind user_id as an integer
  $stmt->execute();
  $borrowed_books_result = $stmt->get_result();

  $borrowed_books = [];
  if ($borrowed_books_result->num_rows > 0) {
    while ($book_row = $borrowed_books_result->fetch_assoc()) {
        $borrowed_books[] = $book_row;
    }
  } else {
    $no_loan_message = "You haven't borrowed any books.";
  }

  $stmt->close();
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
    <link rel="stylesheet" href="search-styles.css" />
    <link rel="stylesheet" href="my-shelf-styles.css" />
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar">
      <a href="homepage-member.php">
        <img src="img/ui/leoscholar-logo-transparent.png" alt="Logo" />
      </a>

      <!-- Hamburger Menu Icon -->
      <span class="menu-toggle">&#9776;</span>

      <div class="nav-links">
        <a href="homepage-member.php">Home</a>
        <a href="search-page.php">Search</a>
        <a href="my-shelf.php"  class="active-page">My Shelf</a>
        <a href="#">Contribute</a>
        <div class="dropdown">
          <a href="#" class="profile-link active-page">
            Profile
            <img src="img/ui/drop-down-icon.svg" alt="Arrow Down Icon" />
          </a>
          <div class="dropdown-content">
            <?php if (isset($_SESSION['user_id'])) { ?>
              <a href="#">Settings</a>
              <a href="#">Payment</a>
              <a href="#">Logout</a>
            <?php } else { ?>
              <a href="login.php">Log in</a>
              <a href="register.php">Register</a>
            <?php } ?>
          </div>
        </div>
      </div>
    </nav>

    <!-- Header for Search Page -->
    <header class="header-shelf">
      <h1 class="big-blue-h1">My Shelf</h1>
    </header>

    <!-- Container -->
    <div class="container">
      <div class="left-side">
        <div class="shelf-subnav">
          <a href="#" id="favourite-tab" class="tab-link active-tab"
            >Favourite</a
          >
          <a href="#" id="borrowed-tab" class="tab-link">Borrowed</a>
          <a href="#" id="reserved-tab" class="tab-link">Reserved</a>
        </div>
      </div>
      <div class="right-side">
        <div id="favourite-books" class="shelf-section">
          <h2>Your Favourite Books</h2>
          <?php if (!empty($favourite_books)) { ?>
            <div class="books-grid">
                <?php 
                  foreach ($favourite_books as $book) { 
                  ?>
                    <a href="book-details.php?book_id=<?php echo $book['book_id']; ?>" class="book-wrapper"> 
                        <img src="img/books/<?php echo $book['cover_path']; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" />
                        <p class="title"><?php echo htmlspecialchars($book['title']); ?></p>
                        <p class="author"><?php echo htmlspecialchars($book['author']); ?></p>
                    </a>
                <?php } ?>
            </div>
          <?php } else { ?> 
              <div class="no-books-message">
                  <img src="img/ui/nothing-here.png" alt="Nothing here" />
              </div>
              <p style="text-align: center;">
                  <?php 
                  if (!isset($_SESSION['user_id'])) {
                      echo htmlspecialchars($message);
                  } else {
                      echo htmlspecialchars($no_favourite_message);
                  }
                  ?>
              </p>
          <?php } ?>
        </div>

        <div id="borrowed-books" class="shelf-section" style="display: none">
          <h2>Your Borrowed Books</h2>
          <?php if (!empty($borrowed_books)) { ?>
            <table class="book-table">
              <thead>
                <tr>
                  <th id="cover-col"></th>
                  <th id="title-col">Title</th>
                  <th id="branch-col">Branch</th>
                  <th id="loan-col">Loan Date</th>
                  <th id="due-col">Due Date</th>
                  <th id="status-col">Status</th>
                  <th id="return-col">Return Date</th>
                  <th id="action-col"></th>
                </tr>
              </thead>
              <tbody>
                <!-- Example Row -->
                <?php foreach ($borrowed_books as $book) { ?>
                  <tr onclick="redirectToBookDetails(<?php echo $book['book_id']; ?>)">
                    <td headers="cover-col">
                      <img
                        src="img/books/<?php echo $book['cover_path']; ?>"
                        alt="Book Cover"
                        class="book-cover"
                      />
                    </td>
                    <td headers="title-col">
                      <?php echo htmlspecialchars($book['title']); ?>
                    </td>
                    <td headers="branch-col">
                      <?php echo htmlspecialchars($book['university_id']); ?> - <?php echo htmlspecialchars($book['branch_name']); ?>
                    </td>
                    <td headers="loan-col">
                      <?php echo !empty($book['loan_date']) ? htmlspecialchars($book['loan_date']) : ''; ?>
                    </td>
                    <td headers="due-col">
                      <?php echo !empty($book['due_date']) ? htmlspecialchars($book['due_date']) : ''; ?>
                    </td>
                    <td headers="status-col">
                      <?php
                        $status = $book['status'];
                        $class = '';

                        // Determine the class based on the status
                        if ($status === 'active') {
                            $class = 'status-green';
                        } elseif ($status === 'returned') {
                            $class = 'status-yellow';
                        } elseif ($status === 'overdue') {
                            $class = 'status-red';
                        }
                      ?>
                      <span class="<?php echo $class; ?>"><?php echo ucfirst(htmlspecialchars($status)); ?></span>
                    </td>
                    <td headers="return-col">
                      <?php echo !empty($book['return_date']) ? htmlspecialchars($book['return_date']) : ''; ?>
                    </td>
                    <td>
                      <form
                        action=""
                        method="POST"
                        onsubmit="return confirm('Are you sure you want to return this book?');"
                      >
                        <input type="hidden" name="book_id" value="" />
                        <input type="hidden" name="user_id" value="" />
                        <!-- Replace with actual user ID -->
                        <button
                          type="submit"
                          class="shelf-action-button return"
                          onclick="event.stopPropagation();"
                        >
                          Return
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          <?php } else { ?> 
            <div class="no-books-message">
                <img src="img/ui/nothing-here.png" alt="Nothing here" />
            </div>
            <p style="text-align: center;">
                <?php 
                if (!isset($_SESSION['user_id'])) {
                    echo htmlspecialchars($message);
                } else {
                    echo htmlspecialchars($no_loan_message);
                }
                ?>
            </p>
          <?php } ?>
        </div>

        <div id="reserved-books" class="shelf-section" style="display: none">
          <h2>Your Reserved Books</h2>
          <table class="book-table">
            <thead>
              <tr>
                <th id="cover-col"></th>
                <th id="title-col">Title</th>
                <th id="branch-col">Branch</th>
                <th id="reserved-on-col">Reserved on</th>
                <th id="avail-col">Availability</th>
                <th id="status-col">Status</th>
                <th id="action-col"></th>
              </tr>
            </thead>
            <tbody>
              <!-- Example Row -->
              <tr onclick="redirectToBookDetails(1)">
                <td headers="cover-col">
                  <img
                    src="img/books/4.jpg"
                    alt="Book Cover"
                    class="book-cover"
                  />
                </td>
                <td headers="title-col">
                  Introduction to Mathematical Statistics
                </td>
                <td headers="branch-col">NTU - Lee Wee Nam Library</td>
                <td headers="reserved-on-col">31/10/2024</td>
                <td headers="avail-col">Not available</td>
                <td headers="status-col">
                  <span class="status-yellow">Pending</span>
                </td>
                <td>
                  <form
                    action=""
                    method="POST"
                    onsubmit="return confirm('Please note that even after you acknowledge, you still need to loan the book separately. Would you like to proceed?');"
                    class="action-form"
                  >
                    <input type="hidden" name="book_id" value="" />
                    <input type="hidden" name="user_id" value="" />
                    <!-- Replace with actual user ID -->
                    <button
                      type="submit"
                      class="shelf-action-button acknowledge"
                      onclick="event.stopPropagation();"
                    >
                      Loan now
                    </button>
                  </form>
                  <form
                    action=""
                    method="POST"
                    onsubmit="return confirm('Are you sure you want cancel this reservation?');"
                    class="action-form"
                  >
                    <input type="hidden" name="book_id" value="" />
                    <input type="hidden" name="user_id" value="" />
                    <!-- Replace with actual user ID -->
                    <button
                      type="submit"
                      class="shelf-action-button cancel"
                      onclick="event.stopPropagation();"
                    >
                      Cancel
                    </button>
                  </form>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- footer -->
    <footer class="footer">
      <p>&copy; 2024 LeoScholar. All rights reserved.</p>
    </footer>

    <!-- Toggling nav-links on mobile -->
    <script>
      document
        .querySelector(".menu-toggle")
        .addEventListener("click", function () {
          document.querySelector(".navbar").classList.toggle("active");
        });
    </script>

    <!-- Sub nav -->
    <script>
      // Function to handle tab click
      function openTab(selectedTab, sectionId) {
        // Get all book sections
        const sections = document.querySelectorAll(".shelf-section");

        // Hide all sections
        sections.forEach((section) => {
          section.style.display = "none";
        });

        // Show the selected section
        document.getElementById(sectionId).style.display = "block"; // Change to "block" for div

        // Remove 'active-tab' class from all tab links
        document.querySelectorAll(".tab-link").forEach((tab) => {
          tab.classList.remove("active-tab");
        });

        // Add 'active-tab' class to the selected tab link
        selectedTab.classList.add("active-tab");
      }

      // Default to showing the "favourite-books" section
      document
        .getElementById("favourite-tab")
        .addEventListener("click", function (event) {
          event.preventDefault();
          openTab(this, "favourite-books");
        });

      // Show "borrowed-books" section on click
      document
        .getElementById("borrowed-tab")
        .addEventListener("click", function (event) {
          event.preventDefault();
          openTab(this, "borrowed-books");
        });

      // Show "reserved-books" section on click
      document
        .getElementById("reserved-tab")
        .addEventListener("click", function (event) {
          event.preventDefault();
          openTab(this, "reserved-books");
        });

      // Show the "favourite-books" section by default on page load
      document.addEventListener("DOMContentLoaded", function () {
        openTab(document.getElementById("favourite-tab"), "favourite-books");
      });
    </script>

    <!-- Redirect to Book details page -->
    <script>
      function redirectToBookDetails(bookId) {
        window.location.href = "book-details.php?book_id=" + bookId;
      }
    </script>
  </body>
</html>
