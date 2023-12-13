<?php
// Include the event management database connection file
include("../db/db_act_management.php");
include("../db/db_user_management.php");
// Start the session
include("session_start.php");

// Check if the event_id is set in the query string
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Retrieve event details from the act_management database based on the event_id
    $eventSql = "SELECT * FROM events WHERE event_id = $event_id";
    $eventResult = $actConn->query($eventSql);

    if ($eventResult->num_rows == 1) {
        $event = $eventResult->fetch_assoc();
    } else {
        // Redirect to the events.php page if the event is not found
        header("Location: events.php");
        exit();
    }
} else {
    // Redirect to the events.php page if event_id is not provided
    header("Location: events.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sidebar/style_sidebar.css">
    <link rel="stylesheet" href="../styles/style_event_page.css">
    <title><?php echo htmlspecialchars($event['event_name']); ?></title>
</head>

<body>
    <?php include("../sidebar/sidebar.php"); ?>

    <div class="container">
        

        <div class="event-details">
            <p><strong> <?php echo htmlspecialchars($event['event_name']); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
            <p><strong>Price:</strong> <?php echo htmlspecialchars($event['event_ticketcost']); ?> PHP</p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($event['event_location']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($event['event_description']); ?></p>
            <!-- Add more details as needed -->

            <?php
            // Display image or video
            $eventImage = htmlspecialchars($event['event_image']);
            if (pathinfo($eventImage, PATHINFO_EXTENSION) === 'mp4') {
                echo '<video width="100%" height="auto" controls>';
                echo '<source src="' . $eventImage . '" type="video/mp4">';
                echo 'Your browser does not support the video tag.';
                echo '</video>';
            } else {
                echo '<img src="' . $eventImage . '" alt="Event Image">';
            }
            ?>

            <!-- Buy Tickets Button -->
            <button class="buy-tickets-btn" onclick="location.href='purchase_ticket.php?event_id=<?php echo $event_id; ?>'">
        Buy Tickets
    </button>
        </div>
    </div>
</body>

</html>
