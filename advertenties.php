<?php
include 'database.php';

$sql = "SELECT * FROM advertenties";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h2>" . $row["boot_naam"] . "</h2>";
        echo "<p>Type: " . $row["boot_type"] . "</p>";
        echo "<p>Locatie: " . $row["locatie"] . "</p>";
        echo "<p>Vermogen: " . $row["vermogen"] . "</p>";
        echo "<p>Lengte: " . $row["lengte"] . "</p>";
        echo "<p>Snelheid: " . $row["snelheid"] . "</p>";
        echo "<p>Aantal passagiers: " . $row["aantal_passagiers"] . "</p>";
        echo "</div>";
    }
} else {
    echo "Geen boten gevonden";
}

$conn->close();
?>
