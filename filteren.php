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
        echo '<div class="col-md-4">';
        echo '<a href="detail_pagina.php?advertentie_id='. $row['advertentie_id'] .'" class="card mb-4">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $row["boot_naam"] . '</h5>';
        echo '<p class="card-text">Type: ' . $row["boot_type"] . '</p>';
        echo '<p class="card-text">Locatie: ' . $row["locatie"] . '</p>';
        echo '</div>';
        echo '</div></a>';
    }
} else {
    echo "<p>Geen advertenties gevonden.</p>";
}

$conn->close();

?>
