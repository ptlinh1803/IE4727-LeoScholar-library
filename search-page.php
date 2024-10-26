<?php
include 'db-connect.php';
session_start();

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
        <form class="search-bar" action="" method="POST">
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
            placeholder="Search..."
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
          <!-- Example Row -->
          <tr onclick="redirectToBookDetails(1)">
            <td headers="cover-col">
              <img src="img/books/1.jpg" alt="Book Cover" class="book-cover" />
            </td>
            <td headers="title-col">Introduction to Mathematical Statistics</td>
            <td headers="author-col">
              Robert V. Hogg, Joseph W. McKean, Allen T. Craig
            </td>
            <td headers="category-col">Mathematics & Statistics</td>
            <td headers="format-col">
              <div class="format-column">
                <div>
                  <img src="img/ui/check.png" alt="Available" /> Hard Copy
                </div>
                <div>
                  <img src="img/ui/cross.png" alt="Not Available" /> E-book
                </div>
                <div>
                  <img src="img/ui/check.png" alt="Available" /> Audio Book
                </div>
              </div>
            </td>
            <td headers="availability-col">
              <div class="availability-column">
                <div><img src="img/ui/map.png" alt="Uni Icon" /> NTU</div>
                <div><img src="img/ui/map.png" alt="Uni Icon" /> NUS</div>
                <div><img src="img/ui/map.png" alt="Uni Icon" /> And more</div>
              </div>
            </td>
          </tr>

          <tr>
            <td headers="cover-col">
              <img src="img/books/2.jpg" alt="Book Cover" class="book-cover" />
            </td>
            <td headers="title-col">Linear Algebra and Its Applications</td>
            <td headers="author-col">Gilbert Strang</td>
            <td headers="category-col">Mathematics & Statistics</td>
            <td headers="format-col">
              <div class="format-column">
                <div>
                  <img src="img/ui/check.png" alt="Available" /> Hard Copy
                </div>
                <div><img src="img/ui/check.png" alt="Available" /> E-book</div>
                <div>
                  <img src="img/ui/cross.png" alt="Not Available" /> Audio Book
                </div>
              </div>
            </td>
            <td headers="availability-col">
              <div class="availability-column">
                <div><img src="img/ui/map.png" alt="Uni Icon" /> NTU</div>
                <div><img src="img/ui/map.png" alt="Uni Icon" /> NUS</div>
              </div>
            </td>
          </tr>

          <tr>
            <td headers="cover-col">
              <img src="img/books/3.jpg" alt="Book Cover" class="book-cover" />
            </td>
            <td headers="title-col">Linear Algebra and Its Applications</td>
            <td headers="author-col">Gilbert Strang</td>
            <td headers="category-col">Mathematics & Statistics</td>
            <td headers="format-col">
              <div class="format-column">
                <div>
                  <img src="img/ui/check.png" alt="Available" /> Hard Copy
                </div>
                <div><img src="img/ui/check.png" alt="Available" /> E-book</div>
                <div>
                  <img src="img/ui/cross.png" alt="Not Available" /> Audio Book
                </div>
              </div>
            </td>
            <td headers="availability-col">
              <div class="availability-column">
                <div>
                  <img src="img/ui/red-map.png" alt="Uni Icon" /> Not Available
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="no-books-message">
        <img src="img/ui/nothing-here.png" alt="Nothing here" />
      </div>
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
