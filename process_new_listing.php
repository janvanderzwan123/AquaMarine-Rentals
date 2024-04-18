<?php
include 'database.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $boot_naam = $_POST['boot_naam'];
    $boot_type = $_POST['boot_type'];
    $locatie = $_POST['locatie'];
    $vermogen = $_POST['vermogen'];
    $prijs_per_dag = $_POST['prijs_per_dag']; // Added line

    // Prepare SQL statement to insert advertisement data
    $sql = "INSERT INTO advertenties (boot_naam, boot_type, locatie, vermogen, prijs_per_dag) 
            VALUES ('$boot_naam', '$boot_type', '$locatie', '$vermogen', '$prijs_per_dag')"; // Modified line

    // Execute SQL statement
    if ($conn->query($sql) === TRUE) {
        // Get the ID of the newly inserted advertisement
        $advertentie_id = $conn->insert_id;

        // Handle photo uploads
        // Code for photo uploads goes here

        // Redirect to profile.php after successful submission
        header("Location: profile.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close database connection
$conn->close();
?>
