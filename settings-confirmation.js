function confirmUpdateCategories() {
  if (confirm("Are you sure you want to update your favorite categories?")) {
    // Redirect to logout_member.php if the user clicked "Yes"
    window.location.href = "update-fav-categories.php";
  }
  // Else do nothing
}

function confirmUpdateInfo() {
  if (confirm("Are you sure you want to update your personal particulars?")) {
    // Redirect to update-info.php if the user clicked "Yes"
    window.location.href = "update-info.php";
  }
  // Else do nothing
}

function confirmUpdatePassword() {
  if (confirm("Are you sure you want to update your password?")) {
    // Redirect to update-password.php if the user clicked "Yes"
    window.location.href = "update-password.php";
  }
  // Else do nothing
}