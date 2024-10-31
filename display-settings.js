function displayContent(contentType) {
  const section = document.getElementById("settings-section");
  section.innerHTML = "";  // Clear any existing content

  // Use a switch case to load different content based on button clicked
  switch (contentType) {
    case 'fav-categories':
      displayFavoriteCategories(section);
      break;
    case 'personal-info':
      displayPersonalInfos(section);
      break;
    case 'change-password':
      displayChangePassword(section);
      break;
    default: // Default setting option is favorite book categories
      displayFavoriteCategories(section);
  }
}

function displayFavoriteCategories(section) {
  section.innerHTML = "<p>Here you can select your favorite categories.</p>";
}

function displayPersonalInfos(section) {
  // Declare the HTML snippet as a stirng literal using the backquote (`)
  section.innerHTML = `
<h4 id="form-heading">Update your personal particulars below:</h4>
<form id="user-info-form">
  <table id="user-info-table">
    <tr>
      <th>Field</th>
      <th>Value</th>
    </tr>
    <tr>
      <td>Assigned ID</td>
      <td><p id="user_id">12345</p></td>
    </tr>
    <tr>
      <td>Name</td>
      <td><input type="text" id="name" name="name" value="John Doe"></td>
    </tr>
    <tr>
      <td>Email</td>
      <td><p id="email">johndoe@example.com</p></td>
    </tr>
    <tr>
      <td>Institution</td>
      <td><p id="institution">Example University</p></td>
    </tr>
    <tr>
      <td>Phone</td>
      <td><input type="text" id="phone" name="phone" value="123-456-7890"></td>
    </tr>
  </table>
  <button type="submit" class="submit-button">Submit</button>
</form>
`;
}

function displayChangePassword(section) {
  section.innerHTML = "<p>Change your password in this section.</p>";
}
