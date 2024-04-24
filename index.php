<?php
include 'header.php';
include 'database.php';
include 'display_calendar.php';

// Retrieve search parameters from the URL
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Construct the SQL query based on search parameters
$sql = "SELECT * FROM advertenties WHERE 1 = 1"; // Start with a condition that is always true

if (!empty($search)) {
    $sql .= " AND (boot_naam LIKE '%$search%' OR boot_type LIKE '%$search%' OR locatie LIKE '%$search%')";
}

// Execute the SQL query
$result = $conn->query($sql);

?>

<main>
    <div class="container">
        <div class="row mb-4" style="margin-top: 4%;">
            <div class="col-md-12">
                <form action="index.php" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Zoeken...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">Zoeken</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <?php
            // Check if any results were found
            if ($result->num_rows > 0) {
                // Loop through each row and display boat listings
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-4">';
                    echo '<a href="detail_pagina.php?advertentie_id=' . $row['advertentie_id'] . '" style="text-decoration: none;">';
                    echo '<div class="boat-listing">';
                    
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

                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo '<div class="col-md-12">';
                echo "Geen boten gevonden";
                echo '</div>';
            }
            ?>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>

<?php
// Close the database connection
$conn->close();
?>
