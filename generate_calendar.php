<?php
// Your database connection code here
include 'database.php';
// Get current month and year
$month = date('n');
$year = date('Y');

// Get number of days in the month
$numDays = date('t', mktime(0, 0, 0, $month, 1, $year));

// Get the first day of the week (1 = Monday, 7 = Sunday)
$firstDayOfWeek = date('N', mktime(0, 0, 0, $month, 1, $year));

// Initialize an array to store event titles for each day
$eventTitles = [];

// Query to get event titles for each day of the month
for ($i = 1; $i <= $numDays; $i++) {
    // SQL to get event title for the current day
    $sql = "SELECT event_title FROM verhuurder_calendar WHERE start_date = '$year-$month-$i' AND user_id = <insert_user_id_here>";

    // Execute the query
    $result = $conn->query($sql);

    // Check if there is a result
    if ($result->num_rows > 0) {
        // Fetch the event title
        $row = $result->fetch_assoc();
        $eventTitles[$i] = $row['event_title'];
    } else {
        // If no event title is found, set it to 'onbeschikbaar' by default
        $eventTitles[$i] = 'onbeschikbaar';
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
            if ($eventTitles[$i] === 'Beschikbaar') {
                $html .= '<div id="day-box" class="date"></div>';
            } else {
                $html .= '<div id="selected" class="date"></div>';
            }
        }
    }

    // Add a date box for the current day
    if ($eventTitles[$i] === 'Beschikbaar') {
        $html .= '<label><input type="checkbox" name="selected_dates[]" value="' . $i . '" checked>' . $i . '</label>';
    } else {
        $html .= '<label><input type="checkbox" name="selected_dates[]" value="' . $i . '">' . $i . '</label>';
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

// Close database connection
$conn->close();
?>
