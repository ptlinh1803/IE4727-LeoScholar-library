<!-- The script borrows a lot of styles from search-styles.css-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LeoScholar</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="search-styles.css" />
  <link rel="stylesheet" href="settings-styles.css" />
  <script src="validateUserSettings.js" defer></script>
  <script src="settings-confirmation.js" defer></script>
</head>

<body>
<!-- Display any message upon form submission-->
<?php
session_start();
if (isset($_SESSION['message'])) {
  echo "<script>alert('" . $_SESSION['message'] . "');</script>";
  unset($_SESSION['message']); // Clear the message after displaying it
}
?>
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
          <a href="user-settings.html" class="active-page">Settings</a>
          <a href="#">Payment</a>
          <a href="#">Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Header for User Page -->
  <header class="header-settings">
    <h1 class="big-blue-h1">User Settings</h1>
  </header>

  <!-- Select Options section -->
  <section class="settings-options">
    <h4>Select the user information that you want to see/update:</h4>
    <div class="button-container-container">
      <div class="button-container">
        <button class="warm-gradient-button category-button" onclick="showSection('fav-categories')">Favorite Book
          Categories</button>
        <button class="warm-gradient-button category-button" onclick="showSection('update-info')">Personal
          Information</button>
        <button class="warm-gradient-button category-button" onclick="showSection('update-password')">Change
          Password</button>
      </div>
    </div>
  </section>

  <!-- Show chosen user information section -->
  <section id="settings-section">
    <!-- Fav Category Section: Disply fav-categories as the default-->
    <div id="fav-categories" class="section" style="display: none">
      <h2 class="form-heading">Select Your Favorite Categories</h2>
      <form method="POST" action="update-fav-categories.php" id="update-category-form"
        class="category-list form-container" onsubmit="return confirmUpdateCategories()">
        <?php include "initial-fav-categories.php" ?>
        <button id="save-categories-button" class="settings-submit-button" onclick="">Submit</button>
      </form>
    </div>


    <!-- Update Personal Info Section -->
    <div id="update-info" class="section" style="display: none">
      <h2 class="form-heading">Update your personal particulars below:</h2>
      <form method="POST" action="update-info.php" id="update-info-form" class="form-container"
        onsubmit="return confirmUpdateInfo()">
        <table id="user-info-table" class="table-container">
          <tr>
            <th>Field</th>
            <th>Value</th>
          </tr>
          <tr>
            <td>Name</td>
            <td><input type="text" id="settings-name" name="settings-name" value="John Doe"
                onchange="validateUpdateInfo()"></td>
          </tr>
          <tr>
            <td>Email</td>
            <td>
              <p id="email">johndoe@example.com</p>
            </td>
          </tr>
          <tr>
            <td>Institution</td>
            <td>
              <p id="institution">Example University</p>
            </td>
          </tr>
          <tr>
            <td>Phone</td>
            <td><input type="text" id="settings-phone" name="settings-phone" value="123-456-7890"
                onchange="validateUpdateInfo()"></td>
          </tr>
        </table>
        <button type="submit" class="settings-submit-button" id="submit-update-info">Submit</button>
      </form>
    </div>

    <!-- Update Password Section -->
    <div id="update-password" class="section" style="display: none">
      <h2 class="form-heading">Change your password:</h2>
      <form action="update-password.php" method="POST" id="update-password-form" class="form-container"
        onsubmit="return confirmUpdatePassword()">
        <table id="change-password-table" class="table-container">
          <tr>
            <th>Field</th>
            <th>Value</th>
          </tr>
          <tr>
            <td>Current Password</td>
            <td><input type="password" id="settings-current-password" name="settings-current-password" required></td>
          </tr>
          <tr>
            <td>New Password</td>
            <td><input type="password" id="settings-new-password" name="settings-new-password" required
                onchange="validateUpdatePassword()"></td>
          </tr>
          <tr>
            <td>Confirm New Password</td>
            <td><input type="password" id="settings-confirm-new-password" name="settings-new-password" required
                onchange="validateUpdatePassword()"></td>
          </tr>
        </table>
        <button type="submit" class="settings-submit-button" id="submit-update-password">Submit</button>
      </form>
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

  <script src="display-settings.js"></script>

</body>

</html>