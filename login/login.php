<?php
session_start();
// Check if the user is already logged in, redirect to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ../main/dashboard.php");
    exit();
}


// Include the user management database connection file
include("../db/db_user_management.php");

// Initialize variables for login form
$username = $password = "";
$errorMessage = "";

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $username = mysqli_real_escape_string($userConn, $_POST["username"]);
    $password = mysqli_real_escape_string($userConn, $_POST["password"]);

    // Query the user table to check for the entered username and password
    $sql = "SELECT id FROM users WHERE username = '$username' AND password = '$password'";
    $result = $userConn->query($sql);

    if ($result->num_rows == 1) {
        // Login successful, set session variables
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];

        // Redirect to the main dashboard or another secure page
        header("Location: ../main/dashboard.php");
        exit();
    } else {
        // Login failed
        $errorMessage = "Invalid username or password";
    }
}

// Close the user management database connection
$userConn->close();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style_login.css"> <!-- Adjust the path accordingly -->
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="login-box">
            <h2>Login</h2>

            <?php
            // Display login error message
            if (!empty($errorMessage)) {
                echo "<p class='error-message'>$errorMessage</p>";
            }
            ?>

            <form action="login.php" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" required value="<?php echo htmlspecialchars($username); ?>">

                <label for="password">Password:</label>
                <input type="password" name="password" required>

                <button type="submit">Login</button>
            </form>

            <a class="link-to-entry" href="../entry/index.html">Back to Entry Page</a>
        </div>
    </div>
</body>

</html>

