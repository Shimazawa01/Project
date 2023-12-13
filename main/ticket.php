<?php
// Include the necessary database connection files and session start
include("../db/db_act_management.php");
include("../db/db_user_management.php");
include("session_start.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sidebar/style_sidebar.css">
    <link rel="stylesheet" href="../styles/style_ticket.css">
    <title>My Tickets</title>
</head>

<body>
    <?php include("../sidebar/sidebar.php"); ?>

    <div class="container">
        <h2>My Tickets</h2>

        <?php
        // Retrieve user's tickets grouped by event
        $ticketsSql = "SELECT events.event_id, events.event_name, GROUP_CONCAT(tickets.ticket_id) AS ticket_ids
                       FROM events
                       LEFT JOIN tickets ON events.event_id = tickets.event_id
                       WHERE tickets.user_id = {$_SESSION['user_id']}
                       GROUP BY events.event_id, events.event_name";

        $ticketsResult = $actConn->query($ticketsSql);

        if ($ticketsResult->num_rows > 0) {
            while ($event = $ticketsResult->fetch_assoc()) {
                echo "<div class='ticket-container'>";
                echo "<h3 class='event-link' onclick='showTickets(\"{$event['ticket_ids']}\")'>{$event['event_name']}</h3>";
                echo "</div>";
            }
        } else {
            echo "<p>No tickets available.</p>";
        }
        ?>
    </div>

    <script>
        function showTickets(ticketIds) {
            // Redirect to a new page with ticket information
            window.location.href = `ticket_details.php?ticket_ids=${ticketIds}`;
        }
    </script>
</body>

</html>
