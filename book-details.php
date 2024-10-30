<?php
include 'db-connect.php';
session_start();

// Get all details from "books" table
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

  // find similar books (same category)
  if (!empty($book)) {
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
        <a href="#">My Shelf</a>
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

    <!-- Main Container -->
    <div class="container">
      <!-- Left Side with Background -->
      <div class="left-side">
        <!-- Back Button -->
        <a href="#" class="back-button" onclick="history.back(); return false;">&lt; Back</a>

        <!-- Book Cover positioned on the border of left and right sides -->
        <div class="book-cover">
          <img src="img/books/<?php echo $book['cover_path']; ?>" alt="Book Cover" />
        </div>
      </div>

      <!-- Right Side with Content -->
      <div class="right-side">
        <h1 class="big-blue-h1 book-title">
          <?php echo htmlspecialchars($book['title']); ?>
          <i class="heart-icon far fa-heart"></i>
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
                onclick="toggleForm()"
              >
                Loan/Reserve
              </button>
            </div>
            <div class="info-column">
              <h4>Available at</h4>
              <div><img src="img/ui/map.png" alt="Uni Icon" /> NTU</div>
              <div><img src="img/ui/map.png" alt="Uni Icon" /> NUS</div>
              <!-- <div><img src="img/ui/map.png" alt="Uni Icon" /> And more</div> -->
              <div>
                <img src="img/ui/map.png" alt="Uni Icon" />
                <a
                  id="tableToggleButton"
                  onclick="toggleTable(); return false;"
                >
                  More details
                </a>
              </div>
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
                <div class="table-header">Author:</div>
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
              <tr>
                <td>NTU</td>
                <td>Communication & Information Library</td>
                <td>NS3-03-01</td>
                <td>3</td>
                <td>OP F3-3</td>
              </tr>
              <tr>
                <td>NUS</td>
                <td>Central Library</td>
                <td>12 Kent Ridge Crescent</td>
                <td>1</td>
                <td>YZ F3-1</td>
              </tr>
              <tr>
                <td>SMU</td>
                <td>Duda Family Business Library</td>
                <td>6214 Bishop Boulevard</td>
                <td>2</td>
                <td>ST F2-4</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Form for Borrowing -->
        <form
          id="loanForm"
          class="form-container"
          onsubmit="submitForm(); return false;"
        >
          <h2>Fill in Details</h2>

          <div>
            <label for="from-date">From:</label>
            <input
              type="date"
              id="from-date"
              onchange="validateDates()"
              required
            />
          </div>

          <div>
            <label for="to-date">To:</label>
            <input
              type="date"
              id="to-date"
              onchange="validateDates()"
              required
            />
          </div>

          <div>
            <label for="branch">Choose a branch to borrow from:</label>
            <select id="branch" onchange="updateAvailability()" required>
              <option value="">Select branch</option>
              <option value="ntu">NTU</option>
              <option value="nus">NUS</option>
              <option value="smu">SMU</option>
            </select>
          </div>

          <div>
            <label for="available-copies">Available copies:</label>
            <span
              id="available-copies"
              style="
                display: block;
                padding: 7px;
                border: 1px solid #ccc;
                border-radius: 5px;
                background-color: #f9f9f9;
              "
            >
              N/A
            </span>
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

      // Toggle active class on click
      heartIcon.addEventListener("click", () => {
        heartIcon.classList.toggle("active"); // Add/remove red color
        heartIcon.classList.toggle("fas"); // Solid heart
        heartIcon.classList.toggle("far"); // Outline heart
      });
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

    <!-- Script for Loan form -->
    <script>
      // Show/hide form
      function toggleForm() {
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

          if (new Date(toDate.value) < minFromDate) {
            toDate.setCustomValidity(
              "To date must be at least 1 day from today."
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
          ntu: 2,
          nus: 3,
          smu: 0,
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
      function submitForm() {
        const actionButton = document.getElementById("actionButton");
        alert(`${actionButton.innerText} request submitted.`);

        // Refresh the current page
        location.reload(); // This will refresh the page, closing the form
      }
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
