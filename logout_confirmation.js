function confirmLogout() {
  if (confirm("Are you sure you want to log out?")) {
    // Redirect to logout_member.php if the user clicked "Yes"
    window.location.href = "logout_member.php";
  }
  // Else do nothing
}