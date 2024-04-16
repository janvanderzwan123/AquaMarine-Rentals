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
        echo '<div class="boat-image"><img src="placeholder.jpg" alt="Boat Image"></div>'; // Placeholder image
        echo '<div class="boat-details">';
        echo '<h2>' . $row["boot_naam"] . '</h2>';
        echo '<p>Type: ' . $row["boot_type"] . '</p>';
        echo '<p>Locatie: ' . $row["locatie"] . '</p>';
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
