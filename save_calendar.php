<?php
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

include 'database.php';

// Check if username is set in the session
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];

// Retrieve advertisement ID for the logged-in user
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

// Function to get the number of days in the current month
function getNumDaysInMonth() {
    return date('t');
}

function updateCalendarEvents($conn, $advertentieID) {
    $numDays = getNumDaysInMonth();
    $selected_dates = isset($_POST['selected_dates']) ? $_POST['selected_dates'] : [];

    for ($i = 1; $i <= $numDays; $i++) {
        $date = date('Y-m-d', mktime(0, 0, 0, date('n'), $i, date('Y')));
        $eventTitle = in_array($i, $selected_dates) ? 'Beschikbaar' : 'Onbeschikbaar';

        // Prepare and execute the SQL statement
        $sql = "UPDATE verhuurder_calendar SET event_title = ? WHERE advertentie_id = ? AND start_date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $eventTitle, $advertentieID, $date);
        $stmt->execute();
    }
}

updateCalendarEvents($conn, $advertentieID);

header("Location: profile.php");
exit();
?>
