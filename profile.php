<?php
include 'header.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    // Redirect user to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get user details from session or database
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Fetch user's advertisements from the database
$sql = "SELECT * FROM advertenties WHERE verhuurder_id = (SELECT gebruiker_id FROM gebruikers WHERE gebruikersnaam = '$username')";
$result = $conn->query($sql);

?>

    <div class="container mt-5">
        <h2>Welkom, <?php echo $username; ?>!</h2>
        <h3>Jouw advertenties:</h3>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row["boat_naam"]; ?></h5>
                                <p class="card-text">Type: <?php echo $row["boot_type"]; ?></p>
                                <p class="card-text">Locatie: <?php echo $row["locatie"]; ?></p>
                                <!-- Add more details as needed -->
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Je hebt nog geen advertenties.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
