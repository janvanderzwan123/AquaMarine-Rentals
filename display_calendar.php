<?php
include 'database.php';
session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT advertentie_id FROM advertenties WHERE gebruiker_id = ?");
$stmt->bind_param("i", $gebruiker_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $advertentie_id = $row['advertentie_id'];
} else {
    exit("Advertentie niet gevonden.");
}

function displayCalendar($conn, $advertentie_id) {
    $month = date('n');
    $year = date('Y');
    $numDays = date('t', mktime(0, 0, 0, $month, 1, $year));
    $firstDayOfWeek = date('N', mktime(0, 0, 0, $month, 1, $year));
    $eventTitles = array_fill(1, $numDays, 'Onbeschikbaar');

    $sql = "SELECT DAY(start_date) AS day, event_title FROM verhuurder_calendar WHERE MONTH(start_date) = ? AND YEAR(start_date) = ? AND advertentie_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $month, $year, $advertentie_id);
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

    return $html;
}
