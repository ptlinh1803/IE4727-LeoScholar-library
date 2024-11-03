function showSection(sectionId) {
  const sections = document.querySelectorAll('.section');
  sections.forEach(section => {
    section.style.display = 'none'; // Hide all sections
  });
  document.getElementById(sectionId).style.display = 'block'; // Show the selected section
}

// Show fav-categories by default
document.addEventListener("DOMContentLoaded", showSection('fav-categories'));