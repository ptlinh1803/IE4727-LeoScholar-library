// validatePasswords.js

function checkPasswordsMatch() {
  // Get the password fields by their IDs
  const passwordField = document.getElementById("register-password");
  const confirmPasswordField = document.getElementById("register-cf-password");

  // Check if both fields are present before running the validation
  if (passwordField && confirmPasswordField) {
    if (passwordField.value !== confirmPasswordField.value) {
      confirmPasswordField.style.borderColor = 'red';
      alert("Passwords do not match.");
    } else {
      confirmPasswordField.style.borderColor = '';
    }
  }
}

// Run the function when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', checkPasswordsMatch())

// Prevent form submission if passwords 
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', (event) => {
      // Prevent form submission if passwords do not match
      if (!checkPasswordsMatch()) {
        event.preventDefault();
        alert("Please fill in all the fields correctly first.");
      }
    });
  }
});
