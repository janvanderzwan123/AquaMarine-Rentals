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

// Function to get the number of days in the current month
function getNumDaysInMonth() {
    return date('t');
}

// Function to prepopulate the verhuurder_calendar table with dates marked as "Onbeschikbaar"
function prepopulateCalendar($conn, $gebruikerID) {
    // Get the number of days in the current month
    $numDays = getNumDaysInMonth();

    // Prepare the SQL statement to insert initial entries in the calendar
    $insertStmt = $conn->prepare("INSERT INTO verhuurder_calendar (user_id, event_title, start_date, end_date) VALUES (?, 'Onbeschikbaar', ?, ?)");
    $insertStmt->bind_param("iss", $userID, $date, $date);

    // Iterate over each day of the month and insert into the database
    for ($i = 1; $i <= $numDays; $i++) {
        // Set the date
        $date = date('Y-m-d', mktime(0, 0, 0, date('n'), $i, date('Y')));

        // Bind the gebruiker_id to user_id in the verhuurder_calendar table
        $userID = $gebruikerID;

        // Execute the prepared statement
        $insertStmt->execute();
    }

    // Close the prepared statement
    $insertStmt->close();
}

// Check if the verhuurder_calendar table is already populated for the current month
$sql = "SELECT COUNT(*) AS num_events FROM verhuurder_calendar WHERE user_id = ? AND MONTH(start_date) = MONTH(CURRENT_DATE()) AND YEAR(start_date) = YEAR(CURRENT_DATE())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $gebruikerID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$numEvents = $row['num_events'];

// If the verhuurder_calendar table is not already populated, prepopulate it
if ($numEvents == 0) {
    prepopulateCalendar($conn, $gebruikerID);
}

// Redirect back to the profile page after prepopulating the calendar
header("Location: profile.php");
exit();
?>
