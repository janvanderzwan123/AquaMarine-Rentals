<?php 
include 'header.php';
?>

<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Reserveren</div>
                <div class="card-body">
                    <form action="process_reservation.php" method="post">
                        <div class="form-group">
                            <label for="selected_dates">Selecteer Datum(s):</label>
                            <input type="date" class="form-control" id="selected_dates" name="selected_dates" multiple>
                        </div>
                        <div class="form-group">
                            <label for="additional_info">Aanvullende Informatie:</label>
                            <textarea class="form-control" id="additional_info" name="additional_info" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Reserveren</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php 
include 'footer.php';
?>