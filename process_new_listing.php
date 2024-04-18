<?php
include 'database.php';  // Make sure your database connection file is correctly included

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $boot_naam = $_POST['boot_naam'];
    $boot_type = $_POST['boot_type'];
    $locatie = $_POST['locatie'];
    $vermogen = $_POST['vermogen'];
    $prijs_per_dag = $_POST['prijs_per_dag'];
    $lengte = $_POST['lengte'];
    $snelheid = $_POST['snelheid'];
    $aantal_passagiers = $_POST['aantal_passagiers'];
    $verhuurder_id = $_POST['verhuurder_id'];  // Assuming this comes from the form or session

    // Prepare SQL statement to insert advertisement data
    $sql = "INSERT INTO advertenties (boot_naam, boot_type, locatie, vermogen, prijs_per_dag, lengte, snelheid, aantal_passagiers, verhuurder_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    // Bind parameters
    $stmt->bind_param("sssiiiiii", $boot_naam, $boot_type, $locatie, $vermogen, $prijs_per_dag, $lengte, $snelheid, $aantal_passagiers, $verhuurder_id);
    
    // Execute SQL statement
    if ($stmt->execute()) {
        // Get the ID of the newly inserted advertisement
        $advertentie_id = $stmt->insert_id;

        // Handle photo uploads
        // Assuming you have a file input for photos, and photo processing code is separate
        include 'handle_photo_upload.php';  // This script should handle file uploads and linking to the ad

        // Redirect to profile.php after successful submission
        header("Location: profile.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>
