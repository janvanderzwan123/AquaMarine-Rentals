<?php
include 'database.php';

// Initialize filter variables
$datum = $_GET['datum'] ?? '';
$locatie = $_GET['locatie'] ?? '';
$type_boot = $_GET['type-boot'] ?? '';
$vermogen = $_GET['vermogen'] ?? '';
$lengte = $_GET['lengte'] ?? '';
$snelheid = $_GET['snelheid'] ?? '';
$passagiers = $_GET['passagiers'] ?? '';

// Build the SQL query based on filter parameters
$sql = "SELECT * FROM advertenties WHERE 1=1";
if (!empty($datum)) {
    $sql .= " AND datum = '$datum'";
}
if (!empty($locatie)) {
    $sql .= " AND locatie = '$locatie'";
}
if (!empty($type_boot)) {
    $sql .= " AND type_boot = '$type_boot'";
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
if (isset($_GET['reset'])) {
    session_unset();
    session_start();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
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
