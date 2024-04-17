<?php
include 'database.php';

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet"> <!-- Pacifico font -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Logo -->
            <div>
                <img src="/images/AMR.png" alt="Logo" style="height: 5rem; margin-left: -6rem;">
            </div>
            <h1 class="m-0">Aqua Marine Rentals</h1>
            <!-- Profile icon with link -->
            <a href="logout.php" class="text-dark ml-3">Uitloggen</a> <!-- Logout button -->
        </div>
    </header>

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
