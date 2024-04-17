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

?>

<div class="container mt-5">
    <h2>Welkom, <?php echo $username; ?>!</h2>
    
    <!-- Display user's advertisements -->
    <h3>Jouw advertenties:</h3>
    <div class="row">
        <?php if (!empty($advertisements)): ?>
            <?php foreach ($advertisements as $advertisement): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $advertisement["boot_naam"]; ?></h5>
                            <p class="card-text">Type: <?php echo $advertisement["boot_type"]; ?></p>
                            <p class="card-text">Locatie: <?php echo $advertisement["locatie"]; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Je hebt nog geen advertenties.</p>
        <?php endif; ?>
    </div>

    <?php if ($role === 'verhuurder'): ?>
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

                // Output the day as a clickable date box
                echo '<div class="day"><div class="day-box">' . $i . '</div></div>';

                // Check if it's the last day of the week or the last day of the month
                if (($i % 7 === 0 && $i !== $daysInMonth) || $i === $daysInMonth) {
                    echo '</div>'; // Close the week div
                }
            }
            ?>
        </div>
        <button class="btn btn-primary save-button">Kalender opslaan</button>
    </div>
</div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
