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
        $insertStmt = $conn->prepare("INSERT INTO verhuurder_calendar (user_id, event_title, start_date, end_date) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE event_title = VALUES(event_title)");
        $insertStmt->bind_param("isss", $userID, $eventTitle, $startDate, $endDate);

        // Set event title
        $eventTitle = "Beschikbaar";

        foreach ($_POST['selected_dates'] as $date) {
            $startDate = date('Y-m-d', mktime(0, 0, 0, date('n'), $date, date('Y')));
            $endDate = date('Y-m-d', mktime(0, 0, 0, date('n'), $date, date('Y')));

            $userID = $gebruikerID;

            $insertStmt->execute();
        }

        $insertStmt->close();

        header("Location: profile.php");
        exit();
    } else {
        header("Location: profile.php");
        exit();
    }
} else {
    header("Location: profile.php");
    exit();
}
?>
