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
$sql = "SELECT gebruiker_id FROM gebruikers WHERE gebruikersnaam = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if the username exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $gebruikerID = $row['gebruiker_id'];
} else {
    // Redirect if the username is not found
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if selected dates are submitted
    if (isset($_POST['selected_dates']) && is_array($_POST['selected_dates'])) {
        // Prepare the SQL statement to insert or update events in the calendar
        $insertStmt = $conn->prepare("INSERT INTO verhuurder_calendar (user_id, event_title, start_date, end_date) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE event_title = CASE WHEN event_title = 'Beschikbaar' THEN 'Onbeschikbaar' ELSE 'Beschikbaar' END");
        $insertStmt->bind_param("isss", $userID, $eventTitle, $startDate, $endDate);

        // Iterate over selected dates and insert or update events in the database
        foreach ($_POST['selected_dates'] as $date) {
            // Set start date and end date (assuming events are for the whole day)
            $startDate = date('Y-m-d', mktime(0, 0, 0, date('n'), $date, date('Y')));
            $endDate = date('Y-m-d', mktime(0, 0, 0, date('n'), $date, date('Y')));

            // Bind the gebruiker_id to user_id in the verhuurder_calendar table
            $userID = $gebruikerID;

            // Set event title to Beschikbaar by default (will be toggled if already exists)
            $eventTitle = 'Beschikbaar';

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
