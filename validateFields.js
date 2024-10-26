// This script provides functions to validate the fields in login.html and register.html
// The script consist of 3 parts:
// 1. Defining all the validation functions
// 2. Creating a function that takes in the type of the element at hand and apply the validation function accordingly
// 3. Create a function that iterates the process of part 2 through the entire HTML


// 1. Validation Functions
// Email validation function
function validateEmail(value) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(value);
}

// Password validation function (example: minimum 8 characters, at least one number)
function validatePassword(value) {
  const passwordRegex = /^(?=.*\d).{8,}$/;
  return passwordRegex.test(value);
}

// Name validation function (example: only letters, spaces and hyphens)
function validateName(value) {
  const nameRegex = /^[A-Za-z\s\-]+$/;
  return nameRegex.test(value);
}

// Phone number validation function (example: US phone number format)
function validatePhoneNumber(value) {
  const phoneRegex = /^\+?\d{10,15}$/; // Allows optional +, followed by 10 to 15 digits
  return phoneRegex.test(value);
}

// 2. Detect and apply the right validation function
function validateField(field) {
  const value = field.value;
  let isValid = false;
  let errorMessage = '';

  switch (field.name) {
    case 'email':
      isValid = validateEmail(value);
      errorMessage = 'Please enter a valid email address.';
      break;
    case 'password':
      isValid = validatePassword(value);
      errorMessage = 'Password must be at least 8 characters long and contain a number.';
      break;
    case 'name':
      isValid = validateName(value);
      errorMessage = 'Name should contain only letters, spaces and hyphens.';
      break;
    case 'phone':
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

// 3. Iterate the function in part 2 through the entire page
function attachValidationToAllFields() {
  const inputFields = document.querySelectorAll('input[type="email"], input[type="password"], input[name="name"], input[name="phone"]');

  inputFields.forEach((field) => {
    field.addEventListener('change', function () {
      validateField(this);
    });
  });
}

// Run the function when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', attachValidationToAllFields);
