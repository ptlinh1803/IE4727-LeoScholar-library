<?php 
include 'db-connect.php';
session_start();

// if user or guest --> cannot access, redirect to homepage-member.php
if (isset($_SESSION['user_id'])) {
  header('Location: homepage-member.php');
  exit();
  // later maybe we need  || !isset($_SESSION['librarian_id']) too
}

// for testing only
$_SESSION['librarian_id'] = 1;

if (isset($_SESSION['librarian_id'])) {
  $librarian_id = $_SESSION['librarian_id'];

  // get librarian info--------------------------------------------------------
  $get_lib_query = "SELECT * FROM librarians WHERE librarian_id = ?";
  $stmt = $conn->prepare($get_lib_query);
  $stmt->bind_param("i", $librarian_id);
  $stmt->execute();
  $librarian_result = $stmt->get_result();
  $librarian = $librarian_result->fetch_assoc();
  $stmt->close();

  // get all branches in that uni-----------------------------------------------
  $get_branches_query = "SELECT * FROM branches WHERE university_id = ?";
  $stmt = $conn->prepare($get_branches_query);
  $stmt->bind_param("s", $librarian['university_id']);
  $stmt->execute();
  $branches_result = $stmt->get_result();
  $branches = [];
  if ($branches_result->num_rows > 0) {
    while ($row = $branches_result->fetch_assoc()) {
      $branches[] = $row;
    }
  }
  $stmt->close();

  // ------------------------- DATA ANALYTICS -------------------------

  // get total number of users
  $university_id = $librarian['university_id'];
  $get_total_students = "SELECT COUNT(*) AS total_users FROM users WHERE university_id = ?";
  $stmt = $conn->prepare($get_total_students);
  $stmt->bind_param("s", $university_id);
  $stmt->execute();
  $total_users_result = $stmt->get_result();
  $total_users_row = $total_users_result->fetch_assoc();
  $total_users = $total_users_row['total_users'];
  $stmt->close();

  // get total number of available copies
  $get_total_copies = "
    SELECT SUM(ba.available_copies) AS total_available_copies
    FROM branches b
    JOIN book_availability ba ON b.branch_id = ba.branch_id
    WHERE b.university_id = ?;
    ";
  $stmt = $conn->prepare($get_total_copies);
  $stmt->bind_param("s", $university_id);
  $stmt->execute();
  $total_copies_result = $stmt->get_result();
  $total_copies_row = $total_copies_result->fetch_assoc();
  $total_copies = $total_copies = $total_copies = $total_copies_row['total_available_copies'] ?? 0;
  $stmt->close();

  // get number of available copies and pending at each branch
  $get_summary_table = "
    WITH CTE1 AS (
    SELECT b.branch_id, b.branch_name,
          COALESCE(SUM(ba.available_copies), 0) AS total_available_copies
      FROM branches b
      LEFT JOIN book_availability ba 
      ON b.branch_id = ba.branch_id
      WHERE b.university_id = ?
      GROUP BY b.branch_id
    ), 
    CTE2 AS (
    SELECT b.branch_id, b.branch_name,
          COALESCE(COUNT(d.donation_id), 0) AS pending_contributions
    FROM branches b
    LEFT JOIN donations d ON b.branch_id = d.branch_id AND d.status = 'pending'
    WHERE b.university_id = ?
    GROUP BY b.branch_id
    )

    SELECT CTE1.branch_id, CTE1.branch_name, CTE1.total_available_copies, CTE2.pending_contributions
    FROM CTE1 JOIN CTE2
    ON CTE1.branch_id = CTE2.branch_id;
  ";
  $stmt = $conn->prepare($get_summary_table);
  $stmt->bind_param("ss", $university_id, $university_id);
  $stmt->execute();
  $summary_table_result = $stmt->get_result();
  $summary_table = [];
  if ($summary_table_result->num_rows > 0) {
    while ($row = $summary_table_result->fetch_assoc()) {
      $summary_table[] = $row;
    }
  }
  $stmt->close();

  // ------------------------- MANAGE BOOKS -------------------------
  // get list of categories
  $sql_list_categories = "SELECT DISTINCT(category) from books;";
  $categories_results = $conn->query($sql_list_categories);

  if ($categories_results) {
    $categories_list = []; // Initialize an array to store the categories
    while ($row = $categories_results->fetch_assoc()) {
        $categories_list[] = $row['category']; // Add each category to the array
    }
  }

  if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
    // Capture GET inputs
    $fulltext_input = $_GET['searchQuery'] ?? '';
    $title_input = $_GET['title'] ?? '';
    $author_input = $_GET['author'] ?? '';
    $category_input = $_GET['category'] ?? '';
    $isbn_input = $_GET['isbn'] ?? '';
    $yearFrom_input = $_GET['yearFrom'] ?? '';
    $yearTo_input = $_GET['yearTo'] ?? '';
    $formats_input = $_GET['format'] ?? [];
    $availableAt_input = $_GET['availableAt'] ?? [];

    // Start building the SQL query
    $sql_query = "
    SELECT b.* FROM books b
    WHERE b.book_id IN (
        SELECT ba.book_id FROM book_availability ba
        JOIN branches br ON ba.branch_id = br.branch_id
        JOIN universities u ON br.university_id = u.university_id
        WHERE u.university_id = '" . $conn->real_escape_string($university_id) . "'
    )";
    $where_conditions = [];

    // Add conditions based on input
    if (!empty($fulltext_input)) {
      $where_conditions[] = "MATCH(title, author, description, category) AGAINST('" . $conn->real_escape_string($fulltext_input) . "' IN NATURAL LANGUAGE MODE)";
    }

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
      foreach ($availableAt_input as $branch) {
          $availableAt_conditions[] = "b.book_id IN (
              SELECT ba.book_id 
              FROM book_availability ba
              JOIN branches br 
              ON ba.branch_id = br.branch_id
              WHERE br.branch_id = $branch
          )";
      }
      if (!empty($availableAt_conditions)) {
          $where_conditions[] = "(" . implode(' OR ', $availableAt_conditions) . ")";
      }
    }

    // Combine conditions if any exist
    if (!empty($where_conditions)) {
      $sql_query .= " AND " . implode(' AND ', $where_conditions);
    }

    // Handle sorting
    if (!empty($_GET['sortBy'])) {
      $sortby = $_GET['sortBy'];
      $sql_query .= " ORDER BY $sortby";
    }
  } else {
    // Show all books at that library by default if no GET
    $sql_query = "
      SELECT * FROM books
      WHERE book_id IN (
        SELECT ba.book_id FROM book_availability ba
        JOIN branches br ON ba.branch_id = br.branch_id
        JOIN universities u ON br.university_id = u.university_id
        WHERE u.university_id = '" . $conn->real_escape_string($university_id) . "'
      )
    ";
  }

  
  $results = $conn->query($sql_query);
  $found_books = []; // Initialize the array
  if ($results->num_rows > 0) {
    while ($book_row = $results->fetch_assoc()) {
        $found_books[] = $book_row; // Store each book in the array
    }
  }

  // Get current books per page
  if (!empty($found_books)) {
    // Set the number of items per page
    $booksPerPage = 10;

    // Get the current page from the URL, default to page 1 if not set
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $currentPage = max(1, $currentPage); // Ensure currentPage is at least 1

    // Calculate the offset and total pages
    $totalBooks = count($found_books);
    $totalPages = ceil($totalBooks / $booksPerPage);
    $offset = ($currentPage - 1) * $booksPerPage;

    // Get the books for the current page
    $booksOnCurrentPage = array_slice($found_books, $offset, $booksPerPage);
  } else {
      // Handle the case where there are no books found
      $booksOnCurrentPage = [];
      $totalPages = 1;
      $currentPage = 1;
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
        <button class="warm-gradient-button" id="manage-books-button">
          <img src="img/ui/book.png" alt="Manage Books Icon" />
          <span>Manage Books</span>
        </button>
        <button class="warm-gradient-button" id="data-analytics-button">
          <img src="img/ui/dashboard.png" alt="Data Analytics Icon" />
          <span>Data Analytics</span>
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
          <p class="big-number">
            <?php echo htmlspecialchars($total_users); ?>
          </p>
        </div>

        <div class="info-group">
          <h2>Number of Books in your University</h2>
          <p class="big-number">
            <?php echo htmlspecialchars($total_copies); ?>
          </p>
        </div>
      </div>

      <?php if (!empty($summary_table)) { ?>
        <table class="library-table">
          <thead>
            <tr>
              <th>Branch</th>
              <th>Number of Available Books</th>
              <th>Number of Pending Contributions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($summary_table as $row) { ?>
              <tr>
                <td><?php echo htmlspecialchars($row['branch_name']); ?></td>
                <td><?php echo htmlspecialchars($row['total_available_copies']); ?></td>
                <td><?php echo htmlspecialchars($row['pending_contributions']); ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <?php } ?>
    </section>

    <!---------------------------Manage Books section------------------------------------ -->
    <section class="lib-section" id="manage-books-section">
      <h1 class="big-blue-h1">Manage Books</h1>

      <!-- Search bar -->
      <div class="lib-search">
        <form class="search-bar" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
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
            placeholder="Quick Search..."
            value="<?php echo isset($_GET['searchQuery']) ? htmlspecialchars($_GET['searchQuery']) : ''; ?>" 
          />
          <button type="submit" class="submit-button">
            <img src="img/ui/small-search-icon.png" alt="Search Icon" />
          </button>
          <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="submit-button reset-button" style="color: white;" onclick="resetForm()">
            Reset
          </a>
        </form>

        <!-- Advanced Search Form -->
        <form
          class="advanced-search-form"
          id="advancedSearchForm"
          method="GET"
          action="<?php echo $_SERVER['PHP_SELF']; ?>"
        >
          <div>
            <label>Quick Search:</label>
            <input
              type="text"
              name="searchQuery"
              value="<?php echo isset($_GET['searchQuery']) ? htmlspecialchars($_GET['searchQuery']) : ''; ?>" 
            />
          </div>
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
                    min="1800"
                    class="year-input"
                    value="<?php echo isset($_GET['yearFrom']) ? htmlspecialchars($_GET['yearFrom']) : ''; ?>"
                  />
                  <span class="separator">-</span>
                  <input
                    type="number"
                    name="yearTo"
                    placeholder="To"
                    min="1800"
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
                <span>Select Branches</span>
                <img
                  src="img/ui/drop-down-icon.svg"
                  alt="Arrow Down Icon"
                  class="dropdown-icon"
                />
              </div>
              <div class="checkbox-dropdown-content" id="availabilityDropdown">
                <?php foreach($branches as $branch) { ?>
                  <label>
                    <input type="checkbox" name="availableAt[]" value="<?php echo htmlspecialchars($branch['branch_id']); ?>" 
                    <?php echo (isset($_GET['availableAt']) && in_array(htmlspecialchars($branch['branch_id']), $_GET['availableAt'])) ? 'checked' : ''; ?>
                    />
                    <?php echo htmlspecialchars($branch['branch_name']); ?>
                  </label>
                <?php } ?>
              </div>
            </div>

            <div>
              <label>Sort by:</label>
              <div class="sort-dropdown">
                <select name="sortBy" id="sortBy" class="sort-dropdown-select">
                  <option value="">None</option>
                  <option value="title ASC" <?php echo (isset($_GET['sortBy']) && $_GET['sortBy'] == 'title ASC') ? 'selected' : ''; ?>>
                    Title A-Z
                  </option>
                  <option value="title DESC" <?php echo (isset($_GET['sortBy']) && $_GET['sortBy'] == 'title DESC') ? 'selected' : ''; ?>>
                    Title Z-A
                  </option>
                  <option value="publication_year DESC" <?php echo (isset($_GET['sortBy']) && $_GET['sortBy'] == 'publication_year DESC') ? 'selected' : ''; ?>>
                    Newest
                  </option>
                  <option value="publication_year ASC" <?php echo (isset($_GET['sortBy']) && $_GET['sortBy'] == 'publication_year ASC') ? 'selected' : ''; ?>>
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
      </div>

      <!-- Display books -->
      <?php if (!empty($found_books)) { ?>
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
            <?php foreach ($booksOnCurrentPage as $book) { ?>
              <tr onclick="redirectToEditBookDetails(<?php echo $book['book_id']; ?>)">
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
                <td headers="year-col">
                  <?php echo htmlspecialchars($book['publication_year']); ?>
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
              </tr>
            <?php } ?>
          </tbody>
        </table>

        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?php echo $currentPage - 1; ?>" class="arrow">Previous</a>
            <?php endif; ?>

            <span>Page <?php echo $currentPage; ?> of <?php echo $totalPages; ?></span>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?php echo $currentPage + 1; ?>" class="arrow">Next</a>
            <?php endif; ?>
        </div>

      <?php } else { ?>
        <div class="no-books-message">
          <img src="img/ui/nothing-here.png" alt="Nothing here" />
        </div>
      <?php } ?>
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
        const activeTab = urlParams.get("active_tab") || "manage-books"; // Default to "manage-books"

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

    <!-- Redirect to Book details page -->
    <script>
      function redirectToEditBookDetails(bookId) {
        // change to the correct edit-book-details page
        window.open("book-details.php?book_id=" + bookId, "_blank");
      }
    </script>

    <!-- Clear all the search inputs with Reset button --> 
    <script>
      function resetForm() {
        // Redirect to the current page without any query parameters
        window.location.href = window.location.origin + window.location.pathname;
      }
    </script>
  </body>
</html>