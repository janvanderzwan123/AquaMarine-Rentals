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

// Get the gebruikers_id of the current user from the session
if (!isset($_SESSION['gebruiker_id'])) {
    header("Location: login.php");
    exit();
}

$gebruikersID = $_SESSION['gebruiker_id'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if selected dates are submitted
    if (isset($_POST['selected_dates']) && is_array($_POST['selected_dates'])) {
        // Prepare the SQL statement to insert or update events in the calendar
        $insertStmt = $conn->prepare("INSERT INTO verhuurder_calendar (user_id, event_title, start_date, end_date) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE event_title = VALUES(event_title)");
        $insertStmt->bind_param("isss", $userID, $eventTitle, $startDate, $endDate);

        // Set event title
        $eventTitle = "Beschikbaar";

        // Iterate over selected dates and insert or update events in the database
        foreach ($_POST['selected_dates'] as $date) {
            // Set start date and end date (assuming events are for the whole day)
            $startDate = date('Y-m-d', mktime(0, 0, 0, date('n'), $date, date('Y')));
            $endDate = date('Y-m-d', mktime(0, 0, 0, date('n'), $date, date('Y')));

            // Bind the gebruikers_id to user_id in the verhuurder_calendar table
            $userID = $gebruikersID;

            // Execute the prepared statement
            $insertStmt->execute();
        }

        // Close the prepared statement
        $insertStmt->close();

        // Redirect back to the profile page after saving
        header("Location: profile.php");
        exit();
    } else {
        // If no dates are selected, redirect back to the profile page
        header("Location: profile.php");
        exit();
    }
} else {
    // If the form is not submitted, redirect back to the profile page
    header("Location: profile.php");
    exit();
}
?>
