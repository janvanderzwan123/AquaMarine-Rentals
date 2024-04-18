<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

// Get the username from the session
$username = $_SESSION['username'];

// Your database connection code here

// Get the gebruiker_id for the current user
$sql = "SELECT gebruiker_id FROM gebruikers WHERE gebruikersnaam = '$username'";
$result = $conn->query($sql);

// Check if the query was successful
if ($result->num_rows > 0) {
    // Fetch the row to get the gebruiker_id
    $row = $result->fetch_assoc();
    $gebruiker_id = $row['gebruiker_id'];
} else {
    // Handle the case where the user is not found
    // You may redirect the user or show an error message
    exit("User not found");
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected dates from the form
    $selectedDates = $_POST['selected_dates'];

    // Update the event titles in the database
    foreach ($selectedDates as $date) {
        // Check if the date should be beschikbaar or onbeschikbaar
        $eventTitle = ($_POST['event_title_' . $date] === 'Beschikbaar') ? 'Beschikbaar' : 'onbeschikbaar';

        // Prepare and execute the SQL statement to update the event title
        $sql = "UPDATE verhuurder_calendar SET event_title = '$eventTitle' WHERE start_date = '$year-$month-$date' AND user_id = '$gebruiker_id'";
        $conn->query($sql);
    }

    // Redirect back to the calendar page
    header("Location: calendar.php");
    exit();
}

// Close database connection
$conn->close();
?>
