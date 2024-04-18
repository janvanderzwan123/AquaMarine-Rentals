<?php
include 'header.php';

?>

<main>
    <div class="container">
        <div class="row mb-4">
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
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h3>Filteren</h3>
                        <hr>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                            <div class="form-group">
                                <label for="datum">Datum</label>
                                <input type="date" class="form-control" id="datum" name="datum" value="<?php echo isset($_GET['datum']) ? htmlspecialchars($_GET['datum']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="locatie">Locatie</label>
                                <input type="text" class="form-control" id="locatie" name="locatie" value="<?php echo isset($_GET['locatie']) ? htmlspecialchars($_GET['locatie']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="boot_type">Soort boot</label>
                                <select class="form-control" id="boot_type" name="boot_type" required>
                                    <option value="">Selecteer type...</option>
                                    <option value="Kano" <?php echo (isset($_POST['boot_type']) && $_POST['boot_type'] === 'Kano') ? 'selected' : ''; ?>>Kano</option>
                                    <option value="Motorboot" <?php echo (isset($_POST['boot_type']) && $_POST['boot_type'] === 'Motorboot') ? 'selected' : ''; ?>>Motorboot</option>
                                    <option value="Zeilboot" <?php echo (isset($_POST['boot_type']) && $_POST['boot_type'] === 'Zeilboot') ? 'selected' : ''; ?>>Zeilboot</option>
                                    <option value="Zeilschip" <?php echo (isset($_POST['boot_type']) && $_POST['boot_type'] === 'Zeilschip') ? 'selected' : ''; ?>>Zeilschip</option>
                                    <option value="Kajak" <?php echo (isset($_POST['boot_type']) && $_POST['boot_type'] === 'Kajak') ? 'selected' : ''; ?>>Kajak</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="vermogen">Vermogen</label>
                                <input type="number" class="form-control" id="vermogen" name="vermogen" value="<?php echo isset($_GET['vermogen']) ? htmlspecialchars($_GET['vermogen']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="lengte">Lengte</label>
                                <input type="number" class="form-control" id="lengte" name="lengte" value="<?php echo isset($_GET['lengte']) ? htmlspecialchars($_GET['lengte']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="snelheid">Snelheid</label>
                                <input type="number" class="form-control" id="snelheid" name="snelheid" value="<?php echo isset($_GET['snelheid']) ? htmlspecialchars($_GET['snelheid']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="passagiers">Aantal passagiers</label>
                                <input type="number" class="form-control" id="passagiers" name="passagiers" value="<?php echo isset($_GET['passagiers']) ? htmlspecialchars($_GET['passagiers']) : ''; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary" name="submit">Filters Toepassen</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <?php include 'advertenties.php'; ?>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>