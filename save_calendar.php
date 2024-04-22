<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

include 'database.php';

// Redirect to login page if username is not set in session
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Check if advertentie_id is sent through POST
if (!isset($_POST['advertentie_id'])) {
    header("Location: login.php");
    exit();
}

$advertentieID = $_POST['advertentie_id'];

// Function to retrieve the specific boat's calendar events
function getBoatCalendarEvents($conn, $advertentieID) {
    $sql = "SELECT start_date, event_title FROM verhuurder_calendar WHERE advertentie_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $advertentieID);
    $stmt->execute();
    $result = $stmt->get_result();
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[$row['start_date']] = $row['event_title'];
    }
    return $events;
}

// Update the calendar events based on the form data
if (isset($_POST['selected_dates'])) {
    // Loop through each day in the current month
    $numDays = date('t');
    for ($i = 1; $i <= $numDays; $i++) {
        // Format the date
        $date = date('Y-m-d', mktime(0, 0, 0, date('n'), $i, date('Y')));
        
        // Check if the day is selected
        if (in_array($i, $_POST['selected_dates'])) {
            $eventTitle = 'Beschikbaar'; // Day is available
        } else {
            $eventTitle = 'Onbeschikbaar'; // Day is unavailable
        }

        // Update the event title in the database
        $sql = "UPDATE verhuurder_calendar SET event_title = ? WHERE advertentie_id = ? AND start_date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $eventTitle, $advertentieID, $date);
        $stmt->execute();
    }
}

// Redirect back to the profile page after updating the calendar
header("Location: profile.php");
exit();
?>
