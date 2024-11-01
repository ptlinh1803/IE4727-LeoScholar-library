<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Create new session if it has not been created yet
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Connect to db
require "db-connect.php";

// Get book_id from URL parameter
$book_id = isset($_GET['book_id']) && is_numeric($_GET['book_id']) ? (int) $_GET['book_id'] : null;

// Hardcode the librarian id for now:
$_SESSION['librarian_id'] = 1;
$librarian_id = $_SESSION['librarian_id'];

// 1. Retrieve the university_id for the librarian
$query_university = "SELECT university_id FROM librarians WHERE librarian_id = '$librarian_id'";
$result_university = mysqli_query($conn, $query_university);
if (!$result_university) {
  echo "Error retrieving university ID: " . mysqli_error($conn);
  exit;
}
$university_row = mysqli_fetch_assoc($result_university);
$university_id = $university_row['university_id'];

// 2. Retrieve branch availability details for the specified book within the librarian's university
$query_availability = "
  WITH Branches AS (
    SELECT branch_id, branch_name 
    FROM branches 
    WHERE university_id = '$university_id'
  ), 
  BookAvailability AS (
    SELECT branch_id, available_copies, shelf 
    FROM book_availability 
    WHERE book_id = '$book_id'
  )
  SELECT b.branch_name, ba.available_copies, ba.shelf 
  FROM Branches b 
  LEFT JOIN BookAvailability ba ON b.branch_id = ba.branch_id
";
$result_availability = mysqli_query($conn, $query_availability);

if (!$result_availability) {
  echo "Error retrieving availability data: " . mysqli_error($conn);
  exit;
}
?>

<table>
<?php
// 3. Display results in a dynamic table
while ($row = mysqli_fetch_assoc($result_availability)) {
  $branch_name = $row['branch_name'];
  $available_copies = $row['available_copies'] !== NULL ? $row['available_copies'] : 0;
  $shelf = $row['shelf'] !== NULL ? $row['shelf'] : "";
  ?>
  <tr>
    <td><h4><?php echo htmlspecialchars($branch_name); ?></h4></td>
    <td><input type="number" value="<?php echo htmlspecialchars($available_copies); ?>"></td>
    <td><input type="text" value="<?php echo htmlspecialchars($shelf); ?>"></td>
  </tr>
  <?php
}
?>
</table>