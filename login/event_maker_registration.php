<?php
// Include the user management database connection file
include("../db/db_user_management.php");

// Initialize variables to store form data and error messages
$makerUsername = $makerPassword = $makerEmail = "";
$registrationMessage = $errorMessage = "";

// Process event maker registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $makerUsername = mysqli_real_escape_string($userConn, $_POST["maker_username"]);
    $makerPassword = mysqli_real_escape_string($userConn, $_POST["maker_password"]);
    $makerEmail = mysqli_real_escape_string($userConn, $_POST["maker_email"]);

    // Validate password
    if (!isValidPassword($makerPassword)) {
        $errorMessage = "Password must be at least 8 characters long, contain at least one capital letter, and at least one number.";
    } else {
        // Use a prepared statement to insert event maker data into the user management database
        $stmt = $userConn->prepare("INSERT INTO users (username, password, email, user_type) VALUES (?, ?, ?, 'event_maker')");
        $stmt->bind_param("sss", $makerUsername, $makerPassword, $makerEmail);

        if ($stmt->execute()) {
            $registrationMessage = "Event Maker Registration successful! Redirecting to the dashboard...";

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Event Maker Registration</title>
</head>

<body>
    <div class="container">
        <h2>Event Maker Registration</h2>

        <?php
        // Display registration success or error message
        if (isset($registrationMessage)) {
            echo "<p class='success-message'>$registrationMessage</p>";
        } elseif (!empty($errorMessage)) {
            echo "<p class='error-message'>$errorMessage</p>";
        }
        ?>

        <form action="event_maker_registration.php" method="post">
            <label for="maker_username">Username:</label>
            <input type="text" name="maker_username" required value="<?php echo htmlspecialchars($makerUsername); ?>">

            <label for="maker_password">Password:</label>
            <input type="password" name="maker_password" required>

            <label for="maker_email">Email:</label>
            <input type="email" name="maker_email" required value="<?php echo htmlspecialchars($makerEmail); ?>">

            <!-- Additional event maker fields can be added as needed -->

            <button type="submit">Register as Event Maker</button>
        </form>
    </div>
</body>

</html>
