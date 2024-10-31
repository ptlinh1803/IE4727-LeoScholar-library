// The script contains the functions necessary to validate user input fields in user-settings.js

// 1. Validation function
// Name validation function (example: only letters, spaces and hyphens)
function validateName(value) {
  const nameRegex = /^[A-Za-z\s\-]+$/;
  return nameRegex.test(value);
}

// Phone number validation function (example: Singaporean phone number format)
function validatePhoneNumber(value) {
  const phoneRegex = /^\d{8}$/;
  return phoneRegex.test(value);
}

// Password validation function (example: minimum 8 characters, at least one number)
function validatePassword(value) {
  const passwordRegex = /^(?=.*\d).{8,}$/;
  return passwordRegex.test(value);
}

// Identical password validation function
function validateIdenticalPasswords() {
  // Get the password fields by their IDs
  const passwordField = document.getElementById("settings-new-password");
  const confirmPasswordField = document.getElementById("settings-confirm-new-password");

  // Check if both fields are present before running the validation
  if (passwordField.value && confirmPasswordField.value) {
    if (passwordField.value !== confirmPasswordField.value) {
      passwordField.style.borderColor = 'red';
      confirmPasswordField.style.borderColor = 'red';
      alert("New passwords do not match.");
      return false;
    } else {
      passwordField.style.borderColor = '';
      confirmPasswordField.style.borderColor = '';
      return true;
    }
  }
}

// 2. Detect & apply the right validation function, attach alert messages
function validateField(field) {
  const value = field.value;
  let isValid = false;
  let errorMessage = '';

  switch (field.name) {
    case 'settings-password':
      isValid = validatePassword(value);
      errorMessage = 'Passwords must be at least 8 characters long and contain a number.';
      break;
    case 'settings-name':
      isValid = validateName(value);
      errorMessage = 'Name should contain only letters, spaces and hyphens.';
      break;
    case 'settings-phone':
      isValid = validatePhoneNumber(value);
      errorMessage = 'Please enter a valid phone number.';
      break;
    default:
      return true; // If name is not recognized, assume valid
  }

  if (!isValid) {
    field.style.borderColor = 'red';
    alert(errorMessage);
  } else {
    field.style.borderColor = '';
  }

  return isValid;
}

// 3. Function to validate update-info
function validateUpdateInfo() {
  const name = document.getElementById("settings-name");
  const phone = document.getElementById("settings-phone");
  const submitInfoButton = document.getElementById("submit-update-info");

  // Set button state.disabled to the opposite of field validity
  submitInfoButton.disabled = !(validateField(name) && validateField(phone));
}

// 4. Function to validate update-password
function validateUpdatePassword() {
  const newPassword = document.getElementById("settings-new-password");
  const submitPasswordButton = document.getElementById("submit-update-password");

  // Set button state.disable to the opposite of field validity
  submitPasswordButton.disabled = !(validateField(newPassword) && validateIdenticalPasswords());

  // Check for whether the new password is the same as the old password will be applied at the backend instead
}