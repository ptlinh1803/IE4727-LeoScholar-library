<?php
include 'db-connect.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// if librarian --> cannot access, redirect to edit book details
if (isset($_SESSION['librarian_id'])) {
  header('Location: homepage-librarian.php'); //change to edit book details page
  exit();
}

// Check if there's an alert message
if (isset($_SESSION['alert'])) {
  $alertMessage = $_SESSION['alert'];
  echo "<script>
      window.addEventListener('load', function() {
          alert('" . addslashes($alertMessage) . "');
      });
  </script>";
  unset($_SESSION['alert']); // Clear the message after displaying
}

// Get all branches -------------------------
$sql_list_branches = "SELECT * from branches;";
$branches_results = $conn->query($sql_list_branches);

if ($branches_results) {
  $branches_list = [];
  while ($row = $branches_results->fetch_assoc()) {
      $branches_list[] = $row;
  }
} else {
  // Handle query error
  echo "Error: " . $conn->error;
}

// Get all details from "books" table ------------------
if (!empty($_GET['book_id'])) {
  $book_id = $_GET['book_id'];

  $get_book_info_query = "
      SELECT * FROM books 
      WHERE book_id = ?";

  $stmt = $conn->prepare($get_book_info_query);
  $stmt->bind_param("i", $book_id);
  $stmt->execute();
  $book_result = $stmt->get_result();

  $book = []; // Initialize the array for suggested books
  if ($book_result->num_rows === 1) {
    $book = $book_result->fetch_assoc(); // Directly fetch the single book row
  } else {
      $message = "No book found";
  }

  $stmt->close(); // Close the statement

  if (!empty($book)) {
    // find similar books (same category) -------------------
    $get_similar_book = "
        SELECT * FROM books
        WHERE category = ? AND book_id != ?
        LIMIT 5
    ";

    // Prepare the statement
    $stmt = $conn->prepare($get_similar_book);
    
    // Bind the parameters
    $stmt->bind_param("si", $book['category'], $book['book_id']);
    
    // Execute the statement
    $stmt->execute();
    
    // Store the result
    $result = $stmt->get_result();
    
    // Initialize the array for similar books
    $similar_books = [];
    
    // Fetch the results into the array
    while ($row = $result->fetch_assoc()) {
        $similar_books[] = $row;
    }

    // Close the statement
    $stmt->close();

    // find book availability -------------------
    $get_availability = " 
      SELECT 
          branches.university_id,
          branches.branch_id,
          branches.branch_name,
          branches.address,
          book_availability.available_copies,
          book_availability.shelf
      FROM 
          book_availability
      JOIN 
          branches ON book_availability.branch_id = branches.branch_id
      WHERE 
          book_availability.book_id = ?
          AND book_availability.available_copies > 0;
    ";

    $stmt = $conn->prepare($get_availability);
    $stmt->bind_param("i", $book['book_id']);
    $stmt->execute();
    $avail_result = $stmt->get_result();

    // Initialize the array for availability
    $availability = [];

    // Fetch the results into the array
    while ($row = $avail_result->fetch_assoc()) {
      $availability[] = $row;
    }

    // Prepare the availability mapping
    $availability_map = [];
    foreach ($availability as $item) {
        $availability_map[$item['branch_id']] = $item['available_copies'];
    }

    // Close the statement
    $stmt->close();

    // check favourite-----------------------
    $isFavourite = false; 
    if (isset($_SESSION['user_id'])) {
      $userId = $_SESSION['user_id'];
      $bookId = $book['book_id'];

      // Check if this book is already in the favourites
      $query = "SELECT * FROM favourite_books WHERE user_id = ? AND book_id = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("ii", $userId, $bookId);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
          $isFavourite = true; // Book is in favorites
      }

      $stmt->close();
    }

    // Add book to favourite --------------------------------------
    if (isset($_POST['toggle_favourite'])) {
      if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Only registered members can use this feature.');</script>";
      } else {
        $userId = $_SESSION['user_id'];
        $bookId = $_GET['book_id'];

        // Check if this book is already in the favourites
        $query = "SELECT * FROM favourite_books WHERE user_id = ? AND book_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $userId, $bookId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
          // Remove from favourites
          $deleteQuery = "DELETE FROM favourite_books WHERE user_id = ? AND book_id = ?";
          $stmt = $conn->prepare($deleteQuery);
          $stmt->bind_param("ii", $userId, $bookId);
          $stmt->execute();
          $isFavourite = false;
          $stmt->close();

          $_SESSION['alert'] = "Book removed from Favourite.";
          
        } else {
          // Add to favourites
          $insertQuery = "INSERT INTO favourite_books (user_id, book_id) VALUES (?, ?)";
          $stmt = $conn->prepare($insertQuery);
          $stmt->bind_param("ii", $userId, $bookId);
          $stmt->execute();
          $isFavourite = true;
          $stmt->close();

          $_SESSION['alert'] = "Book added to Favourite.";
        }
    
        

        // Redirect to the same page to avoid form resubmission
        header("Location: book-details.php?book_id=" . $bookId);
        exit();
      }
    }

    // Submit Loan/Reserve form --------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['branch_id'])) {
      $user_id = $_SESSION['user_id'];
      $book_id = $book['book_id'];
      $branch_id = $_POST['branch_id']; // Get branch_id from the form
      
      // Check availability
      $stmt = $conn->prepare("SELECT available_copies FROM book_availability WHERE book_id = ? AND branch_id = ?");
      $stmt->bind_param("ii", $book_id, $branch_id);
      $stmt->execute();
      $stmt->bind_result($available_copies);
      $stmt->fetch();
      $stmt->close();
      

      if ($available_copies > 0) {
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date']; 

        // Check if the user already has a loan for this book at this branch
        $check_query = "
          SELECT * FROM loans 
          WHERE user_id = ? 
          AND book_id = ? 
          AND branch_id = ?
          AND status = 'active'";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("iii", $user_id, $book_id, $branch_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
          // User has already reserved this book at this branch
          $_SESSION['alert'] = "You have already borrowed this book from this branch.";
        } else {
          // Check user's existing loans count
          $stmt = $conn->prepare("SELECT COUNT(*) AS loan_count FROM loans WHERE user_id = ? AND status='active'");
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $stmt->bind_result($loan_count);
          $stmt->fetch();
          $stmt->close();

          if ($loan_count >= 10) {
            // User has reached the loan limit
            $_SESSION['alert'] = "You have reached the loan limit of 10 books.";
          } else {
            // Insert into loans table
            $stmt = $conn->prepare("INSERT INTO loans (user_id, book_id, branch_id, loan_date, due_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiss", $user_id, $book_id, $branch_id, $from_date, $to_date);
            $stmt->execute();

            // Update available copies
            $stmt = $conn->prepare("UPDATE book_availability SET available_copies = available_copies - 1 WHERE book_id = ? AND branch_id = ?");
            $stmt->bind_param("ii", $book_id, $branch_id);
            $stmt->execute();

            $stmt->close();

            $_SESSION['alert'] = "Loan successful.";  
          }
        }
      } else {
        // Check if the user already has a reservation for this book at this branch
        $check_query = "
          SELECT * FROM reservations
          WHERE user_id = ? 
          AND book_id = ? 
          AND branch_id = ?
          AND status = 'pending'";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("iii", $user_id, $book_id, $branch_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            // User has already reserved this book at this branch
            $_SESSION['alert'] = "You have already reserved this book at this branch.";
        } else {
            // check if the user is borrowing this book from this branch
            $check_query_2 = "
              SELECT * FROM loans 
              WHERE user_id = ? 
              AND book_id = ? 
              AND branch_id = ?
              AND status = 'active'";
            $stmt = $conn->prepare($check_query_2);
            $stmt->bind_param("iii", $user_id, $book_id, $branch_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
              // User has already reserved this book at this branch
              $_SESSION['alert'] = "You have already borrowed this book from this branch.";
            } else {
              // No existing loan or reservation, proceed to insert into reservations table
                $reservation_date = date("Y-m-d"); // Get today's date
                $stmt = $conn->prepare("INSERT INTO reservations (user_id, book_id, branch_id, reservation_date) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiis", $user_id, $book_id, $branch_id, $reservation_date);
                $stmt->execute();
                $stmt->close();
        
                $_SESSION['alert'] = "Reservation successful.";

            }
        }
      }

      // Redirect to the same page to avoid resubmission
      header("Location: book-details.php?book_id=" . $book_id);
      exit(); // Ensure no further code is executed after the redirect
      }
  }
}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book Detail Page</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="book-styles.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
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
        <a href="my-shelf.php">My Shelf</a>
        <a href="user-contribution.php">Contribute</a>
        <div class="dropdown">
          <a href="#" class="profile-link active-page">
            Profile
            <img src="img/ui/drop-down-icon.svg" alt="Arrow Down Icon" />
          </a>
          <div class="dropdown-content">
            <?php if (isset($_SESSION['user_id'])) { ?>
              <a href="#">Settings</a>
              <a href="payment.php">Payment</a>
              <a href="#">Logout</a>
            <?php } else { ?>
              <a href="login.php">Log in</a>
              <a href="register.php">Register</a>
            <?php } ?>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
      <!-- Left Side with Background -->
      <div class="left-side">
        <!-- Back Button -->
        <a href="#" class="back-button" onclick="history.back(); return false;">&lt; Back</a>

        <!-- Book Cover positioned on the border of left and right sides -->
        <?php if ((!empty($_GET['book_id'])) && (!empty($book))) { ?>
          <div class="book-cover">
            <img src="img/books/<?php echo $book['cover_path']; ?>" alt="Book Cover" />
          </div>
        <?php } ?>
      </div>

      <!-- Right Side with Content -->
      <div class="right-side">
        <?php if ((!empty($_GET['book_id'])) && (!empty($book))) { ?>
        <h1 class="big-blue-h1 book-title">
          <?php echo htmlspecialchars($book['title']); ?>
          <!-- <i class="heart-icon far fa-heart"></i> -->
          <form method="POST" action="book-details.php?book_id=<?php echo $_GET['book_id']; ?>" style="display: inline;">
            <input type="hidden" name="toggle_favourite" value="1">
            <button type="submit" name="toggle_favourite" style="background: none; border: none; cursor: pointer;">
              <i class="heart-icon <?php echo isset($isFavourite) && $isFavourite ? 'fas fa-heart red' : 'far fa-heart'; ?>"></i>
            </button>
            <input type="hidden" name="book_id" value="<?php echo $_GET['book_id']; ?>">
          </form>
        </h1>

        <div class="book-info-container">
          <!-- Part 1 -->
          <div class="part-one">
            <div class="info-column">
              <h4>Available Format</h4>
              <div>
                <img src="<?php echo $book['hard_copy'] == 0 ? 'img/ui/cross.png' : 'img/ui/check.png'; ?>" /> Hard Copy
              </div>
              <div>
                <img src="<?php echo !empty($book['ebook_file_path']) ? 'img/ui/check.png' : 'img/ui/cross.png'; ?>" /> E-book
              </div>
              <div>
                <img src="<?php echo !empty($book['audio_file_path']) ? 'img/ui/check.png' : 'img/ui/cross.png'; ?>" /> Audio Book
              </div>
              <button
                class="loan-button"
                id="loanReserveButton"
                onclick="handleLoanReserve()"
              >
                Loan/Reserve
              </button>
            </div>
            <div class="info-column">
              <h4>Available at</h4>
              <?php if (empty($availability)) { ?>
                <div><img src="img/ui/red-map.png" alt="Uni Icon" /><span style="color:red;">Not Available</span></div>
                <div style="min-height: 18.5px;"></div>
                <div style="min-height: 18.5px;"></div>
              <?php } else {
                $displayed_universities = []; // Initialize an array to track displayed university IDs
                $display_count = 0; // Counter to track the number of displayed IDs
                
                foreach ($availability as $entry) {
                    // Check if the university ID has already been displayed
                    if (!in_array($entry['university_id'], $displayed_universities)) {
                        echo '<div><img src="img/ui/map.png" alt="Uni Icon" /> ' . htmlspecialchars($entry['university_id']) . '</div>';
                        
                        // Add the displayed university ID to the tracking array
                        $displayed_universities[] = $entry['university_id'];
                        $display_count++; // Increment the display counter
                
                        // Stop once two unique IDs have been displayed
                        if ($display_count >= 2) {
                            break;
                        }
                    }
                }

                if (count($displayed_universities) < 2) {
                  echo  '<div style="min-height: 18.5px;"></div>';
                }
                
                // show details
                echo '<div><img src="img/ui/map.png" alt="Uni Icon" /><a id="tableToggleButton" onclick="toggleTable(); return false;">More details</a></div>';
               } ?>
              
              <div class="button-group">
                <button
                  class="read-button"
                  <?php if (empty($book['ebook_file_path'])): ?>
                      disabled
                      style="background-color: gray; cursor: not-allowed;"
                  <?php else: ?>
                      onclick="openPDF('database/<?php echo $book['ebook_file_path']; ?>')"
                  <?php endif; ?>
                >
                  Read
                </button>
                <button
                  class="listen-button"
                  <?php if (empty($book['audio_file_path'])): ?>
                      disabled
                      style="background-color: gray; cursor: not-allowed;"
                  <?php else: ?>
                      onclick="openPDF('database/<?php echo $book['audio_file_path']; ?>')"
                  <?php endif; ?>
                >
                  Listen
                </button>
              </div>
            </div>
          </div>

          <!-- Part 2 -->
          <div class="part-two">
            <h3 class="part-two-heading">Book Details</h3>
            <div class="book-details-table">
              <div class="table-row">
                <div class="table-header">Author(s):</div>
                <div class="table-cell">
                  <?php echo htmlspecialchars($book['author']); ?>
                </div>
              </div>
              <div class="table-row">
                <div class="table-header">Publication Year:</div>
                <div class="table-cell"><?php echo htmlspecialchars($book['publication_year']); ?></div>
              </div>
              <div class="table-row">
                <div class="table-header">Category:</div>
                <div class="table-cell"><?php echo htmlspecialchars($book['category']); ?></div>
              </div>
              <div class="table-row">
                <div class="table-header">ISBN-13:</div>
                <div class="table-cell"><?php echo htmlspecialchars($book['isbn']); ?></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Table to show availability -->
        <?php if (!empty($availability)) { ?>
          <div class="availability-table">
            <table>
              <thead>
                <tr>
                  <th>University</th>
                  <th>Branch</th>
                  <th>Address</th>
                  <th>Available Copies</th>
                  <th>Shelf</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($availability as $row) { ?>
                  <tr>
                    <td><?php echo htmlspecialchars($row['university_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['branch_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo htmlspecialchars($row['available_copies']); ?></td>
                    <td><?php echo htmlspecialchars($row['shelf']); ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        <?php } ?>

        <!-- Form for Borrowing -->
        <form
          id="loanForm"
          class="form-container"
          onsubmit="return submitForm();"
          method="POST"
          action="book-details.php?book_id=<?php echo $book_id; ?>"
        >
          <h2>Fill in Details</h2>

          <div>
            <label>From:</label>
            <input
              type="date"
              id="from-date"
              name="from_date"
              onchange="validateDates()"
              required
            />
          </div>

          <div>
            <label>To:</label>
            <input
              type="date"
              id="to-date"
              name="to_date"
              onchange="validateDates()"
              required
            />
          </div>

          <div>
            <label>Choose a branch to borrow from:</label>
            <select id="branch" name="branch_id" onchange="updateAvailability()" required>
              <option value="">Select branch</option>
              <?php foreach ($branches_list as $branch) { ?>
                <option value="<?php echo $branch['branch_id'] ?>">
                  <?php echo $branch['university_id'] ?> - <?php echo $branch['branch_name'] ?>
                </option>
              <?php } ?>
            </select>
          </div>

          <div>
            <label>Available copies:</label>
            <span id="available-copies" >N/A</span>
          </div>

          <!-- Conditional Message & Action Button -->
          <p id="availability-message" style="color: red"></p>
          <button type="submit" id="actionButton">Loan</button>
          <button type="reset" id="resetButton" onClick="resetForm()">
            Reset
          </button>
        </form>

        <!-- Book description -->
        <div class="description">
          <h2>Description</h2>
          <p id="description-text">
            <?php echo htmlspecialchars($book['description']); ?>
          </p>
          <span id="see-more-description" class="show-more">See More</span>
        </div>

        <!--About the authors-->
        <div class="about-author">
          <h2>About the Author(s)</h2>
          <p id="author-description">
          <?php echo htmlspecialchars($book['about_author']); ?>
          </p>
          <span id="see-more-author" class="show-more">See More</span>
        </div>

        <!-- Discover similar books -->
        <?php if (!empty($similar_books)) { ?>
          <div class="similar-books">
            <h2>Discover similar books</h2>
            <div class="books-grid">
              <?php ;
                foreach ($similar_books as $book) { 
                ?>
                  <a href="book-details.php?book_id=<?php echo $book['book_id']; ?>" class="book-wrapper"> 
                      <img src="img/books/<?php echo $book['cover_path']; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" />
                      <p class="title"><?php echo htmlspecialchars($book['title']); ?></p>
                      <p class="author"><?php echo htmlspecialchars($book['author']); ?></p>
                  </a>
              <?php } ?>
            </div>
          </div>
        <?php } ?>

      <?php } else { ?>
        <div class="no-book-detail">
          <img src="img/ui/nothing-here.png" alt="Nothing here" />
        </div>
      <?php } ?>
      </div>
    </div>

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

    <!-- Heart Icon -->
    <script>
      // Select the heart icon
      const heartIcon = document.querySelector(".heart-icon");

      // Check if heartIcon is not null before adding the event listener
      if (heartIcon) {
        // Toggle active class on click
        heartIcon.addEventListener("click", () => {
          heartIcon.classList.toggle("active"); // Add/remove red color
          heartIcon.classList.toggle("fas"); // Solid heart
          heartIcon.classList.toggle("far"); // Outline heart
        });
} 
    </script>

    <!-- Script to display "Description" and "About the Author(s)" -->
    <script>
      // Function to handle truncating text
      function truncateText(element, lines, seeMoreElement) {
        const p = document.getElementById(element);
        const originalText = p.innerHTML.replace(/<br>/g, "\n"); // Preserve line breaks
        p.innerHTML = originalText; // Set original text
        const lineHeight = parseFloat(getComputedStyle(p).lineHeight); // Get line height

        // Set max-height based on the number of lines
        p.style.maxHeight = `${lines * lineHeight}px`;

        // Check if content exceeds the max-height
        if (p.scrollHeight > p.clientHeight) {
          seeMoreElement.style.display = "inline"; // Show "See More" link
        }
      }

      // Expand text function
      function expandText(element, seeMoreElement) {
        const p = document.getElementById(element);
        p.style.maxHeight = "none"; // Remove max height to show all text
        seeMoreElement.style.display = "none"; // Hide "See More" link
      }

      // Truncate both text sections (limit to 3 lines)
      truncateText(
        "description-text",
        4,
        document.getElementById("see-more-description")
      );
      truncateText(
        "author-description",
        4,
        document.getElementById("see-more-author")
      );

      // Event listeners for "See More" links
      document
        .getElementById("see-more-description")
        .addEventListener("click", function () {
          expandText("description-text", this);
        });

      document
        .getElementById("see-more-author")
        .addEventListener("click", function () {
          expandText("author-description", this);
        });

      // Replace \n with <br> for the author description text
      const authorDescription = document.getElementById("author-description");
      authorDescription.innerHTML = authorDescription.innerHTML.replace(
        /\n/g,
        "<br>"
      );

      const bookDescription = document.getElementById("description-text");
      bookDescription.innerHTML = bookDescription.innerHTML.replace(
        /\n/g,
        "<br>"
      );
    </script>

    <!-- Open PDF/Audio -->
    <script>
      function openPDF(path) {
        window.open(path, "_blank");
      }
    </script>

    <!-- Script for Loan/Reserve form -->
    <script>
      // When clicking "Loan/Reserve" button
      function handleLoanReserve() {
        <?php if (isset($_SESSION['user_id'])) { ?>
          // Toggle the form display if the user is logged in
          toggleLoanReserveForm();
        <?php } else { ?>
          // Show alert if the user is not logged in
          alert("Only registered members can use this feature.");
        <?php } ?>
      }

      // Show/hide form
      function toggleLoanReserveForm() {
        const loanForm = document.getElementById("loanForm");
        const loanReserveButton = document.getElementById("loanReserveButton");

        // Toggle form visibility
        if (
          loanForm.style.display === "none" ||
          loanForm.style.display === ""
        ) {
          loanForm.style.display = "block";
          loanReserveButton.innerText = "Close form"; // Change button text to "Close form"
        } else {
          loanForm.style.display = "none";
          loanReserveButton.innerText = "Loan/Reserve"; // Change button text back to "Loan/Reserve"
        }
      }

      // Validation for Date Fields
      function validateDates() {
        const fromDate = document.getElementById("from-date");
        const toDate = document.getElementById("to-date");
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Set time to midnight for accurate comparison

        const minFromDate = new Date(today);
        minFromDate.setDate(minFromDate.getDate() + 1); // From date must be at least 1 day from now

        // Check "From" date
        if (new Date(fromDate.value) < minFromDate) {
          fromDate.setCustomValidity(
            "From date must be at least 1 day from today."
          );
          fromDate.reportValidity();
        } else {
          fromDate.setCustomValidity("");
        }

        // Check "To" date
        if (fromDate.value) {
          // Ensure "From" date is valid before checking "To"
          const fromDateValue = new Date(fromDate.value);
          const maxEndDate = new Date(fromDateValue);
          maxEndDate.setDate(maxEndDate.getDate() + 14); // To date cannot exceed 14 days from "From" date
          const minEndDate = new Date(fromDateValue);
          minEndDate.setDate(minEndDate.getDate() + 1);

          if (new Date(toDate.value) < minEndDate) {
            toDate.setCustomValidity(
              "To date must be at least 1 day from the selected From date."
            );
            toDate.reportValidity();
          } else if (new Date(toDate.value) > maxEndDate) {
            toDate.setCustomValidity(
              "To date cannot exceed 14 days from the selected From date."
            );
            toDate.reportValidity();
          } else {
            toDate.setCustomValidity("");
          }
        }
      }

      // Update Availability Based on Branch
      function updateAvailability() {
        const branch = document.getElementById("branch").value;
        const availableCopiesField =
          document.getElementById("available-copies");
        const availabilityMessage = document.getElementById(
          "availability-message"
        );
        const actionButton = document.getElementById("actionButton");
        const fromDate = document.getElementById("from-date");
        const toDate = document.getElementById("to-date");

        // Sample data for available copies
        const availability = {
          <?php foreach ($availability_map as $branch_id => $copies): ?>
            "<?php echo $branch_id; ?>": <?php echo $copies; ?>,
          <?php endforeach; ?>
        };

        // Display available copies based on selected branch
        const availableCopies = availability[branch] || 0;
        availableCopiesField.innerText = availableCopies;

        // Show "Loan" button or "Reserve" option based on availability
        if (availableCopies > 0) {
          availabilityMessage.innerText = "";
          actionButton.innerText = "Loan";
          actionButton.classList.remove("reserve");
          fromDate.disabled = false;
          toDate.disabled = false;
        } else {
          availabilityMessage.innerText =
            "This book is not available at your chosen branch. Please choose another branch or proceed to reserve it.";
          actionButton.innerText = "Reserve";
          actionButton.classList.add("reserve");
          fromDate.disabled = true;
          toDate.disabled = true;
        }
      }

      // Reset form
      function resetForm() {
        const availableCopiesField =
          document.getElementById("available-copies");
        availableCopiesField.innerText = "N/A";

        const availabilityMessage = document.getElementById(
          "availability-message"
        );
        availabilityMessage.innerText = "";
        actionButton.innerText = "Loan";
        actionButton.classList.remove("reserve");
      }

      // Form submission
      // function submitForm() {
      //   const actionButton = document.getElementById("actionButton");
      //   const availableCopiesField = document.getElementById("available-copies");
      //   const availableCopies = parseInt(availableCopiesField.innerText) || 0; // Get available copies as a number

      //   if (availableCopies > 0) {
      //     alert("Loan request submitted."); // Alert for loan request
      //   } else {
      //     alert("Reservation request submitted."); // Alert for reservation request
      //   }

      //   // Allow form submission to proceed and refresh the page
      //   location.reload(); // This will refresh the page
      //   return true;
      // }
    </script>

    <!-- Script to open "Availability" table -->
    <script>
      function toggleTable() {
        const availabilityTable = document.querySelector(".availability-table");
        const tableToggleButton = document.getElementById("tableToggleButton");

        // Toggle table visibility
        if (
          availabilityTable.style.display === "none" ||
          availabilityTable.style.display === ""
        ) {
          availabilityTable.style.display = "block";
          tableToggleButton.innerText = "Close table"; // Change button text to "Close table"
        } else {
          availabilityTable.style.display = "none";
          tableToggleButton.innerText = "More details"; // Change button text back to "Show table"
        }
      }
    </script>
  </body>
</html>
