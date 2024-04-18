<?php
// Start generating HTML
$html = '<form action="save_calendar.php" method="post"><div class="calendar">';

for ($i = 1; $i <= $numDays; $i++) {
    // Start a new row at the beginning of the week or on the first day of the month
    if ($i == 1 || ($i - 1) % 7 == 0) {
        $html .= '<div class="row">';
    }

    // Add empty date boxes for days before the first day of the month
    if ($i == 1) {
        for ($j = 1; $j < $firstDayOfWeek; $j++) {
            // Add the day-box with appropriate ID based on event_title
            if ($eventTitle[$i] === 'Beschikbaar') {
                $html .= '<div id="day-box" class="date"></div>';
            } else {
                $html .= '<div id="selected" class="date"></div>';
            }
        }
    }

    // Add a date box for the current day
    if ($eventTitle[$i] === 'Beschikbaar') {
        $html .= '<label><input type="checkbox" id="day-box" name="selected_dates[]" value="' . $i . '">' . $i . '</label>';
    } else {
        $html .= '<label><input type="checkbox" id="selected" name="selected_dates[]" value="' . $i . '">' . $i . '</label>';
    }

    // Close the row at the end of the week or at the end of the month
    if ($i == $numDays || ($i + $firstDayOfWeek - 1) % 7 == 0) {
        $html .= '</div>';
    }
}

// Close the calendar form
$html .= '</div><button type="submit" class="btn btn-primary save-button">Kalender opslaan</button></form>';

// Output the generated HTML
echo $html;
?>
