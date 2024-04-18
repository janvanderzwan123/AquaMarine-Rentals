<?php
include 'header.php';

session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

$sql = "SELECT a.*
        FROM advertenties a 
        INNER JOIN gebruikers g ON a.verhuurder_id = g.gebruiker_id 
        WHERE g.gebruikersnaam = '$username'";
$result = $conn->query($sql);

$advertisements = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $advertisements[] = $row;
    }
}

?>

<div class="container mt-5">
    <h2>Welkom, <?php echo $username; ?>!</h2><br>
    <a href="new_listing.php" class="btn btn-primary">Nieuwe advertentie</a><br>
    <h3>Jouw advertenties:</h3><br>
    <div class="row">
        <?php if (!empty($advertisements)): ?>
            <?php foreach ($advertisements as $advertisement): ?>
                <div id="profiel-advertentie" class="col-md-4">
                    <a href="detail_pagina.php?advertentie_id=<?php echo $advertisement['advertentie_id']; ?>" style="text-decoration: none;" class="card mb-4 profiel-advertentie">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $advertisement["boot_naam"]; ?></h5>
                            <p class="card-text">Type: <?php echo $advertisement["boot_type"]; ?></p>
                            <p class="card-text">Locatie: <?php echo $advertisement["locatie"]; ?></p>
                        </div>
                    </a>
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
                    include 'generate_calendar.php';
                    ?>
                </div>
                <button class="btn btn-primary save-button">Kalender opslaan</button>
            </div>
        </div><br><br>
    <?php endif; ?>
</div>



<?php include 'footer.php'; ?>
