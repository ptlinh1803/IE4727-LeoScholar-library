function showSection(sectionId) {
  const sections = document.querySelectorAll('.section');
  sections.forEach(section => {
    section.style.display = 'none'; // Hide all sections
  });
  document.getElementById(sectionId).style.display = 'block'; // Show the selected section
}

function displaySelectedCategories() {
  const checkboxes = document.querySelectorAll('.category-checkbox');
  const selectedCategories = [];

  checkboxes.forEach(checkbox => {
    if (checkbox.checked) {
      selectedCategories.push(checkbox.value);
    }
  });

  const selectedCategoriesDiv = document.getElementById('selected-categories');
  selectedCategoriesDiv.innerHTML = 'Selected Categories: ' + selectedCategories.join(', ') || 'None';
}
