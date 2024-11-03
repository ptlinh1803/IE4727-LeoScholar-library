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

// Get all branches---------------------
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

// get list of categories--------------
$sql_list_categories = "SELECT DISTINCT(category) from books;";
$categories_results = $conn->query($sql_list_categories);

if ($categories_results) {
  $categories_list = [];
  while ($row = $categories_results->fetch_assoc()) {
      $categories_list[] = $row['category'];
  }
} else {
  // Handle query error
  echo "Error: " . $conn->error;
}

// If guest / librarian (non-member) ---------------------
if (!isset($_SESSION['user_id'])) {
  $guest_message = "Hello, Guest! Please register or log in to use this feature!";
} else {
  $user_id = $_SESSION['user_id'];

  // Get past contributions----------------------------
  $get_past_contribution_query = "
      SELECT d.*, br.university_id, br.branch_name
      FROM donations d
      JOIN branches br
      ON d.branch_id = br.branch_id
      WHERE d.user_id = ?
      ";

  $stmt = $conn->prepare($get_past_contribution_query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $past_contribution_result = $stmt->get_result();

  $past_contributions = [];
  if ($past_contribution_result->num_rows > 0) {
    while ($book_row = $past_contribution_result->fetch_assoc()) {
        $past_contributions[] = $book_row;
    }
  } else {
    $no_past_contribution_message = "Your don't have any past contributions.";
  }

  $stmt->close();

  // Upload new contribution----------------------------------
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // inputs
    $user_id = $_SESSION['user_id'];
    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publication_year = $_POST['publication_year'];
    $category = $_POST['category'];
    $branch_id = $_POST['branch_id'];
    $available_copies = $_POST['available_copies'];
    $book_description = !empty($_POST['book_description']) ? $_POST['book_description'] : '';
    $about_author = !empty($_POST['about_author']) ? $_POST['about_author'] : '';

    // Handle the cover image upload
    $cover_path = ''; // Default empty cover_path
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'img/books/'; // Directory to save the image

        // Get the original file extension
        $file_ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);

        // Generate a new file name with a timestamp
        $new_file_name = 'cover_' . time() . '.' . $file_ext;

        // Full path to save the uploaded file
        $target_file = $upload_dir . $new_file_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
            $cover_path = $new_file_name; // Save the new file name in the cover_path variable
        } else {
            echo "Error uploading the image.";
            exit;
        }
    }

    // Set default cover if no file was uploaded
    if ($cover_path === '') {
      $cover_path = 'default.png';
    }

    // Prepare the SQL query to insert data into the donations table
    $stmt = $conn->prepare("
      INSERT INTO donations 
      (user_id, isbn, title, author, description, about_author, publication_year, category, branch_id, available_copies, cover_path) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssisiis", $user_id, $isbn, $title, $author, $book_description, $about_author, $publication_year, $category, $branch_id, $available_copies, $cover_path);

    // Execute the query and check for success
    if ($stmt->execute()) {
        $_SESSION['alert'] = "Thank you for your donation! Our librarians will review and notify you of the decision.";
    } else {
      $_SESSION['alert'] = "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();

    // Redirect back to the same page with the borrowed books tab active
    header("Location: user-contribution.php");
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
    <link rel="stylesheet" href="user-contribution-styles.css" />
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar">
      <a href="index.php">
        <img src="img/ui/leoscholar-logo-transparent.png" alt="Logo" />
      </a>

      <!-- Hamburger Menu Icon -->
      <span class="menu-toggle">&#9776;</span>

      <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="search-page.php">Search</a>
        <a href="my-shelf.php">My Shelf</a>
        <a href="user-contribution.php" class="active-page">Contribute</a>
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

    <!-- Header for User Contribution Page -->
    <header class="header-contribution">
      <h1>Contribution</h1>
    </header>

    <!-- Container -->
    <div class="container">
      <div class="left-side">
        <div class="shelf-subnav">
          <a href="#" id="contribute-form-tab" class="tab-link active-tab"
            >Contribution Form</a
          >
          <a href="#" id="past-contribute-tab" class="tab-link"
            >Past Contributions</a
          >
        </div>
      </div>
      <div class="right-side">
        <!-- ---------------CONTRIBUTION FORM------------- -->
        <div id="contribute-form" class="shelf-section">
          <h2>Fill up Book Details</h2>
          <?php if (isset($_SESSION['user_id'])) { ?>
              <div class="form-container">
                <form
                  action="user-contribution.php"
                  method="POST"
                  enctype="multipart/form-data"
                >
                  <label for="title">Title *:</label><br />
                  <input type="text" id="title" name="title" required /><br /><br />

                  <label for="author">Author *:</label><br />
                  <input
                    type="text"
                    id="author"
                    name="author"
                    required
                  /><br /><br />

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
                    <option value="">Select a category</option>
                    <?php foreach ($categories_list as $category) { ?>
                      <option value="<?php echo htmlspecialchars($category); ?>">
                        <?php echo htmlspecialchars($category); ?>
                      </option>
                  <?php } ?>
                    
                  </select><br /><br />

                  <label for="branch">Branch *:</label><br />
                  <select id="branch" name="branch_id" required>
                    <option value="">Select branch</option>
                    <?php foreach ($branches as $branch) { ?>
                      <option value="<?php echo $branch['branch_id'] ?>">
                        <?php echo $branch['university_id'] ?> - <?php echo $branch['branch_name'] ?>
                      </option>
                    <?php } ?>
                  </select><br /><br />

                  <label for="available_copies">Available Copies *:</label><br />
                  <input
                    type="number"
                    id="available_copies"
                    name="available_copies"
                    required
                    min="0"
                  /><br /><br />

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

                  <label for="cover_image">Cover Image (image only):</label><br />
                  <input
                    type="file"
                    id="cover_image"
                    name="cover_image"
                    accept="image/*"
                  /><br /><br />

                  <input type="submit" value="Submit" />
                  <input type="reset" value="Reset" />
                </form>
              </div>
            <?php } else { ?>
              <div class="no-books-message">
                <img src="img/ui/nothing-here.png" alt="Nothing here" />
              </div>
              <p style="text-align: center;">
                <?php echo htmlspecialchars($guest_message); ?>
              <p>
            <?php } ?>
        </div>

        <!-- --------------- PAST CONTRIBUTIONS ------------- -->
        <div id="past-contribute" class="shelf-section" style="display: none">
          <h2>Your Past Contributions</h2>
          <!-- Display table -->
          <?php if (!empty($past_contributions)) { ?>
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
                </tr>
              </thead>
              <tbody>
                <!-- Example Row -->
                <?php foreach ($past_contributions as $pc) { ?>
                  <tr>
                    <td headers="cover-col">
                      <img
                        src="img/books/<?php echo isset($pc['cover_path']) && !empty($pc['cover_path']) ? $pc['cover_path'] : 'default.png'; ?>"
                        alt="Book Cover"
                        class="book-cover"
                      />
                    </td>
                    <td headers="title-col">
                      <?php echo htmlspecialchars($pc['title']); ?>
                    </td>
                    <td headers="author-col">
                      <?php echo htmlspecialchars($pc['author']); ?>
                    </td>
                    <td headers="branch-col">
                      <?php echo htmlspecialchars($pc['university_id']); ?> - <?php echo htmlspecialchars($pc['branch_name']); ?>
                    </td>
                    <td headers="copies-col"><?php echo $pc['available_copies']; ?></td>
                    <td headers="status-col">
                      <?php
                        $status = $pc['status'];
                        $class = '';

                        // Determine the class based on the status
                        if ($status === 'accepted') {
                            $class = 'status-green';
                        } elseif ($status === 'pending') {
                            $class = 'status-yellow';
                        } elseif ($status === 'rejected') {
                            $class = 'status-red';
                        }
                      ?>
                      <span class="<?php echo $class; ?>"><?php echo ucfirst(htmlspecialchars($status)); ?></span>
                    </td>
                    <td headers="time-col">
                      <?php echo date('Y-m-d', strtotime($pc['created_at'])); ?>
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
                    echo htmlspecialchars($guest_message);
                } else {
                    echo htmlspecialchars($no_past_contribution_message);
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

      // Show "pending-payment" section on click
      document
        .getElementById("contribute-form-tab")
        .addEventListener("click", function (event) {
          event.preventDefault();
          openTab(this, "contribute-form");
        });

      // Show "completed-books" section on click
      document
        .getElementById("past-contribute-tab")
        .addEventListener("click", function (event) {
          event.preventDefault();
          openTab(this, "past-contribute");
        });

      // Show the "favourite-books" section by default on page load
      document.addEventListener("DOMContentLoaded", function () {
        openTab(
          document.getElementById("contribute-form-tab"),
          "contribute-form"
        );
      });
    </script>

    <script>
      // Get the current year
      const currentYear = new Date().getFullYear();
      // Set the max attribute of the publication year input
      document.getElementById("publication_year").max = currentYear;
    </script>
  </body>
</html>
