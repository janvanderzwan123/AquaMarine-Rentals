<?php
include 'database.php';

$sql = "SELECT * FROM advertenties WHERE 1=1";

if (!empty($_GET['datum'])) {
    $datum = $_GET['datum'];
    $sql .= " AND datum = '$datum'";
}

if (!empty($_GET['locatie'])) {
    $locatie = $_GET['locatie'];
    $sql .= " AND locatie = '$locatie'";
}

if (!empty($_GET['type-boot'])) {
    $typeBoot = $_GET['type-boot'];
    $sql .= " AND boot_type = '$typeBoot'";
}

if (!empty($_GET['vermogen'])) {
    $vermogen = $_GET['vermogen'];
    $sql .= " AND vermogen = '$vermogen'";
}

if (!empty($_GET['lengte'])) {
    $lengte = $_GET['lengte'];
    $sql .= " AND lengte = '$lengte'";
}

if (!empty($_GET['snelheid'])) {
    $snelheid = $_GET['snelheid'];
    $sql .= " AND snelheid = '$snelheid'";
}

if (!empty($_GET['passagiers'])) {
    $passagiers = $_GET['passagiers'];
    $sql .= " AND aantal_passagiers = '$passagiers'";
}

if (!empty($_GET['search'])) {
    $search = $_GET['search'];
    $sql .= " AND (boot_naam LIKE '%$search%' OR boot_type LIKE '%$search%' OR locatie LIKE '%$search%')";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<a href="detail_pagina.php?advertentie_id='. $row['advertentie_id'] .'" style="text-decoration: none;"><div class="boat-listing">';
        
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

        echo '<div class="boat-details">';
        echo '<h2>' . $row["boot_naam"] . '</h2>';
        echo '<p>Type: ' . $row["boot_type"] . '</p>';
        echo '<p>Locatie: ' . $row["locatie"] . '</p>';
        echo '</div>';
        echo '<div class="boat-calendar">Calendar Icon</div>';
        echo '</div></a>';
    }
} else {
    echo "Geen boten gevonden";
}

$conn->close();

?>
