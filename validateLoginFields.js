// This script provides functions to validate the fields in and register.html
// The script consist of 3 parts:
// 1. Defining all the validation functions
// 2. Creating a function that takes in the type of the element at hand and apply the validation function accordingly
// 3. Defining a function to enable/disable login buttons (member & librarian)
// 4. Set up login validation for each login section (member & librarian)
// 5. addEventListener to run validation when the script is fully loaded
// 6. addEventListener to disable/enable submit button


// 1. Validation Functions
// Email validation function
function validateEmail(value) {
  const emailRegex = /^[\w\.-]+@e\.(ntu|nus|smu|sit|sutd|suss)\.edu\.sg$/;
  return emailRegex.test(value);
}

// Password validation function (example: minimum 8 characters, at least one number)
function validatePassword(value) {
  const passwordRegex = /^(?=.*\d).{8,}$/;
  return passwordRegex.test(value);
}

// 2. Detect and apply the right validation function
function validateField(field) {
  const value = field.value;
  let isValid = false;
  let errorMessage = '';

  switch (field.name) {
    case 'email':
      isValid = validateEmail(value);
      errorMessage = 'Please enter a valid email address from an approved institution.';
      break;
    case 'password':
      isValid = validatePassword(value);
      errorMessage = 'Password must be at least 8 characters long and contain a number.';
      break;
  }

  if (!isValid) {
    field.style.borderColor = 'red';
    alert(errorMessage);
  } else {
    field.style.borderColor = '';
  }

  return isValid;
}

// Enable/disable button based on field validation on each side
function updateButtonState(form) {
  const fields = form.querySelectorAll(`input[type="email"], input[type="password"]`);
  const loginButton = form.querySelector(`button`);
  const allFieldsValid = Array.from(fields).every(field => validateField(field));

  loginButton.disabled = !allFieldsValid;
}

// 4. Set up validation for each login section
function setupValidation(form) {
  const fields = form.querySelectorAll(`input[type="email"], input[type="password"]`);

  // Apply blur event listener to validate each field individually
  fields.forEach((field) => {
    field.addEventListener('blur', () => {
      updateButtonState(form);
    });
  });
}

// 5. Initialize validation and prevent submission on DOM load
document.addEventListener('DOMContentLoaded', () => {
  const memberForm = document.getElementById('member-form');
  const librarianForm = document.getElementById('librarian-form');

  if (memberForm) {
    setupValidation(memberForm);
    memberForm.addEventListener('submit', (event) => {
      if (memberForm.querySelector('button').disabled) {
        event.preventDefault();
        alert("Please fill in all the fields correctly for Member Login.");
      }
    });
  }

  if (librarianForm) {
    setupValidation(librarianForm);
    librarianForm.addEventListener('submit', (event) => {
      if (librarianForm.querySelector('button').disabled) {
        event.preventDefault();
        alert("Please fill in all the fields correctly for Librarian Login.");
      }
    });
  }
});