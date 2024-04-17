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

    <!-- Display editable calendar for verhuurder users -->
    <?php if ($role === 'verhuurder'): ?>
        <h3>Jouw kalender:</h3>
        <div id="calendar"></div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
