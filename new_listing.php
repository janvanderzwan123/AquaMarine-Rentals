<?php include 'header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Nieuwe advertentie</div>
                <div class="card-body">
                    <form action="process_new_listing.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="boot_naam">Boot Naam:</label>
                            <input type="text" class="form-control" id="boot_naam" name="boot_naam" required>
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
                            <label for="locatie">Locatie:</label>
                            <input type="text" class="form-control" id="locatie" name="locatie" required>
                        </div>
                        <div class="form-group">
                            <label for="vermogen">Vermogen:</label>
                            <input type="number" class="form-control" id="vermogen" name="vermogen" required>
                        </div>
                        <div class="form-group">
                            <label for="lengte">Lengte (meters)</label>
                            <input type="number" class="form-control" id="lengte" name="lengte" placeholder="Enter length in meters" required>
                        </div>
                        <div class="form-group">
                            <label for="prijs_per_dag">Prijs per dag (€):</label>
                            <input type="number" class="form-control" id="prijs_per_dag" name="prijs_per_dag" required>
                        </div>

                        <div class="form-group">
                            <label for="foto">Foto's uploaden:</label>
                            <input type="file" class="form-control-file" id="foto" name="foto[]" multiple accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-primary">Advertentie plaatsen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
