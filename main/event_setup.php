<?php
// Include the event management database connection file
include("../db/db_act_management.php");
include("../db/db_user_management.php");

// Start the session
include("session_start.php");

// Retrieve user type from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT user_type FROM users WHERE id = $user_id";
$result = $userConn->query($sql);

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $user_type = $user['user_type'];

    // Check user type and restrict access if necessary
    if ($user_type === 'regular') {
        // Display a pop-up message for regular users
        echo '<script>alert("Regular users do not have access to Event Setup. Redirecting to the dashboard.");';
        echo 'window.location.href="../main/dashboard.php";</script>';
        exit();
    }
} else {
    // Handle the case where user information is not found
    header("Location: ../login/login.php");
    exit();
}

// Process event details form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize event details from the form
    $eventName = mysqli_real_escape_string($actConn, $_POST["eventName"]);
    $eventDate = mysqli_real_escape_string($actConn, $_POST["eventDate"]);
    $eventLocation = mysqli_real_escape_string($actConn, $_POST["eventLocation"]);
    $eventDescription = mysqli_real_escape_string($actConn, $_POST["eventDescription"]);
    $eventTicketCost = mysqli_real_escape_string($actConn, $_POST["eventTicketCost"]);

    // File upload logic for images
    $imagePath = '';
    if (!empty($_FILES["eventImage"]["name"])) {
        $imagePath = '../uploads/images/' . uniqid() . '_' . $_FILES["eventImage"]["name"];
        move_uploaded_file($_FILES["eventImage"]["tmp_name"], $imagePath);
    }

    // File upload logic for videos
    $videoPath = '';
    if (!empty($_FILES["eventVideo"]["name"])) {
        $videoPath = '../uploads/videos/' . uniqid() . '_' . $_FILES["eventVideo"]["name"];
        move_uploaded_file($_FILES["eventVideo"]["tmp_name"], $videoPath);
    }

    // Insert event details into the database
    $insertSql = "INSERT INTO events (event_name, event_date, event_location, event_description, event_ticketcost, event_image, event_video, user_id) 
                  VALUES ('$eventName', '$eventDate', '$eventLocation', '$eventDescription', '$eventTicketCost', '$imagePath', '$videoPath', $user_id)";

    if ($actConn->query($insertSql) === TRUE) {
        // Event details inserted successfully
        echo '<script>alert("Event details submitted successfully."); window.location.href="../main/dashboard.php";</script>';
    } else {
        // Error inserting event details
        echo '<script>alert("Error submitting event details. ' . $actConn->error . '");</script>';
    }
}

// Continue with the rest of your event_setup.php content
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sidebar/style_sidebar.css">
    <link rel="stylesheet" href="../styles/style_event_setup.css">
    <!-- Add any additional stylesheets as needed -->
    <title>Event Setup</title>
</head>

<body>
    <?php include("../sidebar/sidebar.php"); ?>

    <div class="container">
        <!-- Event Setup content goes here -->
        <h2>Event Setup</h2>

        <!-- Event Details Form -->
        <form action="event_setup.php" method="post" enctype="multipart/form-data">
            <label for="eventName">Event Name:</label>
            <input type="text" name="eventName" required>

            <label for="eventDate">Event Date:</label>
            <input type="date" name="eventDate" required>

            <label for="eventLocation">Event Location:</label>
            <input type="text" name="eventLocation" required>

            <label for="eventDescription">Event Description:</label>
            <textarea name="eventDescription" rows="4" required></textarea>

            <label for="eventTicketCost">Event Ticket Cost:</label>
            <input type="text" name="eventTicketCost" required>

            <label for="eventImage">Event Image:</label>
            <input type="file" name="eventImage" accept="image/*" required>

            <label for="eventVideo">Event Video:</label>
            <input type="file" name="eventVideo" accept="video/*" required>

            <button type="submit">Submit Event Details</button>
        </form>
    </div>
</body>

</html>
