// This one is for member, but I don't want to change the function's name in other scripts
function confirmLogout() {
  if (confirm("Are you sure you want to log out?")) {
    // Redirect to logout script if the user clicked "Yes"
    window.location.href = "logout_member.php";
  }
  // Else do nothing
}

function confirmLogoutLibrarian() {
  if (confirm("Are you sure you want to log out?")) {
    // Redirect to logout script if the user clicked "Yes"
    window.location.href = "logout_librarian.php";
  }
  // Else do nothing
}