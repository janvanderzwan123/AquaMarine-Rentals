<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $emailadres = $_POST['emailadres'];
    $wachtwoord = $_POST['wachtwoord'];
    $wachtwoord_herhalen = $_POST['wachtwoord_herhalen'];


    if ($wachtwoord !== $wachtwoord_herhalen) {
        include 'register_error.php';
    }

    $sql = "INSERT INTO gebruikers (gebruikersnaam, emailadres, wachtwoord, rol_id) 
            VALUES ('$gebruikersnaam', '$emailadres', '$wachtwoord', (SELECT rol_id FROM rollen WHERE rol_naam = '$role'))";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success' role='alert'>Registratie gelukt!</div>";
        echo "<script>setTimeout(function() { window.location.href = 'index.php'; }, 3000);</script>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }

    $conn->close();
}
?>
