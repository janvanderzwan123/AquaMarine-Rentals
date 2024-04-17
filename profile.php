<?php
include 'database.php';
include 'header.php';


session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
$role = $_SESSION['role'];

$sql = "SELECT a.*, g.gebruikersnaam 
        FROM advertenties a 
        INNER JOIN gebruikers g ON a.verhuurder_id = g.gebruiker_id 
        WHERE g.gebruikersnaam = '$username'";
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
                            <h5 class="card-title"><?php echo $row["boot_naam"]; ?></h5>
                            <p class="card-text">Type: <?php echo $row["boot_type"]; ?></p>
                            <p class="card-text">Locatie: <?php echo $row["locatie"]; ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Je hebt nog geen advertenties.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
