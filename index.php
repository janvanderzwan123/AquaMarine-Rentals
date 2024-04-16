<?php 
include 'header.php'; 

?>

<main>
    <div class="container">
        <div class="row">
            <!-- Filter Menu -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h3>Filteren</h3>
                        <hr>
                        <form action="#" method="get">
                            <div class="form-group">
                                <label for="datum">Datum</label>
                                <input type="date" class="form-control" id="datum">
                            </div>
                            <div class="form-group">
                                <label for="locatie">Locatie</label>
                                <input type="text" class="form-control" id="locatie">
                            </div>
                            <div class="form-group">
                                <label for="type-boot">Soort boot</label>
                                <select class="form-control" id="type-boot">
                                    <option value="">Kiezen</option>
                                    <option value="sailing">Zeilboot</option>
                                    <option value="motor">Motorboot</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="vermogen">Vermogen</label>
                                <input type="number" class="form-control" id="vermogen">
                            </div>
                            <div class="form-group">
                                <label for="lengte">Lengte</label>
                                <input type="number" class="form-control" id="lengte">
                            </div>
                            <div class="form-group">
                                <label for="snelheid">Snelheid</label>
                                <input type="number" class="form-control" id="snelheid">
                            </div>
                            <div class="form-group">
                                <label for="passagiers">Aantal passagiers</label>
                                <input type="number" class="form-control" id="passagiers">
                            </div>
                            <button type="submit" class="btn btn-primary">Filters Toepassen</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Boat Listings -->
            <div class="col-md-9">
                <!-- Add your boat listings here -->
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>