function validateAvailableCopies(input) {
  const submitButton = input.closest('tr').querySelector('button[type="submit"]');
  const availableCopies = input.value;

  if (!/^\d+$/.test(availableCopies) || parseInt(availableCopies) < 0) {
    alert("Available copies must be a non-negative integer.");
    submitButton.disabled = true;
    submitButton.style.backgroundColor = 'grey';
  } else {
    // validateShelf(input.closest('tr').querySelector('input[name="shelf"]'));
    submitButton.disabled = false;
    submitButton.style.backgroundColor = '#d71440';
  }
}

// No need to validate shelf
// function validateShelf(input) {
//   const submitButton = input.closest('tr').querySelector('button[type="submit"]');
//   const shelf = input.value;
//   const shelfRegex = /^[A-Z]{2} [A-Z]\d+\-\d+$/;

//   if (shelf !== '' && !shelfRegex.test(shelf)) {
//     alert("Shelf format is invalid. Use format: AA B1-2 or leave empty.");
//     submitButton.disabled = true;
//     submitButton.style.backgroundColor = 'grey';
//   } else {
//     // Enable the button if available_copies is valid
//     const availableCopiesInput = input.closest('tr').querySelector('input[name="available_copies"]');
//     if (/^\d+$/.test(availableCopiesInput.value) && parseInt(availableCopiesInput.value) >= 0) {
//       submitButton.disabled = false;
//       submitButton.style.backgroundColor = '#d71440';
//     }
//   }
// }
