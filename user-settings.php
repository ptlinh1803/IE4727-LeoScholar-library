<?php
require 'db-connect.php';
session_start();

// Verify if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the current user's information from the database
$user_id = $_SESSION['user_id'];
$sql_fetch_user = "SELECT username, email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql_fetch_user);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$current_user = $user_result->fetch_assoc();

$update_success = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables with current user info
    $new_username = $_POST['username'] ?? $current_user['username'];
    $new_email = $_POST['email'] ?? $current_user['email'];
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate new password if entered
    if (!empty($new_password) && $new_password !== $confirm_password) {
        $error_message = "New password and confirmation do not match.";
    } else {
        // Start building the update query
        $sql_update_user = "UPDATE users SET username = ?, email = ?";
        $params = [$new_username, $new_email];
        $types = "ss";

        if (!empty($new_password)) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql_update_user .= ", password = ?";
            $params[] = $hashed_password;
            $types .= "s";
        }

        $sql_update_user .= " WHERE user_id = ?";
        $params[] = $user_id;
        $types .= "i";

        // Prepare and execute the query
        $stmt = $conn->prepare($sql_update_user);
        $stmt->bind_param($types, ...$params);
        if ($stmt->execute()) {
            $update_success = "Your settings have been updated successfully.";
            // Refresh current user data
            $current_user['username'] = $new_username;
            $current_user['email'] = $new_email;
        } else {
            $error_message = "An error occurred. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #333;
        }
        form label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            margin: 10px 0;
            color: green;
        }
        .error {
            margin: 10px 0;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Settings</h2>
        <?php if (!empty($update_success)) : ?>
            <div class="message"><?= $update_success ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)) : ?>
            <div class="error"><?= $error_message ?></div>
        <?php endif; ?>
        
        <form action="user-settings.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($current_user['username']) ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($current_user['email']) ?>" required>
            
            <label for="new_password">New Password (leave blank to keep current password):</label>
            <input type="password" id="new_password" name="new_password">
            
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password">
            
            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
