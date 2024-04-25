<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

include 'database.php';

// Redirect if username is not set
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];

// Retrieve the advertisement ID from the database based on the username
$sql = "SELECT advertentie_id FROM advertenties WHERE verhuurder_id = (
    SELECT gebruiker_id FROM gebruikers WHERE gebruikersnaam = ?
)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: login.php");
    exit();
}

$row = $result->fetch_assoc();
$advertentieID = $row['advertentie_id'];

// Function to toggle calendar events based on the selected dates
function toggleCalendarEvents($conn, $advertentieID) {
    $numDays = date('t');  // Number of days in the current month
    $selected_dates = isset($_POST['selected_dates']) ? $_POST['selected_dates'] : [];

    for ($i = 1; $i <= $numDays; $i++) {
        $date = date('Y-m-d', mktime(0, 0, 0, date('n'), $i, date('Y')));
        // Check the current state of each date
        $sql = "SELECT event_title FROM verhuurder_calendar WHERE advertentie_id = ? AND start_date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $advertentieID, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        $currentEventTitle = $result->fetch_assoc()['event_title'];

        // Determine the new event title based on whether the date was selected and its current state
        if (in_array($i, $selected_dates)) {
            $newEventTitle = ($currentEventTitle === 'Beschikbaar') ? 'Onbeschikbaar' : 'Beschikbaar';
        } else {
            $newEventTitle = $currentEventTitle; // No change if not selected
        }

        // Update the event title for the date
        $sqlUpdate = "UPDATE verhuurder_calendar SET event_title = ? WHERE advertentie_id = ? AND start_date = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("sis", $newEventTitle, $advertentieID, $date);
        $stmtUpdate->execute();
    }
}

toggleCalendarEvents($conn, $advertentieID);

// Redirect back to the profile page after updating the calendar
header("Location: profile.php");
exit();
?>
