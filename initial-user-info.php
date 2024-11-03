<?php
// Step 1: Start session
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Step 2: Check if user_id is set
if (!isset($_SESSION['user_id'])) {
  echo "Error: User not logged in.";
  exit;
} else {
  $user_id = $_SESSION['user_id']; // Step 2: Store user_id
}

// Step 3: Require database connection
require "db-connect.php";

// Step 4: Prepare and execute the query
$query = "SELECT name, email, university_id, phone FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // Bind the user_id parameter
if (!$stmt->execute()) {
  echo "Error: Could not retrieve user information.";
  exit;
}

// Step 5: Store the result into variables
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $name = $row['name'];
  $email = $row['email'];
  $institution = $row['university_id'];
  $phone = $row['phone'];
} else {
  echo "Error: No user found.";
  exit;
}

// Step 6: Write into the HTML page
echo "<tr>
          <td>Name</td>
          <td><input type=\"text\" id=\"settings-name\" name=\"settings-name\" value=\"$name\"
              onchange=\"validateUpdateInfo()\"></td>
        </tr>
        <tr>
          <td>Email</td>
          <td>
            <p id=\"email\">$email</p>
          </td>
        </tr>
        <tr>
          <td>Institution</td>
          <td>
            <p id=\"institution\">$institution</p>
          </td>
        </tr>
        <tr>
          <td>Phone</td>
          <td><input type=\"text\" id=\"settings-phone\" name=\"settings-phone\" value=\"$phone\"
              onchange=\"validateUpdateInfo()\"></td>
        </tr>";
?>
