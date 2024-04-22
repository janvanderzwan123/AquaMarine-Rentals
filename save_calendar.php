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

$sql = "SELECT advertentie_id FROM advertenties WHERE verhuurder_id = (
    SELECT gebruiker_id FROM gebruikers WHERE gebruikersnaam = ?
)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $advertentieID = $row['advertentie_id'];
} else {
    header("Location: login.php");
    exit();
}

function getNumDaysInMonth() {
    return date('t');
}

function initializeCalendar($conn, $advertentieID) {
    $month = date('n');
    $year = date('Y');
    $numDays = date('t', mktime(0, 0, 0, $month, 1, $year));

    for ($i = 1; $i <= $numDays; $i++) {
        $date = date('Y-m-d', mktime(0, 0, 0, $month, $i, $year));
        $sql = "INSERT INTO verhuurder_calendar (start_date, end_date, event_title, advertentie_id) VALUES (?, ?, 'Onbeschikbaar', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $date, $date, $advertentieID);
        $stmt->execute();
    }
}

$advertentieID = $_POST['advertentie_id'];

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

$sql_check_calendar = "SELECT COUNT(*) AS num_rows FROM verhuurder_calendar WHERE advertentie_id = ?";
$stmt_check_calendar = $conn->prepare($sql_check_calendar);
$stmt_check_calendar->bind_param("i", $advertentieID);
$stmt_check_calendar->execute();
$result_check_calendar = $stmt_check_calendar->get_result();
$row_check_calendar = $result_check_calendar->fetch_assoc();
$num_rows_calendar = $row_check_calendar['num_rows'];

if ($num_rows_calendar == 0) {
    initializeCalendar($conn, $advertentieID);
}

$calendarEvents = getBoatCalendarEvents($conn, $advertentieID);
$numDays = getNumDaysInMonth();

for ($i = 1; $i <= $numDays; $i++) {
    $date = date('Y-m-d', mktime(0, 0, 0, date('n'), $i, date('Y')));
    if (isset($_POST['selected_dates'])) {
        if (in_array($i, $_POST['selected_dates'])) {
            $eventTitle = 'Beschikbaar';
        } else {
            $eventTitle = 'Onbeschikbaar';
        }
        $sql = "UPDATE verhuurder_calendar SET event_title = ? WHERE advertentie_id = ? AND start_date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $eventTitle, $advertentieID, $date);
        $stmt->execute();
    } else {
        $sql = "UPDATE verhuurder_calendar SET event_title = 'Onbeschikbaar' WHERE advertentie_id = ? AND start_date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $advertentieID, $date);
        $stmt->execute();
    }
}

header("Location: profile.php");
exit();
?>
