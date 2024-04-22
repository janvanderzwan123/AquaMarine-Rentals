<?php
include 'database.php';
session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
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
    $advertentie_id = $row['advertentie_id'];
} 

function generateCalendar($numDays, $firstDayOfWeek, $eventTitles) {
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
    return $html;
}


$month = date('n');
$year = date('Y');
$numDays = date('t', mktime(0, 0, 0, $month, 1, $year));

// Initialize calendar entries only for the specific boat
for ($i = 1; $i <= $numDays; $i++) {
    $start_date = date('Y-m-d', mktime(0, 0, 0, $month, $i, $year));
    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' + 24 hours'));

    $sql = "SELECT COUNT(*) AS num_rows FROM verhuurder_calendar WHERE start_date = ? AND end_date = ? AND advertentie_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $start_date, $end_date, $advertentie_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $rowCount = $row['num_rows'];

    if ($rowCount == 0) {
        $sql = "INSERT INTO verhuurder_calendar (start_date, end_date, event_title, advertentie_id) VALUES (?, ?, 'Onbeschikbaar', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $start_date, $end_date, $advertentie_id);
        $stmt->execute();
    }
}

// Retrieve calendar events only for the specific boat
$firstDayOfWeek = date('N', mktime(0, 0, 0, $month, 1, $year));
$eventTitles = [];

for ($i = 1; $i <= $numDays; $i++) {
    $start_date = date('Y-m-d', mktime(0, 0, 0, $month, $i, $year));
    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' + 24 hours'));

    $sql = "SELECT event_title FROM verhuurder_calendar WHERE start_date = ? AND end_date = ? AND advertentie_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $start_date, $end_date, $advertentie_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $eventTitles[$i] = $row['event_title'];
    } else {
        $eventTitles[$i] = 'Onbeschikbaar';
    }
}

?>
