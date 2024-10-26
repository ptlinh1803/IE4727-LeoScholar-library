<?php
include 'db-connect.php';
session_start();

// for fulltext search input--------------
$fulltext_input = $_GET['searchQuery'] ?? '';

if (!empty($fulltext_input)) {
  $sql_fulltext_query = "
    SELECT *
    FROM books
    WHERE MATCH(title, author, description, category) AGAINST(? IN NATURAL LANGUAGE MODE);
  ";
  $stmt = $conn->prepare($sql_fulltext_query);
  $stmt->bind_param("s", $fulltext_input);
  $stmt->execute();
  $results = $stmt->get_result();

  $found_books = []; // Initialize the array
  if ($results->num_rows > 0) {
    $message = "Discover our personalized book recommendations tailored just for you, based on your preferred categories!";
    while ($book_row = $results->fetch_assoc()) {
        $found_books[] = $book_row; // Store each book in the array
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
    <link rel="stylesheet" href="search-styles.css" />
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
        <a href="search-page.php" class="active-page">Search</a>
        <a href="#">My Shelf</a>
        <a href="#">Contribute</a>
        <div class="dropdown">
          <a href="#" class="profile-link active-page">
            Profile
            <img src="img/ui/drop-down-icon.svg" alt="Arrow Down Icon" />
          </a>
          <div class="dropdown-content">
            <a href="#">Settings</a>
            <a href="#">Payment</a>
            <a href="#">Logout</a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Header for Search Page -->
    <header class="header-search">
      <div>
        <form class="search-bar" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
          <button
            type="button"
            class="advanced-search-button"
            onclick="handleAdvancedSearch()"
          >
            Advanced Search
            <img
              src="img/ui/dropdown-white.png"
              alt="Arrow Down Icon"
              class="dropdown-icon"
            />
          </button>
          <input
            type="text"
            class="rounded-right"
            name="searchQuery"
            placeholder="Quick Search..."
          />
          <button type="submit" class="submit-button">
            <img src="img/ui/small-search-icon.png" alt="Search Icon" />
          </button>
        </form>

        <!-- Advanced Search Form -->
        <?php if (isset($_SESSION['user_id'])) { ?>
          <form
            class="advanced-search-form"
            id="advancedSearchForm"
            method="POST"
          >
            <div>
              <label>Title:</label>
              <input type="text" name="title" />
            </div>
            <div>
              <label>Author:</label>
              <input type="text" name="author" />
            </div>
            <div>
              <label>Category:</label>
              <input type="text" name="category" />
            </div>
            <div class="form-group">
              <div class="isbn-group">
                <label for="isbn">ISBN:</label>
                <input type="text" id="isbn" name="isbn" class="isbn-input" />
              </div>

              <div class="publication-year-group">
                <label>Publication Year:</label>
                <div class="publication-year-group-input">
                  <input
                    type="number"
                    name="yearFrom"
                    placeholder="From"
                    min="1900"
                    max="2100"
                    class="year-input"
                  />
                  <span class="separator">-</span>
                  <input
                    type="number"
                    name="yearTo"
                    placeholder="To"
                    min="1900"
                    max="2100"
                    class="year-input"
                  />
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="checkbox-dropdown">
                <label>Format:</label>
                <div
                  class="checkbox-dropdown-toggle"
                  onclick="toggleDropdown('formatsDropdown')"
                >
                  <span>Select Formats</span>
                  <img
                    src="img/ui/drop-down-icon.svg"
                    alt="Arrow Down Icon"
                    class="dropdown-icon"
                  />
                </div>
                <div class="checkbox-dropdown-content" id="formatsDropdown">
                  <label>
                    <input type="checkbox" name="format" value="Hard Copy" />
                    Hard Copy
                  </label>
                  <label>
                    <input type="checkbox" name="format" value="E-book" />
                    E-book
                  </label>
                  <label>
                    <input type="checkbox" name="format" value="Audio Book" />
                    Audio Book
                  </label>
                </div>
              </div>

              <div class="checkbox-dropdown">
                <label>Available at:</label>
                <div
                  class="checkbox-dropdown-toggle"
                  onclick="toggleDropdown('availabilityDropdown')"
                >
                  <span>Select Universities</span>
                  <img
                    src="img/ui/drop-down-icon.svg"
                    alt="Arrow Down Icon"
                    class="dropdown-icon"
                  />
                </div>
                <div class="checkbox-dropdown-content" id="availabilityDropdown">
                  <label
                    ><input type="checkbox" name="availableAt" value="NTU" />
                    NTU</label
                  >
                  <label
                    ><input type="checkbox" name="availableAt" value="NUS" />
                    NUS</label
                  >
                  <label
                    ><input type="checkbox" name="availableAt" value="SMU" />
                    SMU</label
                  >
                  <label
                    ><input type="checkbox" name="availableAt" value="SUTD" />
                    SUTD</label
                  >
                  <label
                    ><input type="checkbox" name="availableAt" value="SIT" />
                    SIT</label
                  >
                  <label
                    ><input type="checkbox" name="availableAt" value="SUSS" />
                    SUSS</label
                  >
                </div>
              </div>

              <div>
                <label>Sort by:</label>
                <div class="sort-dropdown">
                  <select name="sortBy" id="sortBy" class="sort-dropdown-select">
                    <option value="none">None</option>
                    <option value="titleAsc">Title A-Z</option>
                    <option value="titleDesc">Title Z-A</option>
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                  </select>
                  <img src="img/ui/drop-down-icon.svg" alt="Dropdown Icon" />
                </div>
              </div>
            </div>

            <div class="advanced-search-form-buttons">
              <button type="reset" onclick="closeSearchForm()">Cancel</button>
              <button type="submit">Apply</button>
            </div>
          </form>
        <?php } ?>
      </div>
    </header>

    <!-- Discover category section -->
    <section class="discover-category">
      <h1 class="big-blue-h1">Discover</h1>
      <div class="carousel">
        <button class="arrow" onclick="moveCarousel(-1)">
          <img
            src="img/ui/red-left-arrow.png"
            alt="Left Arrow"
            class="arrow-carousel"
          />
        </button>
        <div class="button-container">
          <!-- Buttons will be dynamically added here -->
        </div>
        <button class="arrow" onclick="moveCarousel(1)">
          <img
            src="img/ui/red-right-arrow.png"
            alt="Right Arrow"
            class="arrow-carousel"
          />
        </button>
      </div>
    </section>

    <!-- Show book results section -->
    <section class="book-section">
      <div class="search-query">
        <p>Here is your search query:</p>
      </div>
      
      <?php if (!empty($found_books)) { ?>
        <table class="book-table">
          <thead>
            <tr>
              <th id="cover-col"></th>
              <th id="title-col">Title</th>
              <th id="author-col">Author</th>
              <th id="category-col">Category</th>
              <th id="format-col">Format</th>
              <th id="availability-col">Available at</th>
            </tr>
          </thead>
          <tbody>
            <!-- For loop to generate rows -->
            <?php foreach ($found_books as $book) { ?>
              <tr onclick="redirectToBookDetails(<?php echo $book['book_id']; ?>)">
                <td headers="cover-col">
                  <img src="img/books/<?php echo $book['cover_path']; ?>" alt="Book Cover" class="book-cover" />
                </td>
                <td headers="title-col">
                  <?php echo htmlspecialchars($book['title']); ?>
                </td>
                <td headers="author-col">
                  <?php echo htmlspecialchars($book['author']); ?>
                </td>
                <td headers="category-col">
                  <?php echo htmlspecialchars($book['category']); ?>
                </td>
                <td headers="format-col">
                  <div class="format-column">
                    <div>
                      <img src="img/ui/check.png" alt="Available" /> Hard Copy
                    </div>
                    <div>
                    <img src="<?php echo !empty($book['ebook_file_path']) ? 'img/ui/check.png' : 'img/ui/cross.png'; ?>" /> E-book
                    </div>
                    <div>
                    <img src="<?php echo !empty($book['audio_file_path']) ? 'img/ui/check.png' : 'img/ui/cross.png'; ?>" /> Audio Book
                    </div>
                  </div>
                </td>
                <td headers="availability-col">
                  <div class="availability-column">
                  <?php
                  // Prepare the SQL query to find unique universities
                  $sql_uni_query = "
                    SELECT DISTINCT u.university_id
                    FROM universities u
                    JOIN branches b ON u.university_id = b.university_id
                    JOIN book_availability ba ON b.branch_id = ba.branch_id
                    WHERE ba.book_id = ? AND ba.available_copies > 0;
                  ";

                  $stmt_uni = $conn->prepare($sql_uni_query);
                  $stmt_uni->bind_param("i", $book['book_id']);
                  $stmt_uni->execute();
                  $result_uni = $stmt_uni->get_result();

                  $universities = [];
                  while ($uni_row = $result_uni->fetch_assoc()) {
                    $universities[] = $uni_row['university_id']; // Store each university_id
                  }

                  $stmt_uni->close();

                  // Display the availability based on the conditions
                  if (empty($universities)) {
                    echo '<div><img src="img/ui/red-map.png" alt="Uni Icon" /><span style="color:red;">Not Available</span></div>';
                  } elseif (count($universities) <= 2) {
                      foreach ($universities as $uni_id) {
                        echo '<div><img src="img/ui/map.png" alt="Uni Icon" /> ' . htmlspecialchars($uni_id) . '</div>';
                      }
                  } else {
                    // Show the first two universities and "And more"
                    echo '<div><img src="img/ui/map.png" alt="Uni Icon" /> ' . htmlspecialchars($universities[0]) . '</div>';
                    echo '<div><img src="img/ui/map.png" alt="Uni Icon" /> ' . htmlspecialchars($universities[1]) . '</div>';
                    echo '<div><img src="img/ui/map.png" alt="Uni Icon" /> And more</div>';
                  }
                  ?>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <?php } else { ?>
        <div class="no-books-message">
          <img src="img/ui/nothing-here.png" alt="Nothing here" />
        </div>
      <?php } ?>
    </section>

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

    <!-- Toggling Advanced Search Form -->
    <script>
      // When clicking "Advanced Search" button
      function handleAdvancedSearch() {
        <?php if (isset($_SESSION['user_id'])) { ?>
          // Toggle the form display if the user is logged in
          toggleSearchForm();
        <?php } else { ?>
          // Show alert if the user is not logged in
          alert("Only registered members can use this feature.");
        <?php } ?>
      }

      function toggleSearchForm() {
        const searchForm = document.querySelector(".advanced-search-form");

        // Toggle the display style of the form
        if (
          searchForm.style.display === "none" ||
          searchForm.style.display === ""
        ) {
          searchForm.style.display = "block"; // Show the form
        } else {
          searchForm.style.display = "none"; // Hide the form
        }
      }

      // When clicking "Cancel" button
      function closeSearchForm() {
        // Hide the advanced search form
        const searchForm = document.querySelector(".advanced-search-form");
        searchForm.style.display = "none"; // Hide the form
      }
    </script>

    <!-- Toggling dropdown in Advanced Search Form -->
    <script>
      function toggleDropdown(dropdownId) {
        var dropdown = document.getElementById(dropdownId);
        dropdown.style.display =
          dropdown.style.display === "block" ? "none" : "block";
      }
    </script>

    <!-- Show the button carousel dynamically -->
    <script>
      // maybe this can be improved by getting unique categories from the database
      const buttons = [
        "Mathematics & Statistics",
        "Natural Sciences (Physics, Chemistry, Biology)",
        "Computer Science & Technology",
        "Humanities & Social Science",
        "Business & Finance",
        "Medicine",
        "Literature & Language",
        "Arts & Design",
        "Engineering",
        "Fiction & Novels",
      ];

      let currentIndex = 0;

      function createCategoryButton(category) {
        const button = document.createElement("button");
        button.className = "warm-gradient-button category-button";
        button.textContent = category;
        button.onclick = function () {
          // Redirect to a PHP script with the selected category using GET method
          window.location.href = `search-page.php?category=${encodeURIComponent(
            category
          )}`;
        };
        return button;
      }

      function moveCarousel(direction) {
        const buttonContainer = document.querySelector(".button-container");
        const totalButtons = buttons.length;
        const visibleCount = 4; // Number of buttons to show at a time

        // Update current index based on direction
        currentIndex += direction;

        // Prevent going out of bounds
        if (currentIndex < 0) {
          currentIndex = 0;
        } else if (currentIndex > totalButtons - visibleCount) {
          currentIndex = totalButtons - visibleCount;
        }

        // Clear current buttons
        buttonContainer.innerHTML = "";

        // Create buttons dynamically based on the current index
        for (let i = currentIndex; i < currentIndex + visibleCount; i++) {
          if (buttons[i]) {
            const button = createCategoryButton(buttons[i]);
            buttonContainer.appendChild(button);
          }
        }
      }

      // Initialize with the first set of buttons
      moveCarousel(0);
    </script>

    <!-- Redirect to Book details page -->
    <script>
      function redirectToBookDetails(bookId) {
        window.location.href = "book-details.php?book_id=" + bookId;
      }
    </script>
  </body>
</html>