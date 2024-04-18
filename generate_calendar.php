<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include 'database.php';

// Get the username of the current user from the session
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Retrieve the gebruiker_id based on the username
// (Assuming you have already fetched the gebruiker_id in your previous scripts)
// $gebruikerID = ...;

// Function to get the number of days in the current month
function getNumDaysInMonth() {
    return date('t');
}

// Function to retrieve the user's calendar events for the current month
function getUserCalendarEvents($conn, $gebruikerID) {
    $sql = "SELECT start_date, event_title FROM verhuurder_calendar WHERE user_id = ? AND MONTH(start_date) = MONTH(CURRENT_DATE()) AND YEAR(start_date) = YEAR(CURRENT_DATE())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $gebruikerID);
    $stmt->execute();
    $result = $stmt->get_result();
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[$row['start_date']] = $row['event_title'];
    }
    return $events;
}

// Retrieve the calendar events for the current user and month
$calendarEvents = getUserCalendarEvents($conn, $gebruikerID);

// Get the number of days in the current month
$numDays = getNumDaysInMonth();

// Loop through each day of the month
for ($i = 1; $i <= $numDays; $i++) {
    // Set the date
    $date = date('Y-m-d', mktime(0, 0, 0, date('n'), $i, date('Y')));
    
    // Check if the day-box was submitted
    if (isset($_POST['selected_dates']) && in_array($i, $_POST['selected_dates'])) {
        // Toggle the event_title between "Beschikbaar" and "Onbeschikbaar"
        $eventTitle = ($calendarEvents[$date] === 'Beschikbaar') ? 'Onbeschikbaar' : 'Beschikbaar';
        // Update the event_title in the database
        $sql = "UPDATE verhuurder_calendar SET event_title = ? WHERE user_id = ? AND start_date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $eventTitle, $gebruikerID, $date);
        $stmt->execute();
    }
}

// Redirect back to the profile page after processing the calendar updates
header("Location: profile.php");
exit();
?>
