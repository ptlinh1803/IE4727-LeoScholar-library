<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Check if the user is already logged in
if (isset($_SESSION['user_id']) || isset($_SESSION['librarian_id'])) {
    // Redirect to the appropriate homepage based on the session
    if (isset($_SESSION['user_id'])) {
        $_SESSION['message'] = 'Please log out first to register a new account!';
        header('Location: index.php');
        exit; // Always exit after header redirection
    } elseif (isset($_SESSION['librarian_id'])) {
        $_SESSION['message'] = 'Please log out first to register a new account!';
        header('Location: homepage-librarian.php');
        exit; // Always exit after header redirection
    }
}
?>

<!-- The rest of the form is pure HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registration - LeoScholar</title>
  <link rel="stylesheet" href="login_and_register_style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <script src="validateRegistrationFields.js" defer></script>
</head>

<body>
  <div class="content-container">
    <!-- Left Div with Background Image and Logo -->
    <div id="register-left-column"></div>

    <!-- Right Div with Registration Form -->
    <div id="register-right-column">
      <h1>Membership Registration</h1>
      <h5>For both Staffs & Students</h5>

      <form action="register-form.php" method="POST">
        <!-- Name Input Field -->
        <label for="name"><b>Name</b><br /></label>
        <input type="text" id="register-name" name="name" placeholder="E.g. Pham Thuy Linh" class="register-input-field"
          onchange="validateName(this)" required />

        <!-- University Email Input Field -->
        <label for="email"><b>University Email Address</b><br /></label>
        <input type="email" id="register-email" name="email" placeholder="username@domain.sg"
          class="register-input-field" onchange="validateEmail(this)" required />

        <!-- Phone Number Input Field -->
        <label for="phone"><b>Phone Number</b><br /></label>
        <input type="text" id="register-phone" name="phone" placeholder="12345678" class="register-input-field"
          onchange="validatePhone(this)" required />

        <!-- Password Input Field -->
        <label for="register-password"><b>Password</b><br /></label>
        <div class="password-frame">
          <input type="password" id="register-password" name="password" placeholder="••••••••"
            class="register-input-field" onchange="validatePassword(this)" required />
          <span class="password-visibility" onclick="togglePassword('register-password', this)">
            <i class="fas fa-eye-slash"></i>
          </span>
        </div>

        <!-- Confirm Password Input Field -->
        <label for="register-cf-password"><b>Confirm Password</b><br /></label>
        <div class="password-frame">
          <input type="password" id="register-cf-password" name="password" placeholder="••••••••"
            class="register-input-field" onchange="validatePassword(this)" required />
          <span class="password-visibility" onclick="togglePassword('register-cf-password', this)">
            <i class="fas fa-eye-slash"></i>
          </span>
        </div>

        <!-- Register Button -->
        <button type="submit" class="orange-button" id="register-button" name="submit" disabled>
          Register
        </button>
      </form>

      <!-- Options Section -->
      <div id="register-options">
        <p>
          Already a User? <u><a href="login.php">Login now</a></u>
        </p>
        <p><a href="index.php">Use as Guest</a></p>
      </div>
    </div>
  </div>

  <!-- Optional JavaScript for Password Visibility Toggle -->
  <script src="toggle_password.js"></script>
</body>

</html>