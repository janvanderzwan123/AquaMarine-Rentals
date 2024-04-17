<?php
include 'header.php'; 
include 'database.php';

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the advertentie_id from the URL
$advertentie_id = isset($_GET['advertentie_id']) ? intval($_GET['advertentie_id']) : 0;

if ($advertentie_id === 0) {
    echo '<p>Invalid advertentie ID.</p>';
    include 'footer.php';
    exit;
}

// Fetch boat details from the database based on advertentie_id
$boot_details_sql = "SELECT * FROM advertenties WHERE advertentie_id = $advertentie_id";
$boot_details_result = $conn->query($boot_details_sql);

if (!$boot_details_result) {
    echo "Error: " . $conn->error;
    include 'footer.php';
    exit;
}

// Fetch foto_ids associated with the advertentie_id
$foto_ids_sql = "SELECT foto_id FROM foto_links WHERE advertentie_id = $advertentie_id";
$foto_ids_result = $conn->query($foto_ids_sql);
$photo_urls = [];

// Gather all foto_urls based on foto_ids
if ($foto_ids_result && $foto_ids_result->num_rows > 0) {
    while ($foto_id = $foto_ids_result->fetch_assoc()) {
        $foto_url_sql = "SELECT link FROM foto_links WHERE foto_id = " . $foto_id['foto_id'];
        $foto_url_result = $conn->query($foto_url_sql);
        if ($foto_url_result && $foto_url_result->num_rows > 0) {
            while ($foto = $foto_url_result->fetch_assoc()) {
                $photo_urls[] = $foto['link'];
            }
        }
    }
}
?>

<main>
    <div class="container mt-5">
        <div class="row">
            <!-- Back Button -->
            <div class="col-md-12 mb-3">
                <a href="index.php" class="btn btn-secondary">Terug</a>
            </div>
            <!-- Boat Photos -->
            <div class="col-md-6">
                <div class="boat-photos">
                    <?php
                    if (!empty($photo_urls)) {
                        foreach ($photo_urls as $url) {
                            echo "<img src='" . htmlspecialchars($url) . "' class='img-fluid mb-3'>";
                        }
                    } else {
                        echo "<p>Geen foto's beschikbaar.</p>";
                    }
                    ?>
                </div>
            </div>
            <!-- Boat Details -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <?php
                        if ($boot_details_result->num_rows > 0) {
                            $row = $boot_details_result->fetch_assoc();
                            echo "<h3>" . htmlspecialchars($row['boot_naam']) . "</h3>";
                            echo "<p>Type: " . htmlspecialchars($row['boot_type']) . "</p>";
                            // Display other boat details as needed
                        } else {
                            echo "<p>Bootgegevens niet gevonden.</p>";
                        }
                        ?>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary btn-block">Reserveer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
