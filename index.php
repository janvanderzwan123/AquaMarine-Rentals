<?php 
include 'header.php'; 

?>

<main>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Zoeken..." id="searchInput">
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
                    <form action="filteren.php" method="get">
                        <div class="form-group">
                            <label for="datum">Datum</label>
                            <input type="date" class="form-control" id="datum" name="datum">
                        </div>
                        <div class="form-group">
                            <label for="locatie">Locatie</label>
                            <input type="text" class="form-control" id="locatie" name="locatie">
                        </div>
                        <div class="form-group">
                            <label for="type-boot">Soort boot</label>
                            <select class="form-control" id="type-boot" name="type-boot">
                                <option value="">Kiezen</option>
                                <option value="sailing">Zeilboot</option>
                                <option value="motor">Motorboot</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="vermogen">Vermogen</label>
                            <input type="number" class="form-control" id="vermogen" name="vermogen">
                        </div>
                        <div class="form-group">
                            <label for="lengte">Lengte</label>
                            <input type="number" class="form-control" id="lengte" name="lengte">
                        </div>
                        <div class="form-group">
                            <label for="snelheid">Snelheid</label>
                            <input type="number" class="form-control" id="snelheid" name="snelheid">
                        </div>
                        <div class="form-group">
                            <label for="passagiers">Aantal passagiers</label>
                            <input type="number" class="form-control" id="passagiers" name="passagiers">
                        </div>
                        <button type="submit" class="btn btn-primary">Filters Toepassen</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Boat Listings -->
        <div class="col-md-9">
            <div class="row">
                <?php include 'advertenties.php'; ?>
                <?php include 'filteren.php'; ?>
            </div>
        </div>
    </div>
</div>
</main>

<?php include 'footer.php'; ?>
