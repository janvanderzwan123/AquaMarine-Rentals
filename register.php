<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $emailadres = $_POST['emailadres'];
    $wachtwoord = $_POST['wachtwoord'];
    $wachtwoord_herhalen = $_POST['wachtwoord_herhalen'];


    $sql = "INSERT INTO gebruikers (gebruikersnaam, emailadres, wachtwoord, rol_id) 
            VALUES ('$gebruikersnaam', '$emailadres', '$wachtwoord', (SELECT rol_id FROM rol WHERE rol_naam = '$role'))";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
