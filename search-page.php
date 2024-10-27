<?php
include 'db-connect.php';
session_start();

// get list of categories--------------
$sql_list_categories = "SELECT DISTINCT(category) from books;";
$categories_results = $conn->query($sql_list_categories);

if ($categories_results) {
  $categories_list = []; // Initialize an array to store the categories
  while ($row = $categories_results->fetch_assoc()) {
      $categories_list[] = $row['category']; // Add each category to the array
  }
} else {
  // Handle query error
  echo "Error: " . $conn->error;
}

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
    while ($book_row = $results->fetch_assoc()) {
        $found_books[] = $book_row; // Store each book in the array
    }
  }

  $stmt->close(); // Close the statement
} else if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
  // Capture GET inputs
  $title_input = $_GET['title'] ?? '';
  $author_input = $_GET['author'] ?? '';
  $category_input = $_GET['category'] ?? '';
  $isbn_input = $_GET['isbn'] ?? '';
  $yearFrom_input = $_GET['yearFrom'] ?? '';
  $yearTo_input = $_GET['yearTo'] ?? '';
  $formats_input = $_GET['format'] ?? [];
  $availableAt_input = $_GET['availableAt'] ?? [];

  // Start building the SQL query
  $sql_query = "SELECT b.* FROM books b";
  $where_conditions = [];

  // Add conditions based on input
  if (!empty($title_input)) {
    $where_conditions[] = "b.title LIKE '%" . $conn->real_escape_string($title_input) . "%'";
  }

  if (!empty($author_input)) {
    $where_conditions[] = "b.author LIKE '%" . $conn->real_escape_string($author_input) . "%'";
  }

  if (!empty($category_input)) {
    $where_conditions[] = "b.category = '" . $conn->real_escape_string($category_input) . "'";
  }

  if (!empty($isbn_input)) {
    $where_conditions[] = "b.isbn LIKE '%" . $conn->real_escape_string($isbn_input) . "%'";
  }

  if (!empty($yearFrom_input)) {
    $where_conditions[] = "b.publication_year >= " . (int)$yearFrom_input;
  }

  if (!empty($yearTo_input)) {
    $where_conditions[] = "b.publication_year <= " . (int)$yearTo_input;
  }

  // Handling multiple format choices: include a book if they have any of the selected options (OR)
  if (!empty($formats_input)) {
    $format_conditions = [];
    if (in_array("Hard Copy", $formats_input)) {
        $format_conditions[] = "b.hard_copy = 1";
    }
    if (in_array("E-book", $formats_input)) {
        $format_conditions[] = "b.ebook_file_path IS NOT NULL AND b.ebook_file_path != ''";
    }
    if (in_array("Audio Book", $formats_input)) {
        $format_conditions[] = "b.audio_file_path IS NOT NULL AND b.audio_file_path != ''";
    }
    if (!empty($format_conditions)) {
        $where_conditions[] = "(" . implode(' OR ', $format_conditions) . ")";
    }
  }

  // Handling multiple availableAt choices: include a book if they have any of the selected options (OR)
  if (!empty($availableAt_input)) {
    $availableAt_conditions = [];
    // Map university names to their IDs or names in the `universities` table
    foreach ($availableAt_input as $university) {
        $availableAt_conditions[] = "b.book_id IN (
            SELECT ba.book_id FROM book_availability ba
            JOIN branches br ON ba.branch_id = br.branch_id
            JOIN universities u ON br.university_id = u.university_id
            WHERE u.university_id = '" . $conn->real_escape_string($university) . "'
        )";
    }
    if (!empty($availableAt_conditions)) {
        $where_conditions[] = "(" . implode(' OR ', $availableAt_conditions) . ")";
    }
  }

  // Combine conditions if any exist
  if (!empty($where_conditions)) {
    $sql_query .= " WHERE " . implode(' AND ', $where_conditions);
  }

  // Handle sorting
  if (!empty($_GET['sortBy'])) {
    switch ($_GET['sortBy']) {
        case 'titleAsc':
            $sql_query .= " ORDER BY title ASC";
            break;
        case 'titleDesc':
            $sql_query .= " ORDER BY title DESC";
            break;
        case 'newest':
            $sql_query .= " ORDER BY publication_year DESC"; // Sort by newest publication year
            break;
        case 'oldest':
            $sql_query .= " ORDER BY publication_year ASC"; // Sort by oldest publication year
            break;
        default:
            break;
    }
}

  // Prepare and execute the query
  $results = $conn->query($sql_query);
  $found_books = []; // Initialize the array
  if ($results->num_rows > 0) {
    while ($book_row = $results->fetch_assoc()) {
        $found_books[] = $book_row; // Store each book in the array
    }
  }
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
            action="<?php echo $_SERVER['PHP_SELF']; ?>"
            method="GET"
          >
            <div>
              <label>Title:</label>
              <input 
                type="text" 
                name="title" 
                value="<?php echo isset($_GET['title']) ? htmlspecialchars($_GET['title']) : ''; ?>" 
              />
            </div>
            <div>
              <label>Author:</label>
              <input 
                type="text" 
                name="author" 
                value="<?php echo isset($_GET['author']) ? htmlspecialchars($_GET['author']) : ''; ?>"
              />
            </div>
            <div>
              <label>Category:</label>
              <!-- <input type="text" name="category" /> -->
              <div class="sort-dropdown">
                <select name="category" id="sortBy" class="sort-dropdown-select">
                  <option value="">Select a category</option> <!-- Default option -->
                  <?php foreach ($categories_list as $category) { ?>
                      <option value="<?php echo htmlspecialchars($category); ?>" 
                      <?php echo (isset($_GET['category']) && $_GET['category'] == $category) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category); ?>
                      </option>
                  <?php } ?>
                </select>
                <img src="img/ui/drop-down-icon.svg" alt="Dropdown Icon" />
              </div>
            </div>
            <div class="form-group">
              <div class="isbn-group">
                <label for="isbn">ISBN:</label>
                <input 
                  type="text" 
                  id="isbn" 
                  name="isbn" 
                  class="isbn-input" 
                  value="<?php echo isset($_GET['isbn']) ? htmlspecialchars($_GET['isbn']) : ''; ?>"
                />
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
                    value="<?php echo isset($_GET['yearFrom']) ? htmlspecialchars($_GET['yearFrom']) : ''; ?>"
                  />
                  <span class="separator">-</span>
                  <input
                    type="number"
                    name="yearTo"
                    placeholder="To"
                    min="1900"
                    max="2100"
                    class="year-input"
                    value="<?php echo isset($_GET['yearTo']) ? htmlspecialchars($_GET['yearTo']) : ''; ?>"
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
                    <input type="checkbox" name="format[]" value="Hard Copy" 
                    <?php echo (isset($_GET['format']) && in_array('Hard Copy', $_GET['format'])) ? 'checked' : ''; ?>
                    />
                    Hard Copy
                  </label>
                  <label>
                    <input type="checkbox" name="format[]" value="E-book" 
                    <?php echo (isset($_GET['format']) && in_array('E-book', $_GET['format'])) ? 'checked' : ''; ?>
                    />
                    E-book
                  </label>
                  <label>
                    <input type="checkbox" name="format[]" value="Audio Book" 
                    <?php echo (isset($_GET['format']) && in_array('Audio Book', $_GET['format'])) ? 'checked' : ''; ?>
                    />
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
                  <label>
                    <input type="checkbox" name="availableAt[]" value="NTU" 
                    <?php echo (isset($_GET['availableAt']) && in_array('NTU', $_GET['availableAt'])) ? 'checked' : ''; ?>
                    />
                    NTU
                  </label>
                  <label>
                    <input type="checkbox" name="availableAt[]" value="NUS" 
                    <?php echo (isset($_GET['availableAt']) && in_array('NUS', $_GET['availableAt'])) ? 'checked' : ''; ?>
                    />
                    NUS
                  </label>
                  <label>
                    <input type="checkbox" name="availableAt[]" value="SMU" 
                    <?php echo (isset($_GET['availableAt']) && in_array('SMU', $_GET['availableAt'])) ? 'checked' : ''; ?>
                    />
                    SMU
                  </label>
                  <label>
                    <input type="checkbox" name="availableAt[]" value="SUTD" 
                    <?php echo (isset($_GET['availableAt']) && in_array('SUTD', $_GET['availableAt'])) ? 'checked' : ''; ?>
                    />
                    SUTD
                  </label>
                  <label>
                    <input type="checkbox" name="availableAt[]" value="SIT" 
                    <?php echo (isset($_GET['availableAt']) && in_array('SIT', $_GET['availableAt'])) ? 'checked' : ''; ?>
                    />
                    SIT
                  </label>
                  <label>
                    <input type="checkbox" name="availableAt[]" value="SUSS" 
                    <?php echo (isset($_GET['availableAt']) && in_array('SUSS', $_GET['availableAt'])) ? 'checked' : ''; ?>
                    />
                    SUSS
                  </label>
                </div>
              </div>

              <div>
                <label>Sort by:</label>
                <div class="sort-dropdown">
                  <select name="sortBy" id="sortBy" class="sort-dropdown-select">
                    <option value="">Default</option>
                    <option value="titleAsc" <?php echo (isset($_GET['sortBy']) && $_GET['sortBy'] == 'titleAsc') ? 'selected' : ''; ?>>
                      Title A-Z
                    </option>
                    <option value="titleDesc" <?php echo (isset($_GET['sortBy']) && $_GET['sortBy'] == 'titleDesc') ? 'selected' : ''; ?>>
                      Title Z-A
                    </option>
                    <option value="newest" <?php echo (isset($_GET['sortBy']) && $_GET['sortBy'] == 'newest') ? 'selected' : ''; ?>>
                      Newest
                    </option>
                    <option value="oldest" <?php echo (isset($_GET['sortBy']) && $_GET['sortBy'] == 'oldest') ? 'selected' : ''; ?>>
                      Oldest
                    </option>
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
                      <img src="<?php echo $book['hard_copy'] == 0 ? 'img/ui/cross.png' : 'img/ui/check.png'; ?>" /> Hard Copy
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
      const buttons = [];
      <?php
      foreach ($categories_list as $category) {
        // Use JavaScript string literal syntax for each category
        echo "buttons.push('$category');\n";
      }
      ?>

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
