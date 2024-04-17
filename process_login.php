<?php
// Start session
session_start();

// Include database connection
include 'database.php';

// Retrieve form data
$username = $_POST['gebruikersnaam'];
$password = $_POST['wachtwoord'];
$role = $_POST['role'];

// Query to check if user exists with provided credentials
$sql = "SELECT * FROM gebruikers WHERE gebruikersnaam = '$username' AND wachtwoord = '$password' AND rol_id = (SELECT rol_id FROM rollen WHERE rol_naam = '$role')";
$result = $conn->query($sql);

// Check if a matching user is found
if ($result->num_rows == 1) {
    // User authenticated successfully, set session variables
    $_SESSION['loggedIn'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;

    // Redirect user to dashboard or profile page
    header("Location: profile.php");
    exit();
} else {
    // Authentication failed, redirect to login error page or display error message
    header("Location: login_error.php");
    exit();
}

// Close database connection
$conn->close();
?>
