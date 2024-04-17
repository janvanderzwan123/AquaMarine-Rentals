<?php
session_start();

include 'database.php';

$username = $_POST['gebruikersnaam'];
$password = $_POST['wachtwoord'];
$role = $_POST['role'];

$sql = "SELECT * FROM gebruikers WHERE gebruikersnaam = '$username' AND wachtwoord = '$password' AND rol_id = (SELECT rol_id FROM rollen WHERE rol_naam = '$role')";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $_SESSION['loggedIn'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;

    header("Location: profile.php");
    exit();
} else {
    header("Location: login_error.php");
    exit();
}

$conn->close();
?>
