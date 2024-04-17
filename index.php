<?php 
include 'header.php'; 
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
$sql = "SELECT * FROM boot_listings WHERE 1=1";
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

// Execute the query
$result = $conn->query($sql);
?>

<main>
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Zoeken...">
                    <div class="input-group-append">
                        <button id="searchBtn" class="btn btn-outline-secondary" type="button">Zoeken</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Filter Menu -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h3>Filteren</h3>
                        <hr>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                            <div class="form-group">
                                <label for="datum">Datum</label>
                                <input type="date" class="form-control" id="datum" name="datum" value="<?php echo $datum; ?>">
                            </div>
                            <div class="form-group">
                                <label for="locatie">Locatie</label>
                                <input type="text" class="form-control" id="locatie" name="locatie" value="<?php echo $locatie; ?>">
                            </div>
                            <div class="form-group">
                                <label for="type-boot">Soort boot</label>
                                <select class="form-control" id="type-boot" name="type-boot">
                                    <option value="">Kiezen</option>
                                    <option value="sailing" <?php echo ($type_boot == 'sailing') ? 'selected' : ''; ?>>Zeilboot</option>
                                    <option value="motor" <?php echo ($type_boot == 'motor') ? 'selected' : ''; ?>>Motorboot</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="vermogen">Vermogen</label>
                                <input type="number" class="form-control" id="vermogen" name="vermogen" value="<?php echo $vermogen; ?>">
                            </div>
                            <div class="form-group">
                                <label for="lengte">Lengte</label>
                                <input type="number" class="form-control" id="lengte" name="lengte" value="<?php echo $lengte; ?>">
                            </div>
                            <div class="form-group">
                                <label for="snelheid">Snelheid</label>
                                <input type="number" class="form-control" id="snelheid" name="snelheid" value="<?php echo $snelheid; ?>">
                            </div>
                            <div class="form-group">
                                <label for="passagiers">Aantal passagiers</label>
                                <input type="number" class="form-control" id="passagiers" name="passagiers" value="<?php echo $passagiers; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary" name="submit">Filters Toepassen</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Boat Listings -->
            <div class="col-md-9">
                <?php include 'advertenties.php'; ?>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
