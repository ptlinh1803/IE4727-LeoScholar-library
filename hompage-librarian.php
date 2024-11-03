<?php 
include 'db-connect.php';
session_start();

// if user --> cannot access, redirect to homepage-member.php
if (isset($_SESSION['user_id'])) {
  header('Location: homepage-member.php');
  exit();
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
    <link rel="stylesheet" href="hp-librarian-styles.css" />
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
        <a href="homepage-librarian.php" class="active-page">Home</a>
        <a href="#">Logout</a>
      </div>
    </nav>

    <!-- Header -->
    <header class="header hp-lib-header">
      <div>
        <h2>Welcome to</h2>
        <img src="img/ui/leoscholar-transparent.png" alt="Logo" />
        <p>An Integrated Portal to Manage Your Libraries</p>
      </div>
    </header>

    <!-- What would you like to do today section -->
    <section class="services">
      <h1 class="big-blue-h1">What would you like to do today?</h1>
      <div class="service-button-container lib-service">
        <button class="warm-gradient-button" id="data-analytics-button">
          <img src="img/ui/dashboard.png" alt="Data Analytics Icon" />
          <span>Data Analytics</span>
        </button>
        <button class="warm-gradient-button" id="manage-books-button">
          <img src="img/ui/book.png" alt="Manage Books Icon" />
          <span>Manage Books</span>
        </button>
        <button class="warm-gradient-button" id="add-books-button">
          <img src="img/ui/add.png" alt="Add new Book Icon" />
          <span>Add new Books</span>
        </button>
        <button class="warm-gradient-button" id="manage-contribution-button">
          <img src="img/ui/donate.png" alt="Donate a book Icon" />
          <span>Manage Contributions</span>
        </button>
      </div>
    </section>

    <!-- --------------------Data Analytics section------------------------ -->
    <section class="lib-section" id="data-analytics-section">
      <h1 class="big-blue-h1">Data Analytics</h1>
      <div class="info-container">
        <div class="info-group">
          <h2>Number of Members from your University</h2>
          <p class="big-number">180</p>
        </div>

        <div class="info-group">
          <h2>Number of Books in your University</h2>
          <p class="big-number">180</p>
        </div>
      </div>

      <table class="library-table">
        <thead>
          <tr>
            <th>Branch</th>
            <th>Number of Available Books</th>
            <th>Number of Pending Contributions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Art, Design & Media Library</td>
            <td>1000</td>
            <td>10</td>
          </tr>
          <tr>
            <td>Business Library</td>
            <td>1500</td>
            <td>2</td>
          </tr>
          <tr>
            <td>Communication & Information Library</td>
            <td>1300</td>
            <td>3</td>
          </tr>
          <tr>
            <td>Humanities & Social Sciences Library</td>
            <td>1200</td>
            <td>2</td>
          </tr>
          <tr>
            <td>Lee Wee Nam Library</td>
            <td>2000</td>
            <td>25</td>
          </tr>
        </tbody>
      </table>
    </section>

    <!---------------------------Manage Books section------------------------------------ -->
    <section class="lib-section" id="manage-books-section">
      <h1 class="big-blue-h1">Manage Books</h1>

      <!-- Search bar -->
      <div class="lib-search">
        <form class="search-bar" action="" method="POST">
          <button
            type="button"
            class="advanced-search-button"
            onclick="toggleSearchForm()"
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
      </div>

      <!-- Display books -->
      <table class="book-table">
        <thead>
          <tr>
            <th id="cover-col"></th>
            <th id="title-col">Title</th>
            <th id="author-col">Author</th>
            <th id="category-col">Category</th>
            <th id="year-col">Publication Year</th>
            <th id="format-col">Format</th>
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
            <td headers="year-col">2011</td>
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
          </tr>

          <tr>
            <td headers="cover-col">
              <img src="img/books/2.jpg" alt="Book Cover" class="book-cover" />
            </td>
            <td headers="title-col">Linear Algebra and Its Applications</td>
            <td headers="author-col">Gilbert Strang</td>
            <td headers="category-col">Mathematics & Statistics</td>
            <td headers="year-col">2011</td>
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
          </tr>

          <tr>
            <td headers="cover-col">
              <img src="img/books/3.jpg" alt="Book Cover" class="book-cover" />
            </td>
            <td headers="title-col">Linear Algebra and Its Applications</td>
            <td headers="author-col">Gilbert Strang</td>
            <td headers="category-col">Mathematics & Statistics</td>
            <td headers="year-col">2011</td>
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
          </tr>
        </tbody>
      </table>
    </section>

    <!-- --------------------------Add new Books section------------------------------ -->
    <section class="lib-section" id="add-books-section">
      <h1 class="big-blue-h1">Add new Books</h1>

      <!-- Form -->
      <div class="form-container">
        <form
          action=""
          method="POST"
          enctype="multipart/form-data"
          onsubmit="return validateBranches()"
        >
          <label for="title">Title *:</label><br />
          <input type="text" id="title" name="title" required /><br /><br />

          <label for="author">Author *:</label><br />
          <input type="text" id="author" name="author" required /><br /><br />

          <label for="isbn">ISBN:</label><br />
          <input type="text" id="isbn" name="isbn" /><br /><br />

          <label for="publication_year">Publication Year *:</label><br />
          <input
            type="number"
            id="publication_year"
            name="publication_year"
            required
            min="1800"
          /><br /><br />

          <label for="category">Category *:</label><br />
          <select id="category" name="category" required>
            <option value="Computer Science & Technology">
              Computer Science & Technology
            </option>
            <option value="Humanities & Social Science">
              Humanities & Social Science
            </option>
            <option value="Business & Finance">Business & Finance</option>
            <option value="Medicine">Medicine</option></select
          ><br /><br />

          <label for="branch">Branch & Available Copies *:</label><br />

          <div id="branchContainer">
            <div class="branch-group">
              <select name="branches[]" required>
                <option value="">Select Branch</option>
                <option value="NTU">NTU</option>
                <option value="NUS">NUS</option>
                <option value="SMU">SMU</option>
              </select>
              <input
                type="number"
                name="available_copies[]"
                placeholder="Available Copies"
                required
                min="0"
              />
              <button type="button" onclick="removeBranch(this)">Remove</button>
            </div>
          </div>

          <br />
          <button type="button" onclick="addBranch()">
            Add Another Branch
          </button>
          <br /><br />

          <label for="book_description">Book Description:</label><br />
          <textarea
            id="book_description"
            name="book_description"
            rows="4"
            cols="50"
          ></textarea
          ><br /><br />

          <label for="about_author">About the Author:</label><br />
          <textarea
            id="about_author"
            name="about_author"
            rows="4"
            cols="50"
          ></textarea
          ><br /><br />

          <label for="cover_image">Cover Image:</label><br />
          <input
            type="file"
            id="cover_image"
            name="cover_image"
            accept="image/*"
          /><br /><br />

          <label for="e_book">E-book (pdf/epub/mobi):</label><br />
          <input
            type="file"
            id="e_book"
            name="ebook_file_path"
            accept=".pdf,.epub,.mobi"
          /><br /><br />

          <label for="audio_book">Audio Book:</label><br />
          <input
            type="file"
            id="audio_book"
            name="audio_file_path"
            accept="audio/*"
          /><br /><br />

          <input type="submit" value="Submit" />
          <input type="reset" value="Reset" />
        </form>
      </div>
    </section>

    <!-- --------------------------Manage Contributions section------------------------------ -->
    <section class="lib-section" id="manage-contribution-section">
      <h1 class="big-blue-h1">Manage Contributions</h1>
      <p>Click on each row to see more details</p>

      <table class="book-table">
        <thead>
          <tr>
            <th id="cover-col"></th>
            <th id="title-col">Title</th>
            <th id="author-col">Author</th>
            <th id="branch-col">Branch</th>
            <th id="copies-col">Copies</th>
            <th id="status-col">Status</th>
            <th id="time-col">Created at</th>
            <th id="action-col"></th>
          </tr>
        </thead>
        <tbody>
          <!-- Example Row -->
          <tr onclick="toggleDetails(this)">
            <td headers="cover-col">
              <img src="img/books/3.jpg" alt="Book Cover" class="book-cover" />
            </td>
            <td headers="title-col">Introduction to Mathematical Statistics</td>
            <td headers="author-col">Harry Potter</td>
            <td headers="branch-col">NTU - Lee Wee Nam Library</td>
            <td headers="copies-col">1</td>
            <td headers="status-col">
              <span class="status-yellow">Pending</span>
            </td>
            <td headers="time-col">31/10/2024</td>
            <td>
              <form
                action=""
                method="POST"
                onsubmit="return confirm('Are you sure you want to return this book?');"
              >
                <input type="hidden" name="book_id" value="" />
                <input type="hidden" name="branch_id" value="" />
                <input type="hidden" name="loan_id" value="" />
                <input type="hidden" name="user_id" value="" />
                <input type="hidden" name="return_book" value="1" />
                <button
                  type="submit"
                  class="shelf-action-button acknowledge"
                  onclick="event.stopPropagation();"
                >
                  Accept
                </button>
              </form>
              <form
                action=""
                method="POST"
                onsubmit="return confirm('You may renew this book only once, extending the due date by 14 days. If you would like to keep it longer after that, please return it first, then borrow it again.');"
              >
                <input type="hidden" name="loan_id" value="" />
                <input type="hidden" name="renew_book" value="1" />
                <!-- Replace with actual user ID -->
                <button
                  type="submit"
                  class="shelf-action-button cancel"
                  onclick="event.stopPropagation();"
                >
                  Reject
                </button>
              </form>
            </td>
          </tr>
          <!-- Hidden Details Row -->
          <tr class="hidden-details">
            <td colspan="8">
              <strong>Description:</strong> A comprehensive guide to statistical
              methods.
              <br />
              <strong>About the Author:</strong> Harry Potter, a renowned author
              in statistics.
              <br />
              <strong>More Details:</strong> Published by XYZ Publications, 2023
              Edition.
            </td>
          </tr>
          <tr onclick="toggleDetails(this)">
            <td headers="cover-col">
              <img src="img/books/4.jpg" alt="Book Cover" class="book-cover" />
            </td>
            <td headers="title-col">Introduction to Mathematical Statistics</td>
            <td headers="author-col">Harry Potter</td>
            <td headers="branch-col">NTU - Lee Wee Nam Library</td>
            <td headers="copies-col">1</td>
            <td headers="status-col">
              <span class="status-yellow">Pending</span>
            </td>
            <td headers="time-col">31/10/2024</td>

            <td>
              <form
                action=""
                method="POST"
                onsubmit="return confirm('Are you sure you want to return this book?');"
              >
                <input type="hidden" name="book_id" value="" />
                <input type="hidden" name="branch_id" value="" />
                <input type="hidden" name="loan_id" value="" />
                <input type="hidden" name="user_id" value="" />
                <input type="hidden" name="return_book" value="1" />
                <button
                  type="submit"
                  class="shelf-action-button acknowledge"
                  onclick="event.stopPropagation();"
                >
                  Accept
                </button>
              </form>
              <form
                action=""
                method="POST"
                onsubmit="return confirm('You may renew this book only once, extending the due date by 14 days. If you would like to keep it longer after that, please return it first, then borrow it again.');"
              >
                <input type="hidden" name="loan_id" value="" />
                <input type="hidden" name="renew_book" value="1" />
                <!-- Replace with actual user ID -->
                <button
                  type="submit"
                  class="shelf-action-button cancel"
                  onclick="event.stopPropagation();"
                >
                  Reject
                </button>
              </form>
            </td>
          </tr>
          <!-- Hidden Details Row -->
          <tr class="hidden-details">
            <td colspan="8">
              <strong>Description:</strong> A comprehensive guide to statistical
              methods.
              <br />
              <strong>About the Author:</strong> Harry Potter, a renowned author
              in statistics.
              <br />
              <strong>More Details:</strong> Published by XYZ Publications, 2023
              Edition.
            </td>
          </tr>
        </tbody>
      </table>
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

    <!-- Toggle buttons and sections -->
    <script>
      // Function to handle tab click
      function openTab(selectedTab, sectionId) {
        // Get all book sections
        const sections = document.querySelectorAll(".lib-section");

        // Hide all sections
        sections.forEach((section) => {
          section.style.display = "none";
        });

        // Show the selected section
        document.getElementById(sectionId).style.display = "block"; // Change to "block" for div
      }

      // data-analytics-button
      document
        .getElementById("data-analytics-button")
        .addEventListener("click", function (event) {
          event.preventDefault();
          openTab(this, "data-analytics-section");
        });

      // manage-books-button
      document
        .getElementById("manage-books-button")
        .addEventListener("click", function (event) {
          event.preventDefault();
          openTab(this, "manage-books-section");
        });

      // add-books-button
      document
        .getElementById("add-books-button")
        .addEventListener("click", function (event) {
          event.preventDefault();
          openTab(this, "add-books-section");
        });

      // manage-contribution-button
      document
        .getElementById("manage-contribution-button")
        .addEventListener("click", function (event) {
          event.preventDefault();
          openTab(this, "manage-contribution-section");
        });

      document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get("active_tab") || "data-analytics"; // Default to "data-analytics"

        // Open the corresponding tab
        const tabToOpen = document.getElementById(`${activeTab}-button`);
        if (tabToOpen) {
          openTab(tabToOpen, `${activeTab}-section`);
        }
      });
    </script>

    <!-- Toggling Advanced Search Form -->
    <script>
      // When clicking "Advanced Search" button
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

    <!-- Add branch input dynamically -->
    <script>
      function addBranch() {
        const branchContainer = document.getElementById("branchContainer");

        const branchGroup = document.createElement("div");
        branchGroup.classList.add("branch-group");

        const select = document.createElement("select");
        select.name = "branches[]";
        select.required = true;
        select.innerHTML = `
    <option value="">Select Branch</option>
    <option value="NTU">NTU</option>
    <option value="NUS">NUS</option>
    <option value="SMU">SMU</option>
  `;

        const input = document.createElement("input");
        input.type = "number";
        input.name = "available_copies[]";
        input.placeholder = "Available Copies";
        input.required = true;
        input.min = "0";

        const removeButton = document.createElement("button");
        removeButton.type = "button";
        removeButton.textContent = "Remove";
        removeButton.onclick = function () {
          removeBranch(removeButton);
        };

        branchGroup.appendChild(select);
        branchGroup.appendChild(input);
        branchGroup.appendChild(removeButton);

        branchContainer.appendChild(branchGroup);
      }

      function removeBranch(button) {
        const branchGroup = button.parentElement;
        branchGroup.remove();
      }

      function validateBranches() {
        const branches = document.querySelectorAll("select[name='branches[]']");
        const selectedBranches = [];

        for (const branch of branches) {
          if (branch.value) {
            if (selectedBranches.includes(branch.value)) {
              alert(
                "Each branch must be unique. Please select different branches."
              );
              return false;
            }
            selectedBranches.push(branch.value);
          }
        }
        return true;
      }
    </script>

    <!-- Set the max attribute of the publication year input -->
    <script>
      // Get the current year
      const currentYear = new Date().getFullYear();
      document.getElementById("publication_year").max = currentYear;
    </script>

    <!-- Toggle hidden details of each contributions -->
    <script>
      function toggleDetails(row) {
        // Get the next row after the clicked row
        const detailsRow = row.nextElementSibling;

        // Check if the row is a hidden-details row and toggle display
        if (detailsRow && detailsRow.classList.contains("hidden-details")) {
          detailsRow.style.display =
            detailsRow.style.display === "none" ||
            detailsRow.style.display === ""
              ? "table-row"
              : "none";
        }
      }
    </script>
  </body>
</html>
