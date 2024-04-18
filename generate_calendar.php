<?php
// Get current month and year
$month = date('n');
$year = date('Y');

// Get number of days in the month
$numDays = date('t', mktime(0, 0, 0, $month, 1, $year));

// Get the first day of the week (1 = Monday, 7 = Sunday)
$firstDayOfWeek = date('N', mktime(0, 0, 0, $month, 1, $year));

// Fetch event titles from the database
$eventTitles = []; // Placeholder for event titles

// SQL statement to fetch event titles
// Replace 'your_condition_here' with your specific condition
$sql = "SELECT event_title FROM verhuurder_calendar WHERE your_condition_here";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $eventTitles[] = $row['event_title'];
    }
}

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
            if (isset($eventTitles[$i]) && $eventTitles[$i] === 'Beschikbaar') {
                $html .= '<div id="day-box" class="date"></div>';
            } else {
                $html .= '<div id="selected" class="date"></div>';
            }
        }
    }

    // Add a date box for the current day
    $html .= '<label><input type="checkbox" name="selected_dates[]" value="' . $i . '">' . $i . '</label>';

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
