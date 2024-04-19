<?php
session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

include 'database.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];

$sql = "SELECT gebruiker_id FROM gebruikers WHERE gebruikersnaam = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $gebruikerID = $row['gebruiker_id'];
} else {
    header("Location: login.php");
    exit();
}

function getNumDaysInMonth() {
    return date('t');
}

function getUserCalendarEvents($conn, $gebruikerID) {
    $sql = "SELECT start_date, event_title FROM verhuurder_calendar WHERE gebruiker_id = ? AND MONTH(start_date) = MONTH(CURRENT_DATE()) AND YEAR(start_date) = YEAR(CURRENT_DATE())";
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

$calendarEvents = getUserCalendarEvents($conn, $gebruikerID);
$numDays = getNumDaysInMonth();

for ($i = 1; $i <= $numDays; $i++) {
    $date = date('Y-m-d', mktime(0, 0, 0, date('n'), $i, date('Y')));
    if (isset($_POST['selected_dates'])) {
        if (in_array($i, $_POST['selected_dates'])) {
            $eventTitle = 'Beschikbaar';
        } else {
            $eventTitle = 'Onbeschikbaar';
        }
        $sql = "UPDATE verhuurder_calendar SET event_title = ? WHERE gebruiker_id = ? AND start_date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $eventTitle, $gebruikerID, $date);
        $stmt->execute();
    } else {
        $sql = "UPDATE verhuurder_calendar SET event_title = 'Onbeschikbaar' WHERE gebruiker_id = ? AND start_date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $gebruikerID, $date);
        $stmt->execute();
    }
}

header("Location: profile.php");
exit();
?>
