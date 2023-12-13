<?php
// Include the user management database connection file
include("../db/db_user_management.php");

// Initialize variables to store form data and error messages
$username = $password = $email = "";
$registrationMessage = $errorMessage = "";

// Process regular user registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $username = mysqli_real_escape_string($userConn, $_POST["username"]);
    $password = mysqli_real_escape_string($userConn, $_POST["password"]);
    $email = mysqli_real_escape_string($userConn, $_POST["email"]);

    // Validate password
    if (!isValidPassword($password)) {
        $errorMessage = "Password must be at least 8 characters long, contain at least one capital letter, and at least one number.";
    } else {
        // Use a prepared statement to insert regular user data into the user management database
        $stmt = $userConn->prepare("INSERT INTO users (username, password, email, user_type) VALUES (?, ?, ?, 'regular')");
        $stmt->bind_param("sss", $username, $password, $email);

        if ($stmt->execute()) {
            $registrationMessage = "Regular User Registration successful! Redirecting to the dashboard...";

            // Close the prepared statement
            $stmt->close();

            // Close the user management database connection
            $userConn->close();

            // Redirect to the dashboard after a brief delay (adjust as needed)
            header("refresh:3;url=../main/dashboard.php");
            exit; // Ensure no further code execution after the redirection
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }
    }
}

// Function to validate password
function isValidPassword($password)
{
    // Password must be at least 8 characters long, contain at least one capital letter, and at least one number
    return (strlen($password) >= 8 && preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password));
}

// Close the user management database connection if not already closed
if (isset($userConn)) {
    $userConn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Regular User Registration</title>
</head>

<body>
    <div class="container">
        <h2>Regular User Registration</h2>

        <?php
        // Display registration success or error message
        if (isset($registrationMessage)) {
            echo "<p class='success-message'>$registrationMessage</p>";
        } elseif (!empty($errorMessage)) {
            echo "<p class='error-message'>$errorMessage</p>";
        }
        ?>

        <form action="regular_user_registration.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" required value="<?php echo htmlspecialchars($username); ?>">

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">

            <!-- Additional regular user fields can be added as needed -->

            <button type="submit">Register as Regular User</button>
        </form>
    </div>
</body>

</html>
