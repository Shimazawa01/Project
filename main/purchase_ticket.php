<?php
// purchase_ticket.php
include("../db/db_act_management.php");
include("../db/db_user_management.php");
include("session_start.php");

// Retrieve event_id from the URL parameter
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
    // Redirect if event_id is not provided
    header("Location: events.php");
    exit();
}

// Process ticket purchase form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $numTickets = mysqli_real_escape_string($actConn, $_POST["numTickets"]);

    // Insert ticket records into the tickets table
    $purchaseSql = "INSERT INTO tickets (event_id, user_id, ticket_price, ticket_code) VALUES ";
    for ($i = 0; $i < $numTickets; $i++) {
        $ticketCode = generateTicketCode(); // Generate a ticket code
        $purchaseSql .= "($event_id, {$_SESSION['user_id']}, {$event['event_ticketcost']}, '$ticketCode')";
        if ($i < $numTickets - 1) {
            $purchaseSql .= ", ";
        }
    }

    if ($actConn->query($purchaseSql) === TRUE) {
        // Tickets purchased successfully
        echo '<script>alert("Tickets purchased successfully."); setTimeout(function(){ window.location.href = "events.php"; }, 3000);</script>';
        header("Location:events.php");
    } else {
        // Error purchasing tickets
        echo '<script>alert("Error purchasing tickets.");</script>';
    }
}

// Function to generate a random ticket code
function generateTicketCode()
{
    return bin2hex(random_bytes(8)); // Generate an 16-character hexadecimal code
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Include your CSS stylesheets and other head elements -->
    <title>Purchase Tickets</title>
</head>

<body>
    <!-- Your HTML content goes here -->
    <h2>Purchase Tickets for <?php echo htmlspecialchars($event['event_name']); ?></h2>

    <div class="event-details">
        <!-- Display event details -->
        <p><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
        <p><strong>Price:</strong> <?php echo htmlspecialchars($event['event_ticketcost']); ?></p>
        <!-- Add more event details as needed -->

        <!-- Display image or video -->
        <?php
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
    </div>

    <!-- Display ticket purchase form -->
    <form action="purchase_ticket.php?event_id=<?php echo $event_id; ?>" method="post">
        <label for="numTickets">Number of Tickets:</label>
        <input type="number" name="numTickets" min="1" required>
        <button type="submit">Purchase Tickets</button>
    </form>
</body>

</html>
