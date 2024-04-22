<?php
include 'database.php';
session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['advertentie_id'])) {
    header("Location: index.php");
    exit();
}

$advertentie_id = $_GET['advertentie_id'];

$sql = "SELECT advertentie_id FROM advertenties WHERE advertentie_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $advertentie_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    initializeCalendar($conn, $advertentie_id);
    $numDays = date('t');
    $firstDayOfWeek = date('N', mktime(0, 0, 0, date('n'), 1, date('Y')));

    $eventTitles = [];
    for ($i = 1; $i <= $numDays; $i++) {
        $start_date = date('Y-m-d', mktime(0, 0, 0, date('n'), $i, date('Y')));
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

    // Generate the HTML for the calendar
    $calendarHTML = generateCalendar($numDays, $firstDayOfWeek, $eventTitles);
} else {
    header("Location: login.php");
    exit();
}

function initializeCalendar($conn, $advertentieID) {
    $month = date('n');
    $year = date('Y');
    $numDays = date('t', mktime(0, 0, 0, $month, 1, $year));

    $sql_check_calendar = "SELECT COUNT(*) AS num_rows FROM verhuurder_calendar WHERE advertentie_id = ?";
    $stmt_check_calendar = $conn->prepare($sql_check_calendar);
    $stmt_check_calendar->bind_param("i", $advertentieID);
    $stmt_check_calendar->execute();
    $result_check_calendar = $stmt_check_calendar->get_result();
    $row_check_calendar = $result_check_calendar->fetch_assoc();
    $num_rows_calendar = $row_check_calendar['num_rows'];

    if ($num_rows_calendar == 0) {
        for ($i = 1; $i <= $numDays; $i++) {
            $date = date('Y-m-d', mktime(0, 0, 0, $month, $i, $year));
            $sql = "INSERT INTO verhuurder_calendar (start_date, end_date, event_title, advertentie_id) VALUES (?, ?, 'Onbeschikbaar', ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $date, $date, $advertentieID);
            $stmt->execute();
        }
    }
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
?>
