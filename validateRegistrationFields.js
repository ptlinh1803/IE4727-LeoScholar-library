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

  // Disable the button by default
  const submitButton = document.getElementById("register-button");
  submitButton.disabled = true;
  submitButton.style.backgroundColor = 'grey';
}

// Run clear fields function on page load
window.onload = clearFieldsOnLoad;

// Name check function
function isNameValid(field) {
  const nameRegex = /^[A-Za-z\s\-]+$/;
  return nameRegex.test(field.value);
}

// Name validate function
function validateName(field) {
  displayValidationResult(field, isNameValid(field), "Name should contain only letters, spaces, and hyphens.");
}

// Email check function
function isEmailValid(field) {
  const emailRegex = /^[\w\.-]+@e\.(ntu|nus|smu|sit|sutd|suss)\.edu\.sg$/;
  return emailRegex.test(field.value);
}

// Email validate function
function validateEmail(field) {
  displayValidationResult(field, isEmailValid(field), "Please enter a valid email address from an approved institution.");
}

// Phone check function
function isPhoneValid(field) {
  const phoneRegex = /^\d{8}$/;
  return phoneRegex.test(field.value);
}

// Phone validate function
function validatePhone(field) {
  displayValidationResult(field, isPhoneValid(field), "Please enter a valid Singaporean phone number.");
}

// Password check function
function isPasswordValid(field) {
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>-_])[\w!@#$%^&*(),.?":{}|<>-_]{8,}$/;
  return passwordRegex.test(field.value);
}

// Password validate function
function validatePassword(field) {
  displayValidationResult(field, isPasswordValid(field), "Passwords must have at least 8 characters and 1 uppercase letter, 1 lowercase letter, 1 number & 1 special character");
  validateIdenticalPasswords();
}

// Identical password check function
function arePasswordsIdentical() {
  const passwordField = document.getElementById("register-password");
  const confirmPasswordField = document.getElementById("register-cf-password");
  return (passwordField.value && confirmPasswordField.value && passwordField.value == confirmPasswordField.value);
}

// Identical password validation function
function validateIdenticalPasswords() {
  const passwordField = document.getElementById("register-password");
  const confirmPasswordField = document.getElementById("register-cf-password");
  const submitButton = document.getElementById("register-button");

  if (!arePasswordsIdentical()) {
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

//Function to update the button state based on field validity
function updateButtonState() {
  // Get all the fields for validations
  const nameField = document.getElementById("register-name");
  const emailField = document.getElementById("register-email");
  const phoneField = document.getElementById("register-phone");
  const passwordField = document.getElementById("register-password");
  const cfPasswordField = document.getElementById("register-cf-password");

  // Combine all the conditions
  const isFormValid = isNameValid(nameField) && isEmailValid(emailField) && isPhoneValid(phoneField) &&
    isPasswordValid(passwordField) && isPasswordValid(cfPasswordField) && arePasswordsIdentical();
  const submitButton = document.getElementById("register-button");

  submitButton.disabled = !isFormValid;
  submitButton.style.backgroundColor = isFormValid ? '#ef7c00' : 'grey';
}