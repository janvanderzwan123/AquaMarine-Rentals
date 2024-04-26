<?php
echo "<div>voor database</div>";
include 'database.php';
echo "<div>na database</div>";
echo "<div>voor calendar</div>";
// include 'display_calendar.php';
echo "<div>na calendar</div>";


// $datum = $_GET['datum'] ?? '';
// $locatie = $_GET['locatie'] ?? '';
// $type_boot = $_GET['boot_type'] ?? '';
// $vermogen = $_GET['vermogen'] ?? '';
// $lengte = $_GET['lengte'] ?? '';
// $snelheid = $_GET['snelheid'] ?? '';
// $passagiers = $_GET['passagiers'] ?? '';
// $search = $_GET['search'] ?? '';

// $conditions = [];
// $params = [];

// if ($datum) {
//     $conditions[] = "datum = ?";
//     $params[] = $datum;
// }
// if ($locatie) {
//     $conditions[] = "locatie = ?";
//     $params[] = $locatie;
// }
// if ($type_boot) {
//     $conditions[] = "boot_type = ?";
//     $params[] = $type_boot;
// }
// if ($vermogen) {
//     $conditions[] = "vermogen = ?";
//     $params[] = $vermogen;
// }
// if ($lengte) {
//     $conditions[] = "lengte = ?";
//     $params[] = $lengte;
// }
// if ($snelheid) {
//     $conditions[] = "snelheid = ?";
//     $params[] = $snelheid;
// }
// if ($passagiers) {
//     $conditions[] = "passagiers = ?";
//     $params[] = $passagiers;
// }
// if ($search) {
//     $conditions[] = "(boot_naam LIKE CONCAT('%', ?, '%') OR boot_type LIKE CONCAT('%', ?, '%') OR locatie LIKE CONCAT('%', ?, '%'))";
//     $params[] = $search;
//     $params[] = $search;
//     $params[] = $search;
// }
echo "<div>voor statement</div>";

$sql = "SELECT * FROM advertenties";
echo "<div>na statement</div>";

// if (!empty($conditions)) {
//     $sql .= " WHERE " . implode(' AND ', $conditions);
// }
$stmt = $conn->prepare($sql);
echo "<div>1</div>";
$typeString = str_repeat('s', count($params)); // Create a string with a number of 's' characters equal to the number of parameters
echo "<div>2</div>";
$stmt->bind_param($typeString, ...$params);
echo "<div>3</div>";
$stmt->execute();
echo "<div>4</div>";
$result = $stmt->get_result();
echo "<div>na result</div>";


if ($result->num_rows > 0) {
    // Loop through each row and display boat listings
    while ($row = $result->fetch_assoc()) {
        echo '<a href="detail_pagina.php?advertentie_id=' . $row['advertentie_id'] . '" style="text-decoration: none;"><div class="boat-listing">';
        
        // Display boat image
        $photoIdArray = explode(',', $row["photo_id"]);
        $firstPhotoId = $photoIdArray[0];
        $photoSql = "SELECT link FROM foto_links WHERE foto_id = $firstPhotoId LIMIT 1";
        $photoResult = $conn->query($photoSql);
        if ($photoResult->num_rows > 0) {
            $photoRow = $photoResult->fetch_assoc();
            $photoLink = $photoRow['link'];
            echo '<div class="boat-image"><img src="' . $photoLink . '" alt="Boat Image"></div>'; 
        } else {
            echo '<div class="boat-image"><img src="placeholder.png" alt="Boat Image"></div>'; 
        }

        // Display boat details
        echo '<div class="boat-details">';
        echo '<h2>' . $row["boot_naam"] . '</h2>';
        echo '<p>Type: ' . $row["boot_type"] . '</p>';
        echo '<p>Locatie: ' . $row["locatie"] . '</p>';
        echo '<p>Prijs per dag: €' . $row["prijs_per_dag"] . '</p>';
        echo '</div>';

        // Display calendar for the boat
        // echo displayCalendar($conn, $row['advertentie_id']);

        echo '</div></a>';
    }
} else {
    echo "Geen boten gevonden";
}



$conn->close();
?>
