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
    $sql = "SELECT event_title FROM verhuurder_calendar WHERE start_date = '$year-$month-$i' AND user_id = '$gebruiker_id'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $eventTitles[$i] = $row['event_title'];
    } else {
        $eventTitles[$i] = 'onbeschikbaar';
    }
}

$html = '<form action="save_calendar.php" method="post"><div class="calendar">';

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
        $html .= '<label style="background-color: green;"><input type="checkbox" name="selected_dates[]" value="' . $i . '" checked>' . $i . '</label>';
    } else {
        $html .= '<label style="background-color: red;"><input type="checkbox" name="selected_dates[]" value="' . $i . '">' . $i . '</label>';
    }
    if ($i == $numDays || ($i + $firstDayOfWeek - 1) % 7 == 0) {
        $html .= '</div>';
    }
}

$html .= '</div><button type="submit" class="btn btn-primary save-button">Kalender opslaan</button></form>';

echo $html;

$conn->close();
?>
