<?php
include 'database.php'; // Make sure your database connection is correctly set up

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data with added escaping for security
    $boot_naam = $conn->real_escape_string($_POST['boot_naam']);
    $boot_type = $conn->real_escape_string($_POST['boot_type']);
    $locatie = $conn->real_escape_string($_POST['locatie']);
    $vermogen = $conn->real_escape_string($_POST['vermogen']);
    $lengte = $conn->real_escape_string($_POST['lengte']);
    $prijs_per_dag = $conn->real_escape_string($_POST['prijs_per_dag']);

    // Prepare SQL statement to insert advertisement data
    $stmt = $conn->prepare("INSERT INTO advertenties (boot_naam, boot_type, locatie, vermogen, lengte, prijs_per_dag) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiid", $boot_naam, $boot_type, $locatie, $vermogen, $lengte, $prijs_per_dag);

    // Execute SQL statement
    if ($stmt->execute()) {
        // Get the ID of the newly inserted advertisement
        $advertentie_id = $conn->insert_id;

        // Handle photo uploads
        if (!empty($_FILES['photos']['name'][0])) {
            $target_dir = "images/";
            foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['photos']['name'][$key];
                $file_tmp = $_FILES['photos']['tmp_name'][$key];
                $newFilePath = $target_dir . basename($file_name);
                if (move_uploaded_file($file_tmp, $newFilePath)) {
                    // Insert photo link into foto_links after uploading
                    $photoSql = $conn->prepare("INSERT INTO foto_links (foto_id, link) VALUES (?, ?)");
                    $photoSql->bind_param("is", $advertentie_id, $newFilePath);
                    $photoSql->execute();
                }
            }
        }

        // Redirect to profile.php after successful submission
        header("Location: profile.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Invalid Request";
}

// Close database connection and statement
$stmt->close();
$conn->close();
?>
