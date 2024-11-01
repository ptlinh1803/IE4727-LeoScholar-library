<?php
include 'db-connect.php';
include 'update-loan-fine-status.php';
session_start();

// Get all branches
$branchesQuery = "SELECT * FROM branches;";
$branchesResult = $conn->query($branchesQuery);

if ($branchesResult) {
  $branches = [];
  while ($branch = $branchesResult->fetch_assoc()) {
      $branches[] = $branch;
  }
} else {
  // Handle query error
  echo "Error: " . $conn->error;
}


// If not log in
if (!isset($_SESSION['user_id'])) {
  $message = "Hello, Guest! Please log in to access exclusive features and personalized content!";
} else {
  $user_id = $_SESSION['user_id'];
  // Update loans & fines for this user-------------------
  updateOverdueStatusAndFines($conn, $user_id);


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
          b.title,
          b.cover_path,
          br.university_id,
          br.branch_name,
          l.loan_id,
          l.branch_id,
          l.loan_date,
          l.due_date,
          l.return_date,
          l.status,
          l.renewed
      FROM 
          loans l
      JOIN 
          books b ON l.book_id = b.book_id
      JOIN 
          branches br ON l.branch_id = br.branch_id
      WHERE 
          l.user_id = ?
    ";
  // Initialize parameters array
  $params = [$user_id];
  $types = "i";

  $status_filter = isset($_GET['status']) ? $_GET['status'] : [];
  $branch_filter = isset($_GET['branch_id']) ? $_GET['branch_id'] : [];

  // Check if filters are applied and append to query
  if (!empty($status_filter)) {
    $status_placeholders = implode(',', array_fill(0, count($status_filter), '?'));
    $get_borrowed_books_query .= " AND l.status IN ($status_placeholders)";
    $params = array_merge($params, $status_filter); // Merge the status filter into params
    $types .= str_repeat('s', count($status_filter)); // Assuming status is a string
  }

  if (!empty($branch_filter)) {
    $branch_placeholders = implode(',', array_fill(0, count($branch_filter), '?'));
    $get_borrowed_books_query .= " AND l.branch_id IN ($branch_placeholders)";
    $params = array_merge($params, $branch_filter); // Merge the branch filter into params
    $types .= str_repeat('i', count($branch_filter)); // Assuming branch_id is an integer
  }

  // Add sorting
  $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : '';
  if (!empty($sort_by)) {
    $get_borrowed_books_query .= " ORDER BY l.$sort_by";
  }

  // Prepare the statement
  $stmt = $conn->prepare($get_borrowed_books_query);
  if ($stmt === false) {
      die("Error preparing statement: " . $conn->error);
  }
  $stmt->bind_param($types, ...$params);
  $stmt->execute();
  $borrowed_books_result = $stmt->get_result();

  $borrowed_books = [];
  if ($borrowed_books_result->num_rows > 0) {
    while ($book_row = $borrowed_books_result->fetch_assoc()) {
        $borrowed_books[] = $book_row;
    }
  } else {
    $no_loan_message = "No borrowed books to display.";
  }

  $stmt->close();


  // Get reserved books---------------------------------
  $get_reserved_books_query = "
      SELECT 
          b.book_id,
          b.title,
          b.cover_path,
          br.university_id,
          br.branch_name,
          r.reservation_id,
          r.branch_id,
          r.reservation_date,
          r.status
      FROM 
          reservations r
      JOIN 
          books b ON r.book_id = b.book_id
      JOIN 
          branches br ON r.branch_id = br.branch_id
      WHERE 
          r.user_id = ?
    ";

  // Initialize parameters array
  $params2 = [$user_id];
  $types2 = "i";

  $reserved_status_filter = isset($_GET['reserved_status']) ? $_GET['reserved_status'] : [];
  $reserved_branch_filter = isset($_GET['reserved_branch_id']) ? $_GET['reserved_branch_id'] : [];

  // Check if filters are applied and append to query
  if (!empty($reserved_status_filter)) {
    $reserved_status_placeholders = implode(',', array_fill(0, count($reserved_status_filter), '?'));
    $get_reserved_books_query .= " AND r.status IN ($reserved_status_placeholders)";
    $params2 = array_merge($params2, $reserved_status_filter); // Merge the status filter into params
    $types2 .= str_repeat('s', count($reserved_status_filter)); // Assuming status is a string
  }

  if (!empty($reserved_branch_filter)) {
    $reserved_branch_placeholders = implode(',', array_fill(0, count($reserved_branch_filter), '?'));
    $get_reserved_books_query .= " AND r.branch_id IN ($reserved_branch_placeholders)";
    $params2 = array_merge($params2, $reserved_branch_filter); // Merge the branch filter into params
    $types2 .= str_repeat('i', count($reserved_branch_filter)); // Assuming branch_id is an integer
  }

  // Add sorting
  $reserved_sort_by = isset($_GET['reserved_sort_by']) ? $_GET['reserved_sort_by'] : '';
  if (!empty($reserved_sort_by)) {
    $get_reserved_books_query .= " ORDER BY r.$reserved_sort_by";
  }

  // Prepare the statement
  $stmt = $conn->prepare($get_reserved_books_query);
  if ($stmt === false) {
      die("Error preparing statement: " . $conn->error);
  }
  $stmt->bind_param($types2, ...$params2);
  $stmt->execute();
  $reserved_books_result = $stmt->get_result();

  $reserved_books = [];
  if ($reserved_books_result->num_rows > 0) {
    while ($book_row = $reserved_books_result->fetch_assoc()) {
        $reserved_books[] = $book_row;
    }
  } else {
    $no_reserve_message = "No reserved books to display.";
  }

  $stmt->close();


  // Return borrowed books-------------------------------------------
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return_book'])) {
      // Get form data
      $book_id = $_POST['book_id'];
      $user_id = $_POST['user_id'];
      $branch_id = $_POST['branch_id'];
      $loan_id = $_POST['loan_id'];

      // Prepare the SQL to update the loan status
      $updateLoanStatusQuery = "
        UPDATE loans 
        SET 
          status = 'returned',
          return_date = CURDATE()
        WHERE loan_id = ?";
      $stmt = $conn->prepare($updateLoanStatusQuery);
      $stmt->bind_param("i", $loan_id);
      $stmt->execute();

      // Check if the update was successful
      if ($stmt->affected_rows > 0) {
          // Increase the available copies of the book at this branch
          $updateAvailableCopiesQuery = "
              INSERT INTO book_availability (book_id, branch_id, available_copies)
              VALUES (?, ?, 1)
              ON DUPLICATE KEY UPDATE 
                  available_copies = available_copies + 1;";
          $stmt = $conn->prepare($updateAvailableCopiesQuery);
          $stmt->bind_param("ii", $book_id, $branch_id);
          $stmt->execute();
      }

      $stmt->close();

      // Redirect back to the same page with the borrowed books tab active
      header("Location: my-shelf.php?active_tab=borrowed");
      exit();
  }

  // Renew borrowed books-------------------------------------------
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['renew_book'])) {
    // Get form data
    $loan_id = $_POST['loan_id'];

    // Prepare the SQL to update the loan status
    $updateLoanStatusQuery = "
      UPDATE loans 
      SET 
        due_date = DATE_ADD(due_date, INTERVAL 14 DAY),
        renewed = true
      WHERE loan_id = ?";
    $stmt = $conn->prepare($updateLoanStatusQuery);
    $stmt->bind_param("i", $loan_id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the same page with the borrowed books tab active
    header("Location: my-shelf.php?active_tab=borrowed");
    exit();
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
        <!-- ----------------------FAVOURITE BOOKS-------------------------- -->
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

        <!-- ----------------------BORROWED BOOKS-------------------------- -->
        <div id="borrowed-books" class="shelf-section" style="display: none">
          <h2>Your Borrowed Books</h2>

          <!-- Filter button & form -->
          <button id="filter-button" onclick="toggleFilterForm('filter-loan-form')" class="filter-button">
          <img src="img/ui/filter.png"/>
            Filter
          </button>
          <form 
            id="filter-loan-form"
            class="advanced-search-form filter-form"
            method="GET" 
            action="<?php echo $_SERVER['PHP_SELF']; ?>"
          >
              <input type="hidden" name="active_tab" id="active-tab-input" value="borrowed">
              <!-- Status Filter -->
              <div class="checkbox-dropdown">
                <label>Status</label>
                <div
                  class="checkbox-dropdown-toggle filter-toggle"
                  onclick="toggleFilterForm('status-dropdown')"
                >
                  <span>Select Status</span>
                  <img
                    src="img/ui/drop-down-icon.svg"
                    alt="Arrow Down Icon"
                    class="dropdown-icon"
                  />
                </div>
                <div class="checkbox-dropdown-content filter-dropdown" id="status-dropdown">
                  <label>
                    <input type="checkbox" name="status[]" value="active" 
                    />
                    Active
                  </label>
                  <label>
                    <input type="checkbox" name="status[]" value="overdue" 
                    />
                    Overdue
                  </label>
                  <label>
                    <input type="checkbox" name="status[]" value="returned" 
                    />
                    Returned
                  </label>
                </div>
              </div>

              <!-- Branch Filter -->
              <div class="checkbox-dropdown">
                <label>Branches</label>
                <div
                  class="checkbox-dropdown-toggle filter-toggle"
                  onclick="toggleFilterForm('branch-dropdown')"
                >
                  <span>Select Branches</span>
                  <img
                    src="img/ui/drop-down-icon.svg"
                    alt="Arrow Down Icon"
                    class="dropdown-icon"
                  />
                </div>
                <div class="checkbox-dropdown-content" id="branch-dropdown" style="width: 400px;">
                  <?php foreach ($branches as $branch) { ?>
                    <label>
                      <input type="checkbox" name="branch_id[]" value="<?php echo htmlspecialchars($branch['branch_id']); ?>" 
                      />
                      <?php echo htmlspecialchars($branch['university_id']); ?> - <?php echo htmlspecialchars($branch['branch_name']); ?>
                    </label>
                  <?php } ?>
                </div>
              </div>

              <!-- Sort By Filter -->
              <div>
              <label for="sort-by">Sort By:</label>
              <div class="sort-dropdown">
                <select name="sort_by" id="sort-by" class="sort-dropdown-select" style="color: black;">
                    <option value="">Default</option>
                    <option value="due_date ASC">Due Date (ASC)</option>
                    <option value="due_date DESC">Due Date (DESC)</option>
                    <option value="loan_date ASC">Loan Date (ASC)</option>
                    <option value="loan_date DESC">Loan Date (DESC)</option>
                    <option value="return_date ASC">Return Date (ASC)</option>
                    <option value="return_date DESC">Return Date (DESC)</option>
                </select>
                <img src="img/ui/drop-down-icon.svg" alt="Dropdown Icon" />
              </div>
              </div>

              <button type="submit" class="submit-filter-button">Apply Filters</button>
          </form>

          <!-- Display table -->
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
                        action="my-shelf.php"
                        method="POST"
                        onsubmit="return confirm('Are you sure you want to return this book?');"
                      >
                      <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>" />
                      <input type="hidden" name="branch_id" value="<?php echo $book['branch_id']; ?>" />
                      <input type="hidden" name="loan_id" value="<?php echo $book['loan_id']; ?>" />
                      <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" />
                      <input type="hidden" name="return_book" value="1" />
                        <button
                          type="submit"
                          class="shelf-action-button return"
                          onclick="event.stopPropagation();"
                          <?php if ($status === 'returned') { ?>
                            disabled
                            style="background-color: #D3D3D3; cursor: not-allowed; color: black; border: none;"
                          <?php } ?>
                        >
                          Return
                        </button>
                      </form>
                      <form
                        action="my-shelf.php"
                        method="POST"
                        onsubmit="return confirm('You may renew this book only once, extending the due date by 14 days. If you would like to keep it longer after that, please return it first, then borrow it again.');"
                      >
                      <input type="hidden" name="loan_id" value="<?php echo $book['loan_id']; ?>" />
                      <input type="hidden" name="renew_book" value="1" />
                        <!-- Replace with actual user ID -->
                        <button
                          type="submit"
                          class="shelf-action-button acknowledge"
                          onclick="event.stopPropagation();"
                          <?php if ($status === 'returned' || $status === 'overdue' || $book['renewed'] == 1) { ?>
                            disabled
                            style="background-color: #D3D3D3; cursor: not-allowed; color: black; border: none;"
                          <?php } ?>
                        >
                          Renew
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


        <!-- ----------------------RESERVED BOOKS-------------------------- -->
        <div id="reserved-books" class="shelf-section" style="display: none">
          <h2>Your Reserved Books</h2>
          <!-- Filter button & form -->
          <button id="filter-button" onclick="toggleFilterForm('filter-reserved-form')" class="filter-button">
          <img src="img/ui/filter.png"/>
            Filter
          </button>
          <form 
            id="filter-reserved-form"
            class="advanced-search-form filter-form"
            method="GET" 
            action="<?php echo $_SERVER['PHP_SELF']; ?>"
          >
              <input type="hidden" name="active_tab" id="active-tab-input" value="reserved">
              <!-- Status Filter -->
              <div class="checkbox-dropdown">
                <label>Status</label>
                <div
                  class="checkbox-dropdown-toggle filter-toggle"
                  onclick="toggleFilterForm('reserved-status-dropdown')"
                >
                  <span>Select Status</span>
                  <img
                    src="img/ui/drop-down-icon.svg"
                    alt="Arrow Down Icon"
                    class="dropdown-icon"
                  />
                </div>
                <div class="checkbox-dropdown-content filter-dropdown" id="reserved-status-dropdown">
                  <label>
                    <input type="checkbox" name="reserved_status[]" value="pending" 
                    />
                    Pending
                  </label>
                  <label>
                    <input type="checkbox" name="reserved_status[]" value="fulfilled" 
                    />
                    Fulfilled
                  </label>
                  <label>
                    <input type="checkbox" name="reserved_status[]" value="cancelled" 
                    />
                    Cancelled
                  </label>
                </div>
              </div>

              <!-- Branch Filter -->
              <div class="checkbox-dropdown">
                <label>Branches</label>
                <div
                  class="checkbox-dropdown-toggle filter-toggle"
                  onclick="toggleFilterForm('reserved-branch-dropdown')"
                >
                  <span>Select Branches</span>
                  <img
                    src="img/ui/drop-down-icon.svg"
                    alt="Arrow Down Icon"
                    class="dropdown-icon"
                  />
                </div>
                <div class="checkbox-dropdown-content" id="reserved-branch-dropdown" style="width: 400px;">
                  <?php foreach ($branches as $branch) { ?>
                    <label>
                      <input type="checkbox" name="reserved_branch_id[]" value="<?php echo htmlspecialchars($branch['branch_id']); ?>" 
                      />
                      <?php echo htmlspecialchars($branch['university_id']); ?> - <?php echo htmlspecialchars($branch['branch_name']); ?>
                    </label>
                  <?php } ?>
                </div>
              </div>

              <!-- Sort By Filter -->
              <div>
              <label for="sort-by">Sort By:</label>
              <div class="sort-dropdown">
                <select name="reserved_sort_by" id="reserved-sort-by" class="sort-dropdown-select" style="color: black;">
                    <option value="">Default</option>
                    <option value="reservation_date ASC">Reservation Date (ASC)</option>
                    <option value="reservation_date DESC">Reservation Date (DESC)</option>
                </select>
                <img src="img/ui/drop-down-icon.svg" alt="Dropdown Icon" />
              </div>
              </div>

              <button type="submit" class="submit-filter-button">Apply Filters</button>
          </form>
          <!-- Display table -->
          <?php if (!empty($reserved_books)) { ?>
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
              <?php foreach ($reserved_books as $book) { ?>
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
                    <td headers="reserved-on-col">
                      <?php echo !empty($book['reservation_date']) ? htmlspecialchars($book['reservation_date']) : ''; ?>
                    </td>
                    <td headers="avail-col">Not available</td>
                    <td headers="status-col">
                      <?php
                        $status = $book['status'];
                        $class = '';

                        // Determine the class based on the status
                        if ($status === 'fulfilled') {
                            $class = 'status-green';
                        } elseif ($status === 'pending') {
                            $class = 'status-yellow';
                        } elseif ($status === 'cancelled') {
                            $class = 'status-red';
                        }
                      ?>
                      <span class="<?php echo $class; ?>"><?php echo ucfirst(htmlspecialchars($status)); ?></span>
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
                    echo htmlspecialchars($no_reserve_message);
                }
                ?>
            </p>
          <?php } ?>
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

      document.addEventListener("DOMContentLoaded", function () {
          const urlParams = new URLSearchParams(window.location.search);
          const activeTab = urlParams.get("active_tab") || "favourite"; // Default to "favourite"

          // Open the corresponding tab
          const tabToOpen = document.getElementById(`${activeTab}-tab`);
          if (tabToOpen) {
              openTab(tabToOpen, `${activeTab}-books`);
          }
      });
    </script>

    <!-- Redirect to Book details page -->
    <script>
      function redirectToBookDetails(bookId) {
        window.open("book-details.php?book_id=" + bookId, "_blank");
      }
    </script>

    <!-- Toggle filter form display --> 
    <script> 
        function toggleFilterForm(formId) {
            var form = document.getElementById(formId);
            if (form.style.display === "block") {
                form.style.display = "none";
            } else {
                form.style.display = "block";
            }
        }
    </script>
</html>
