<?php
// Include the event management database connection file
include("../db/db_act_management.php");
include("../db/db_user_management.php");
// Start the session
include("session_start.php");

/* Retrieve user type from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT user_type FROM users WHERE id = $user_id";
$result = $actConn->query($sql);

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $user_type = $user['user_type'];
} else {
    // Handle the case where user information is not found
    header("Location: ../login/login.php");
    exit();
}*/

// Retrieve ongoing events from the act_management database
$eventsSql = "SELECT * FROM events WHERE event_date >= CURDATE()";
$eventsResult = $actConn->query($eventsSql);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sidebar/style_sidebar.css">
    <link rel="stylesheet" href="../styles/style_events.css">
    <title>Ongoing Events</title>
</head>

<body>
    <?php include("../sidebar/sidebar.php"); ?>

    <div class="container">
        <h2>Ongoing Events</h2>

        <?php
        while ($event = $eventsResult->fetch_assoc()) {
            // Output event information with a link to the event page
            echo '<div class="event-card">';
            echo '<h3><a href="event_page.php?event_id=' . $event['event_id'] . '">' . htmlspecialchars($event['event_name']) . '</a></h3>';
            echo '<p><strong>Date:</strong> ' . htmlspecialchars($event['event_date']) . '</p>';
            echo '<p><strong>Location:</strong> ' . htmlspecialchars($event['event_location']) . '</p>';
            echo '<p><strong>Price:</strong> ' . htmlspecialchars($event['event_ticketcost']) . '</p>';
            //echo '<p><strong>Max Attendees:</strong> ' . htmlspecialchars($event['population_cap']) . '</p>';
            echo '<p><strong>Description:</strong> ' . htmlspecialchars($event['event_description']) . '</p>';
        
            // Display image or video
            $eventVideo = htmlspecialchars($event['event_video']);
            if (pathinfo($eventVideo, PATHINFO_EXTENSION) === 'mp4') {
                echo '<video width="100%" height="auto" controls>';
                echo '<source src="' . $eventVideo . '" type="video/mp4">';
                echo 'Your browser does not support the video tag.';
                echo '</video>';
            } else {
               //echo '<img src="' . $eventImage . '" alt="Event Image">';
            }
        
            // Add more information as needed
            echo '</div>';
        }
        
        // If no events are available
        if ($eventsResult->num_rows === 0) {
            echo '<p>No events available.</p>';
        }
        ?>
    </div>
</body>

</html>
