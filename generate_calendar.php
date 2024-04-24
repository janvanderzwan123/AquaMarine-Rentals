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

function initializeCalendar($conn, $advertentieID) {
    $month = date('n');
    $year = date('Y');
    $numDays = date('t', mktime(0, 0, 0, $month, 1, $year));


    $startOfMonth = date('Y-m-01', mktime(0, 0, 0, $month, 1, $year));
    $endOfMonth = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));

    $sql_check_calendar = "SELECT COUNT(*) AS num_rows FROM verhuurder_calendar WHERE advertentie_id = ? AND start_date BETWEEN ? AND ?";
    $stmt_check_calendar = $conn->prepare($sql_check_calendar);
    $stmt_check_calendar->bind_param("iss", $advertentieID, $startOfMonth, $endOfMonth);
    $stmt_check_calendar->execute();
    $result_check_calendar = $stmt_check_calendar->get_result();
    $row_check_calendar = $result_check_calendar->fetch_assoc();

    if ($row_check_calendar['num_rows'] == 0) {
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
    $advertentie_id = $_GET['advertentie_id'];
    $html = "<form action='save_calendar.php?advertentie_id=" . $advertentie_id . "' method='post'><div class='calendar'>";

    for ($i = 1; $i <= $numDays; $i++) {
        if ($i == 1 || ($i + $firstDayOfWeek - 2) % 7 == 0) {
            $html .= '<div class="row">';
        }

        $backgroundColor = ($eventTitles[$i] === 'Beschikbaar') ? 'green' : 'red';
        $checkedAttribute = ($eventTitles[$i] === 'Beschikbaar') ? 'checked' : '';

        $html .= "<label style='background-color: $backgroundColor;'><input type='checkbox' name='selected_dates[]' value='$i' $checkedAttribute>$i</label>";

        if ($i == $numDays || ($i + $firstDayOfWeek - 1) % 7 == 0) {
            $html .= '</div>';
        }
    }
    $html .= "</div><button type='submit' class='btn btn-primary save-button'>Kalender opslaan</button></form>";
    return $html;
}

?>
