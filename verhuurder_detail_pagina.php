<?php
include 'header.php'; 
include 'generate_calendar.php';

// Get the advertentie_id from the URL
if(isset($_GET['advertentie_id'])) {
    $advertentie_id = $_GET['advertentie_id'];

    // Fetch boat details from the database based on advertentie_id
    $sql = "SELECT * FROM advertenties WHERE advertentie_id = $advertentie_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $boot_naam = $row['boot_naam'];
        $boot_type = $row['boot_type'];
        // Fetch other boat details as needed
    }

    // Fetch boat photos using a join to correctly associate them
    $sql_photos = "SELECT fl.link
    FROM foto_links fl
    JOIN foto_id fi ON fl.foto_id = fi.foto_id
    WHERE fi.advertentie_id = $advertentie_id;
    ";
    $result_photos = $conn->query($sql_photos);
}

?>

<main>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="boat-photos">
                    <?php
                    if ($result_photos && $result_photos->num_rows > 0) {
                        while($row_photo = $result_photos->fetch_assoc()) {
                            echo "<img src='" . $row_photo['link'] . "' class='img-fluid mb-3'>";
                        }
                    } else {
                        echo "<p>Geen foto's beschikbaar.</p>";
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3><?php echo $boot_naam; ?></h3>
                        <p>Type: <?php echo $boot_type; ?></p>
                        <?php if ($role === 'verhuurder'): ?>
                            <div class="container mt-5">
                                <h2>Jouw kalender:</h2>
                                <div class="calendar">
                                    <div class="header">
                                        <div>Maand: <?php echo $currentMonth ?></div>
                                    </div>
                                    <div class="days">
                                        <?php
                                            echo displayCalendar($conn, $gebruiker_id);
                                        ?>
                                    </div>
                                </div>
                            </div><br><br>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
