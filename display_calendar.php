<?php
include 'database.php';
session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$sql = "SELECT gebruiker_id FROM gebruikers WHERE gebruikersnaam = '$username'";
$result = $conn->query($sql);

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
$eventTitles = [];

for ($i = 1; $i <= $numDays; $i++) {
    $start_date = date('Y-m-d', mktime(0, 0, 0, $month, $i, $year));
    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' + 24 hours'));

    $sql = "SELECT event_title FROM verhuurder_calendar WHERE start_date = ? AND end_date = ? AND gebruiker_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $start_date, $end_date, $gebruiker_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $eventTitles[$i] = $row['event_title'];
    } else {
        $eventTitles[$i] = 'Onbeschikbaar';
    }
}

$html = '<div class="calendar">';
for ($i = 1; $i <= $numDays; $i++) {
    if ($i == 1 || ($i - 1) % 7 == 0) {
        $html .= '<div class="row">';
    }
    if ($i == 1) {
        for ($j = 1; $j < $firstDayOfWeek; $j++) {
            if ($eventTitles[$i] === 'Beschikbaar') {
                $html .= '<div id="day-box" class="date"></div>';
            } else {
                $html .= '<div id="selected" class="date"></div>';
            }
        }
    }
    if ($eventTitles[$i] === 'Beschikbaar') {
        $html .= '<div style="background-color: green;">' . $i . '</div>';
    } else {
        $html .= '<div style="background-color: red;">' . $i . '</div>';
    }
    if ($i == $numDays || ($i + $firstDayOfWeek - 1) % 7 == 0) {
        $html .= '</div>';
    }
}
$html .= '</div>';

echo $html;
$conn->close();
?>
