<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Check if the user is already logged in
if (isset($_SESSION['user_id']) || isset($_SESSION['librarian_id'])) {
    // Redirect to the appropriate homepage based on the session
    if (isset($_SESSION['user_id'])) {
        $_SESSION['message'] = 'You are already logged in!';
        header('Location: index.php');
        exit; // Always exit after header redirection
    } elseif (isset($_SESSION['librarian_id'])) {
        $_SESSION['message'] = 'You are already logged in!';
        header('Location: homepage-librarian.php');
        exit; // Always exit after header redirection
    }
}
?>

<!-- The rest of the form is pure HTML-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="login_and_register_style.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
  />
  <title>LeoScholar - Login</title>
</head>

<body>
  <div class="content-container">
    <!-- Left Column for Member Login -->
    <div id="login-left-column">
      <img src="img/ui/leoscholar-logo-transparent.png" alt="LeoScholar Logo" class="login-logo">
      <h3>Welcome Back!</h3>
      <h4>Sign in to continue to your Digital Library</h4>

      <form action="login_member.php" method="POST" id="member-form">
        <label for="member-email"><b>Email</b></label>
        <input type="email" id="member-email" name="email" class="login-input-field" placeholder="username@domain.sg"
          required>

        <label for="member-password"><b>Password</b></label>
        <div class="password-frame">
          <input type="password" id="member-password" name="password" class="login-input-field" placeholder="••••••••"
            required>
          <span class="password-visibility" onclick="togglePassword('member-password', this)">
            <i class="fas fa-eye-slash"></i>
          </span>
        </div>

        <div class="options">
        </div>

        <button type="submit" class="orange-button" name="submit">Login as a Member</button>

        <div class="options">
          <p>New Member? <a href="register.php"><u>Register Here</u></a></p>
          <p><a href="index.php">Continue as Guest</a></p> <!-- Put in <p> for identical block-level alignment-->
        </div>
      </form>
    </div>

    <!-- Vertical Divider -->
    <div id="vertical-divider"></div>

    <!-- Right Column for Librarian Login -->
    <div id="login-right-column">
      <img src="img/ui/leoscholar-logo-transparent.png" alt="LeoScholar Logo" class="login-logo">
      <h3>Welcome Back!</h3>
      <h4>Sign in to manage your university's Digital Library</h4>

      <form action="login_librarian.php" method="POST" id="librarian-form">
        <label for="librarian-email"><b>Email</b></label>
        <input type="email" id="librarian-email" name="email" class="login-input-field" placeholder="username@domain.sg"
          required>

        <label for="librarian-password"><b>Password</b></label>
        <div class="password-frame">
          <input type="password" id="librarian-password" name="password" class="login-input-field"
            placeholder="••••••••" required>
          <span class="password-visibility" onclick="togglePassword('librarian-password', this)">
            <i class="fas fa-eye-slash"></i>
          </span>
        </div>

        <div class="options">
        </div>

        <button type="submit" class="blue-button" name="submit">Login as a Librarian</button>
      </form>
      <div class="options">
        <p>New Librarian? <a href="#"><u>Contact your university to create your account</u></a></p>
      </div>
    </div>
  </div>

  <script src="toggle_password.js"></script>
</body>

</html>