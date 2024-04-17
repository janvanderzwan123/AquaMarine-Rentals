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

// Initialize variables
$advertisements = [];
$calendarEvents = [];
$errors = [];

// Database connection
include 'database.php';

// Fetch user's advertisements from the database
try {
    $sql = "SELECT a.*
            FROM advertenties a 
            INNER JOIN gebruikers g ON a.verhuurder_id = g.gebruiker_id 
            WHERE g.gebruikersnaam = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are advertisements
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $advertisements[] = $row;
        }
    }
} catch (Exception $e) {
    $errors[] = "Error fetching advertisements: " . $e->getMessage();
}

// Fetch events from the verhuurder's calendar
if ($role === 'verhuurder') {
    try {
        $sql_calendar = "SELECT * FROM verhuurder_calendar WHERE user_id = (SELECT gebruiker_id FROM gebruikers WHERE gebruikersnaam = ?)";
        $stmt_calendar = $conn->prepare($sql_calendar);
        $stmt_calendar->bind_param("s", $username);
        $stmt_calendar->execute();
        $result_calendar = $stmt_calendar->get_result();

        // Check if there are calendar events
        if ($result_calendar->num_rows > 0) {
            while ($row = $result_calendar->fetch_assoc()) {
                $calendarEvents[] = $row;
            }
        }
    } catch (Exception $e) {
        $errors[] = "Error fetching calendar events: " . $e->getMessage();
    }
}

?>

<div class="container mt-5">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
            <?php foreach ($errors as $error): ?>
                <?php echo $error; ?><br>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
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
        <?php echo 'voor calendar'; ?>
        <?php if ($role === 'verhuurder'): ?>
            <h3>Jouw kalender:</h3>
            <div id="calendar"></div>
            <?php echo 'in kalender'; ?>
        <?php endif; ?>
        <?php echo 'na kalender'; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
