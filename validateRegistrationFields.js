// Function to clear all fields on page load
function clearFieldsOnLoad() {
  // List of all input fields to clear on load
  const fields = [
    document.getElementById("register-name"),
    document.getElementById("register-email"),
    document.getElementById("register-phone"),
    document.getElementById("register-password"),
    document.getElementById("register-cf-password")
  ];

  // Clear each field and reset border color
  fields.forEach(field => {
    field.value = ''; // Reset value to empty string
    field.style.borderColor = ''; // Reset any border styling
  });
}

// Run clear fields function on page load
window.onload = clearFieldsOnLoad;

// Email validation function
function validateEmail(field) {
  const emailRegex = /^[\w\.-]+@e\.(ntu|nus|smu|sit|sutd|suss)\.edu\.sg$/;
  const isValid = emailRegex.test(field.value);
  displayValidationResult(field, isValid, "Please enter a valid email address from an approved institution.");
}

// Password validation function (example: minimum 8 characters, at least one number)
function validatePassword(field) {
  const passwordRegex = /^(?=.*\d).{8,}$/;
  const isValid = passwordRegex.test(field.value);
  displayValidationResult(field, isValid, "Password must be at least 8 characters long and contain a number.");
}

// Name validation function (example: only letters, spaces, and hyphens)
function validateName(field) {
  const nameRegex = /^[A-Za-z\s\-]+$/;
  const isValid = nameRegex.test(field.value);
  displayValidationResult(field, isValid, "Name should contain only letters, spaces, and hyphens.");
}

// Phone number validation function (example: Singaporean phone number format)
function validatePhoneNumber(field) {
  const phoneRegex = /^\d{8}$/;
  const isValid = phoneRegex.test(field.value);
  displayValidationResult(field, isValid, "Please enter a valid Singaporean phone number.");
}

// Identical password validation function
function validateIdenticalPasswords() {
  const passwordField = document.getElementById("register-password");
  const confirmPasswordField = document.getElementById("register-cf-password");
  const submitButton = document.getElementById("register-button");

  if (passwordField.value && confirmPasswordField.value && passwordField.value !== confirmPasswordField.value) {
    passwordField.style.borderColor = 'red';
    confirmPasswordField.style.borderColor = 'red';
    submitButton.disabled = true;
    submitButton.style.backgroundColor = 'grey';
    alert("Passwords do not match.");
  } else {
    passwordField.style.borderColor = '';
    confirmPasswordField.style.borderColor = '';
    updateButtonState();
  }
}

// Utility function to display validation results
function displayValidationResult(field, isValid, errorMessage) {
  const submitButton = document.getElementById("register-button");

  if (!isValid) {
    field.style.borderColor = 'red';
    submitButton.disabled = true;
    submitButton.style.backgroundColor = 'grey';
    alert(errorMessage);
  } else {
    field.style.borderColor = '';
    updateButtonState();
  }
}

// Function to update the button state based on field validity
function updateButtonState() {
  const isFormValid = document.querySelectorAll('.register-input-field').length ===
    Array.from(document.querySelectorAll('.register-input-field')).filter(field => field.style.borderColor !== '#ef7c00').length;
  const submitButton = document.getElementById("register-button");

  submitButton.disabled = !isFormValid;
  submitButton.style.backgroundColor = isFormValid ? '#ef7c00' : 'grey';
}
