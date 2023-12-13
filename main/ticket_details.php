<?php
// Include the necessary database connection files and session start
include("../db/db_act_management.php");
include("../db/db_user_management.php");
include("session_start.php");

// Retrieve ticket_ids from the URL parameter
if (isset($_GET['ticket_ids'])) {
    $ticketIds = explode(',', $_GET['ticket_ids']);
} else {
    // Redirect if ticket_ids are not provided
    header("Location: my_tickets.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sidebar/style_sidebar.css">
    <link rel="stylesheet" href="../styles/style_ticket.css">
    <title>Ticket Details</title>
</head>

<body>
    <?php include("../sidebar/sidebar.php"); ?>

    <div class="container">
        <h2>Ticket Details</h2>

        <?php
        // Retrieve ticket details based on ticket_ids and include event name
        $ticketDetailsSql = "SELECT tickets.*, events.event_name FROM tickets 
                             JOIN events ON tickets.event_id = events.event_id
                             WHERE ticket_id IN (" . implode(',', $ticketIds) . ")";
        $ticketDetailsResult = $actConn->query($ticketDetailsSql);

        if ($ticketDetailsResult->num_rows > 0) {
            while ($ticket = $ticketDetailsResult->fetch_assoc()) {
                echo "<div class='ticket-container'>";
                echo "<h3>Ticket for Event: {$ticket['event_name']}</h3>";
                echo "<p><strong>Purchase Date:</strong> {$ticket['purchase_date']}</p>";
                echo "<p><strong>Ticket Price:</strong> {$ticket['ticket_price']}</p>";
                echo "<p><strong>Ticket Code:</strong> {$ticket['ticket_code']}</p>";
                // Add more ticket details as needed
                echo "</div>";
            }
        } else {
            echo "<p>No ticket details available.</p>";
        }
        ?>
    </div>
</body>

</html>
