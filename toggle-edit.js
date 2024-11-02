function toggleEdit(elementId) {
  const field = document.getElementById(elementId);
  field.readOnly = !field.readOnly; // Toggle readonly
  if (!field.readOnly) {
    field.classList.add("editable"); // Add a class to style the editable field
    field.focus(); // Optionally focus the field for user convenience
  } else {
    field.classList.remove("editable");
  }
}