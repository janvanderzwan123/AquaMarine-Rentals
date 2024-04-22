<?php
include 'database.php';
session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Secure the initial user query
$stmt = $conn->prepare("SELECT gebruiker_id FROM gebruikers WHERE gebruikersnaam = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $gebruiker_id = $row['gebruiker_id'];
} else {
    exit("Gebruiker niet gevonden.");
}

$month = date('n');
$year = date('Y');
$numDays = date('t', mktime(0, 0, 0, $month, 1, $year));
$firstDayOfWeek = date('N', mktime(0, 0, 0, $month, 1, $year));

// Pre-fill event titles with 'Onbeschikbaar'
$eventTitles = array_fill(1, $numDays, 'Onbeschikbaar');

$sql = "SELECT DAY(start_date) AS day, event_title FROM verhuurder_calendar WHERE MONTH(start_date) = ? AND YEAR(start_date) = ? AND gebruiker_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $month, $year, $gebruiker_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $eventTitles[$row['day']] = $row['event_title'];
}

$html = '<div class="calendar">';
for ($i = 1; $i <= $numDays + $firstDayOfWeek - 1; $i++) {
    if (($i - 1) % 7 == 0) {
        $html .= '<div class="row">';
    }
    if ($i < $firstDayOfWeek) {
        $html .= '<div class="date"></div>';
    } else {
        $day = $i - $firstDayOfWeek + 1;
        $class = $eventTitles[$day] === 'Beschikbaar' ? 'day-box' : 'selected';
        $html .= '<div class="' . $class . '">' . $day . '</div>';
    }
    if ($i % 7 == 0 || $i == $numDays + $firstDayOfWeek - 1) {
        $html .= '</div>';
    }
}
$html .= '</div>';

echo $html;

