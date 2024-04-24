<?php
include 'database.php';
include 'display_calendar.php';

$datum = isset($_GET['datum']) ? $_GET['datum'] : '';
$locatie = isset($_GET['locatie']) ? $_GET['locatie'] : '';
$type_boot = isset($_GET['boot_type']) ? $_GET['boot_type'] : '';
$vermogen = isset($_GET['vermogen']) ? $_GET['vermogen'] : '';
$lengte = isset($_GET['lengte']) ? $_GET['lengte'] : '';
$snelheid = isset($_GET['snelheid']) ? $_GET['snelheid'] : '';
$passagiers = isset($_GET['passagiers']) ? $_GET['passagiers'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM advertenties";
if (!empty($datum)) {
    $sql .= " AND datum = '$datum'";
}
if (!empty($locatie)) {
    $sql .= " AND locatie = '$locatie'";
}
if (!empty($type_boot)) {
    $sql .= " AND boot_type = '$type_boot'";
}
if (!empty($vermogen)) {
    $sql .= " AND vermogen = '$vermogen'";
}
if (!empty($lengte)) {
    $sql .= " AND lengte = '$lengte'";
}
if (!empty($snelheid)) {
    $sql .= " AND snelheid = '$snelheid'";
}
if (!empty($passagiers)) {
    $sql .= " AND passagiers = '$passagiers'";
}
if (!empty($search)) {
    $sql .= " AND (boot_naam LIKE '%$search%' OR boot_type LIKE '%$search%' OR locatie LIKE '%$search%')";
}

$result = $conn->query($sql);


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
        echo displayCalendar($conn, $row['advertentie_id']);

        echo '</div></a>';
    }
} else {
    echo "Geen boten gevonden";
}



$conn->close();
?>
