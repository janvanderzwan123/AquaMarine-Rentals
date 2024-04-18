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
                            <label for="boot_type">Boot Type:</label>
                            <input type="text" class="form-control" id="boot_type" name="boot_type" required>
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
