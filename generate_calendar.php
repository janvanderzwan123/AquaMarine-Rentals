<?php
include 'database.php';
session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

function generateCalendar($numDays, $firstDayOfWeek, $eventTitles, $advertentie_id) {
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
