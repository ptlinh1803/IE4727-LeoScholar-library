<?php
// Start the session
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<!-- The script borrows a lot of styles from search-styles.css-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LeoScholar</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
  />
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="search-styles.css" />
  <link rel="stylesheet" href="settings-styles.css" />
  <script src="logout_confirmation.js" defer></script>
  <script src="validateUserSettings.js" defer></script>
  <script src="settings-confirmation.js" defer></script>
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
      <a href="#">My Shelf</a>
      <a href="#">Contribute</a>
      <div class="dropdown">
        <a href="#" class="profile-link active-page">
          Profile
          <img src="img/ui/drop-down-icon.svg" alt="Arrow Down Icon" />
        </a>
        <div class="dropdown-content">
          <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="user-settings.php" class="active-page">Settings</a>
            <a href="#">Payment</a>
            <a href="#" onclick="confirmLogout()">Logout</a>
          <?php } else { ?>
            <a href="login.php">Log in</a>
              <a href="register.php">Register</a>
            <?php } ?>
        </div>
      </div>
    </div>
  </nav>

  <!-- Header for User Page -->
  <header class="header-settings">
    <h1 class="big-blue-h1">User Settings</h1>
  </header>

  <!-- Check if session's user_id is set, if not, display nothing-here.png-->
  <?php
// Check if the session ID is set
if (!isset($_SESSION['user_id'])) {
    // If not set, display the message
    echo     "<div style='display: flex; flex-direction: column; align-items: center; justify-content: center; height: 60vh;'>
    <img src='img/ui/nothing-here.png' alt='Nothing here' style='height: 300px;' />
    <h4> Please log in to view your user settings </h4>
</div>"
;
}
else { //Display the content of the page normally
?>
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
          <?php include "initial-user-info.php"?>
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
            <td>
            <div class="password-input-container">
              <input type="password" id="settings-current-password" name="settings-current-password" required>
            <span class="password-visibility" onclick="togglePassword('settings-current-password', this)">
              <i class="fas fa-eye-slash"></i>
            </span>
            </div>
          </td>
          </tr>
          <tr>
            <td>New Password</td>
            <td>
            <div class="password-input-container">
              <input type="password" id="settings-new-password" name="settings-new-password" required
                onchange="validateUpdatePassword()">
              <span class="password-visibility" onclick="togglePassword('settings-new-password', this)">
                <i class="fas fa-eye-slash"></i>
              </span>
            </div>
            </td>
          </tr>
          <tr>
            <td>Confirm New Password</td>
            <td>
            <div class="password-input-container">
              <input type="password" id="settings-confirm-new-password" name="settings-new-password" required
                onchange="validateUpdatePassword()">
                <span class="password-visibility" onclick="togglePassword('settings-confirm-new-password', this)">
                  <i class="fas fa-eye-slash"></i>
                </span>
              </div>
              </td>
          </tr>
        </table>
        <button type="submit" class="settings-submit-button" id="submit-update-password">Submit</button>
      </form>
    </div>
  </section>

  <?php } ?>
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
  <script src="toggle_password.js"></script>
  <?php include "display-session-message.php" ?>
</body>

</html>