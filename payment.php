<?php
include 'db-connect.php';
include 'update-loan-fine-status.php';
session_start();

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

// If not log in
if (!isset($_SESSION['user_id'])) {
  $message = "Hello, Guest! Please register or log in to see your payments!";
} else {
  $user_id = $_SESSION['user_id'];
  // Update loans & fines for this user-------------------
  updateOverdueStatusAndFines($conn, $user_id);

  // Get pending payments ----------------------------
  $get_pending_payments = "
      SELECT 
        f.fine_id, 
        f.user_id, 
        f.loan_id, 
        l.book_id, 
        b.title, 
        b.cover_path, 
        l.branch_id, 
        br.university_id, 
        br.branch_name, 
        f.reason, 
        f.amount, 
        l.status,
        f.paid, 
        f.issued_date, 
        f.paid_date
      FROM fines f
      JOIN loans l
      ON f.loan_id = l.loan_id
      JOIN books b
      ON l.book_id = b.book_id
      JOIN branches br
      ON l.branch_id = br.branch_id
      WHERE f.user_id = ? AND f.paid = 0;
      ";

  $stmt = $conn->prepare($get_pending_payments);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $pending_payments_result = $stmt->get_result();

  $pending_payments = [];
  if ($pending_payments_result->num_rows > 0) {
    while ($row = $pending_payments_result->fetch_assoc()) {
        $pending_payments[] = $row;
    }
  } else {
    $no_pending_payments = "You don't have any pending payments.";
  }

  $stmt->close();

  // Get total amount of pending payments
  $total_pending_sql = "
    SELECT SUM(f.amount) AS total_amount 
    FROM fines f 
    WHERE f.user_id = ? AND f.paid = 0";
  $stmt = $conn->prepare($total_pending_sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $total_pending_result = $stmt->get_result();

  $totalPendingAmount = 0;
  if ($row = $total_pending_result->fetch_assoc()) {
      $totalPendingAmount = $row['total_amount'];
  }
  $stmt->close();

  // Get completed payments ----------------------------
  $get_completed_payments = "
      SELECT 
        f.fine_id, 
        f.user_id, 
        f.loan_id, 
        l.book_id, 
        b.title, 
        b.cover_path, 
        l.branch_id, 
        br.university_id, 
        br.branch_name, 
        f.reason, 
        f.amount, 
        f.paid, 
        f.issued_date, 
        f.paid_date
      FROM fines f
      JOIN loans l
      ON f.loan_id = l.loan_id
      JOIN books b
      ON l.book_id = b.book_id
      JOIN branches br
      ON l.branch_id = br.branch_id
      WHERE f.user_id = ? AND f.paid = 1;
      ";

  $stmt = $conn->prepare($get_completed_payments);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $completed_payments_result = $stmt->get_result();

  $completed_payments = [];
  if ($completed_payments_result->num_rows > 0) {
    while ($row = $completed_payments_result->fetch_assoc()) {
        $completed_payments[] = $row;
    }
  } else {
    $no_completed_payments = "You don't have any completed payments.";
  }

  $stmt->close();

  // Get total amount of completed payments
  $total_completed_sql = "
    SELECT SUM(f.amount) AS total_amount 
    FROM fines f 
    WHERE f.user_id = ? AND f.paid = 1";
  $stmt = $conn->prepare($total_completed_sql);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $total_completed_result = $stmt->get_result();

  $totalCompletedAmount = 0;
  if ($row = $total_completed_result->fetch_assoc()) {
      $totalCompletedAmount = $row['total_amount'];
  }
  $stmt->close();
}

// Update payment status -----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['chosen_payments']) && !empty($_POST['chosen_payments'])) {
  // Prepare the fine_id values for the query
  $fine_ids = $_POST['chosen_payments'];
    
  // Prepare the SQL statement
  $sql = "UPDATE fines SET paid = 1 WHERE fine_id IN (" . implode(',', array_fill(0, count($fine_ids), '?')) . ")";

  // Prepare the statement
  $stmt = $conn->prepare($sql);
  
  // Execute the statement with the chosen payment IDs
  $stmt->execute($fine_ids);
  
  // Optionally check how many rows were affected
  $affectedRows = $stmt->affected_rows;

  if ($affectedRows > 0) {
    $_SESSION['alert'] = "You have successfully paid " . $affectedRows . " fine(s).";
  } else {
    $_SESSION['alert'] = "No payment made.";
  }

  // Redirect back to the same page with the borrowed books tab active
  header("Location: payment.php");
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
    <link rel="stylesheet" href="payment-styles.css" />
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
        <a href="#">Contribute</a>
        <div class="dropdown">
          <a href="#" class="profile-link active-page">
            Profile
            <img src="img/ui/drop-down-icon.svg" alt="Arrow Down Icon" />
          </a>
          <div class="dropdown-content">
            <?php if (isset($_SESSION['user_id'])) { ?>
              <a href="#">Settings</a>
              <a href="payment.php" class="active-page">Payment</a>
              <a href="#">Logout</a>
            <?php } else { ?>
              <a href="login.php">Log in</a>
              <a href="register.php">Register</a>
            <?php } ?>
          </div>
        </div>
      </div>
    </nav>

    <!-- Header for Payment Page -->
    <header class="header-payment">
      <h1>Payment</h1>
    </header>

    <!-- Container -->
    <div class="container">
      <div class="left-side">
        <div class="shelf-subnav">
          <a href="#" id="pending-tab" class="tab-link active-tab">Pending</a>
          <a href="#" id="completed-tab" class="tab-link">Completed</a>
        </div>
      </div>
      <div class="right-side">
        <!-- ---------------PENDING PAYMENTS------------- -->
        <div id="pending-payment" class="shelf-section">
          <h2>Your Pending Payments</h2>
          <p>Note: You can only pay overdue fines for books that have already been returned.</p>

          <?php if (!empty($pending_payments)) { ?>
            <form 
              id="payment-form" 
              action="payment.php" 
              method="POST"
              onsubmit="return confirm('Are you sure you want to proceed with the selected payment(s)?');"
            >
              <table class="book-table">
                <thead>
                  <tr>
                    <th id="cover-col"></th>
                    <th id="title-col">Title</th>
                    <th id="branch-col">Branch</th>
                    <th id="reason-col">Reason</th>
                    <th id="amount-col">Amount</th>
                    <th id="status-col">Status</th>
                    <th id="action-col">Select</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($pending_payments as $pp) { ?>
                    <tr>
                      <td headers="cover-col">
                        <img
                          src="img/books/<?php echo $pp['cover_path']; ?>"
                          alt="Book Cover"
                          class="book-cover"
                        />
                      </td>
                      <td headers="title-col">
                        <?php echo htmlspecialchars($pp['title']); ?>
                      </td>
                      <td headers="branch-col">
                        <?php echo htmlspecialchars($pp['university_id']); ?> - <?php echo htmlspecialchars($pp['branch_name']); ?>
                      </td>
                      <td headers="reason-col">
                        <?php echo htmlspecialchars($pp['reason']); ?>
                      </td>
                      <td headers="amount-col">
                        $<?php echo htmlspecialchars($pp['amount']); ?>
                      </td>
                      <td headers="status-col">
                        <span class="status-yellow">Pending</span>
                      </td>
                      <td>
                        <?php if ($pp['status'] === 'returned') { ?>
                        <input
                          type="checkbox"
                          class="payment-checkbox"
                          name="chosen_payments[]"
                          value="<?php echo htmlspecialchars($pp['fine_id']); ?>"
                          data-amount="<?php echo htmlspecialchars($pp['amount']); ?>"
                          onchange="updateTotal();"
                        />
                        <?php } ?>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>

              <div class="display-amount">
                <p>
                  Total Amount to Pay: <span>$</span
                  ><span id="total-amount"><?php echo htmlspecialchars($totalPendingAmount); ?></span>
                </p>
                <p>Pay now: <span>$</span><span id="paynow-amount">0.00</span></p>
                <p>Number of Chosen Payments: <span id="chosen-count">0</span></p>
                <button
                  type="button"
                  id="select-all-button"
                  onclick="toggleSelectAll();"
                >
                  Select All
                </button>
                <button type="submit" class="pay-button" disabled>Pay</button>
              </div>
            </form>
          <?php } else { ?> 
              <div class="no-books-message">
                  <img src="img/ui/nothing-here.png" alt="Nothing here" />
              </div>
              <p style="text-align: center;">
                  <?php 
                  if (!isset($_SESSION['user_id'])) {
                      echo htmlspecialchars($message);
                  } else {
                      echo htmlspecialchars($no_pending_payments);
                  }
                  ?>
              </p>
          <?php } ?>
        </div>

        <!-- ---------------COMPLETED PAYMENTS------------- -->
        <div id="completed-payment" class="shelf-section" style="display: none">
          <h2>Your Completed Payments</h2>
          <?php if (!empty($completed_payments)) { ?>
            <table class="book-table">
              <thead>
                <tr>
                  <th id="cover-col"></th>
                  <th id="title-col">Title</th>
                  <th id="branch-col">Branch</th>
                  <th id="reason-col">Overdue</th>
                  <th id="amount-col">Amount</th>
                  <th id="status-col">Status</th>
                  <th id="paid-col">Paid date</th>
                  <th id="action-col"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($completed_payments as $cp) { ?>
                  <tr onclick="redirectToBookDetails(<?php echo $cp['book_id']; ?>)">
                    <td headers="cover-col">
                      <img
                        src="img/books/<?php echo $cp['cover_path']; ?>"
                        alt="Book Cover"
                        class="book-cover"
                      />
                    </td>
                    <td headers="title-col">
                      <?php echo htmlspecialchars($cp['title']); ?>
                    </td>
                    <td headers="branch-col">
                      <?php echo htmlspecialchars($cp['university_id']); ?> - <?php echo htmlspecialchars($cp['branch_name']); ?>
                    </td>
                    <td headers="reason-col">
                      <?php echo htmlspecialchars($cp['reason']); ?>
                    </td>
                    <td headers="amount-col">
                      $<?php echo htmlspecialchars($pp['amount']); ?>
                    </td>
                    <td headers="status-col">
                      <span class="status-green">Completed</span>
                    </td>
                    <td headers="paid-col">
                      <?php echo !empty($cp['paid_date']) ? htmlspecialchars($cp['paid_date']) : ''; ?>
                    </td>
                  </tr>
                <?php } ?> 
              </tbody>
            </table>
            <div class="display-amount">
              <p>Total: <span>$<?php echo htmlspecialchars($totalCompletedAmount); ?></span></p>
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
                      echo htmlspecialchars($no_completed_payments);
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
        .getElementById("pending-tab")
        .addEventListener("click", function (event) {
          event.preventDefault();
          openTab(this, "pending-payment");
        });

      // Show "completed-books" section on click
      document
        .getElementById("completed-tab")
        .addEventListener("click", function (event) {
          event.preventDefault();
          openTab(this, "completed-payment");
        });

      // Show the "favourite-books" section by default on page load
      document.addEventListener("DOMContentLoaded", function () {
        openTab(document.getElementById("pending-tab"), "pending-payment");
      });
    </script>

    <!-- Redirect to Book details page -->
    <script>
      function redirectToBookDetails(bookId) {
        window.open("book-details.php?book_id=" + bookId, "_blank");
      }
    </script>

    <!-- Display the total and chosen amount -->
    <script>
      // Function to update the total amounts and selected count
      function updateTotal() {
        const checkboxes = document.querySelectorAll(".payment-checkbox");
        // let totalAmount = 0; // Total for all payments
        let payNowAmount = 0; // Total for selected payments
        let chosenCount = 0; // Count of selected payments

        // Loop through each checkbox
        checkboxes.forEach((checkbox) => {
          const amount = parseFloat(checkbox.getAttribute("data-amount"));
          // totalAmount += amount; // Sum up all amounts for total display

          // Check if the checkbox is selected
          if (checkbox.checked) {
            payNowAmount += amount; // Add to pay now amount if checked
            chosenCount++; // Increment chosen count
          }
        });

        // Update the total amount and chosen count displayed
        // document.getElementById("total-amount").innerText =
        //   totalAmount.toFixed(2);
        document.getElementById("paynow-amount").innerText =
          payNowAmount.toFixed(2);
        document.getElementById("chosen-count").innerText = chosenCount;

        // Enable or disable the Pay button based on chosen payments
        const payButton = document.querySelector(".pay-button");
        payButton.disabled = chosenCount === 0;
      }

      // Function to toggle all checkboxes
      function toggleSelectAll() {
        const checkboxes = document.querySelectorAll(".payment-checkbox");
        const allChecked = Array.from(checkboxes).every(
          (checkbox) => checkbox.checked
        );

        // Set all checkboxes to the opposite of their current state
        checkboxes.forEach((checkbox) => {
          checkbox.checked = !allChecked;
          checkbox.dispatchEvent(new Event("change")); // Trigger change event to update totals
        });
      }

      // Initialize total amount at page load
      document.addEventListener("DOMContentLoaded", () => {
        updateTotal(); // Call updateTotal on page load to set initial totals
      });
    </script>
  </body>
</html>
