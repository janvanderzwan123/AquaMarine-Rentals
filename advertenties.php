<?php
include 'database.php';

// Alle boten ophalen uit de database
$sql = "SELECT * FROM advertenties";
$result = $conn->query($sql);

// Controleren of er boten zijn
if ($result->num_rows > 0) {
    // Door elke rij lopen en bootgegevens weergeven
    while($row = $result->fetch_assoc()) {
        echo '<div class="boat-listing">';
        
        // Fetching the first photo link based on the first photo_id
        $photoIdArray = explode(',', $row["photo_id"]); // Splitting the photo_id string into an array
        $firstPhotoId = $photoIdArray[0]; // Getting the first photo_id
        $photoSql = "SELECT link FROM foto_links WHERE foto_id = $firstPhotoId LIMIT 1";
        $photoResult = $conn->query($photoSql);
        if ($photoResult->num_rows > 0) {
            $photoRow = $photoResult->fetch_assoc();
            $photoLink = $photoRow['link'];
            echo '<div class="boat-image"><img src="' . $photoLink . '" alt="Boat Image"></div>'; // Displaying the first photo
        } else {
            echo '<div class="boat-image"><img src="placeholder.jpg" alt="Boat Image"></div>'; // Placeholder image if no photo found
        }

        echo '<div class="boat-details">';
        echo '<h2>' . $row["boot_naam"] . '</h2>';
        echo '<p>Type: ' . $row["boot_type"] . '</p>';
        echo '<p>Locatie: ' . $row["locatie"] . '</p>';
        // Add more details as needed
        echo '</div>';
        echo '<div class="boat-calendar">Calendar Icon</div>'; // Placeholder for calendar icon
        echo '</div>';
    }
} else {
    echo "Geen boten gevonden";
}

// Databaseverbinding sluiten
$conn->close();
?>
