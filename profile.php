<?php
include 'header.php';

session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

// Get username and role from session
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Fetch user's advertisements from the database
$sql = "SELECT a.*
        FROM advertenties a 
        INNER JOIN gebruikers g ON a.verhuurder_id = g.gebruiker_id 
        WHERE g.gebruikersnaam = '$username'";
$result = $conn->query($sql);

// Check if there are advertisements
$advertisements = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $advertisements[] = $row;
    }
}

// Fetch events from the verhuurder's calendar
$calendarEvents = [];
if ($role === 'verhuurder') {
    $sql_calendar = "SELECT * FROM verhuurder_calendar WHERE user_id = (SELECT gebruiker_id FROM gebruikers WHERE gebruikersnaam = '$username')";
    $result_calendar = $conn->query($sql_calendar);
    if ($result_calendar->num_rows > 0) {
        while ($row = $result_calendar->fetch_assoc()) {
            $calendarEvents[] = $row;
        }
    }
}

?>

<div class="container mt-5">
    <h2>Jouw kalender:</h2>
    <div class="calendar">
        <div class="header">
            <div class="month-year">Maand Jaar</div>
            <div class="days-of-week">
                <div>Zo</div>
                <div>Ma</div>
                <div>Di</div>
                <div>Wo</div>
                <div>Do</div>
                <div>Vr</div>
                <div>Za</div>
            </div>
        </div>
        <div class="days">
            <?php
            // Get the current month and year
            $currentMonth = date('n');
            $currentYear = date('Y');

            // Get the number of days in the current month
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

            // Start the calendar on the first day of the month
            $startDay = 1;

            // Loop through each day of the month
            for ($i = 1; $i <= $daysInMonth; $i++) {
                // Check if it's the first day of the week
                if ($i === $startDay) {
                    echo '<div class="week">';
                }

                // Output the day
                echo '<div class="day">' . $i . '</div>';

                // Check if it's the last day of the week or the last day of the month
                if (($i % 7 === 0 && $i !== $daysInMonth) || $i === $daysInMonth) {
                    echo '</div>'; // Close the week div
                }
            }
            ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
