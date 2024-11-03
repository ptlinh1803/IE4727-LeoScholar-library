function togglePassword(id, icon) {
  const passwordField = document.getElementById(id);
  if (passwordField.type === "password") {
    passwordField.type = "text";
    icon.innerHTML = '<i class="fas fa-eye"></i>'; // Switch to open eye
  } else {
    passwordField.type = "password";
    icon.innerHTML = '<i class="fas fa-eye-slash"></i>'; // Switch to closed eye
  }
}
