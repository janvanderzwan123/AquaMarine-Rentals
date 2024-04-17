<?php include 'header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Registration</div>
                <div class="card-body">
                    <form action="register.php" method="POST">
                        <div class="form-group">
                            <label for="role">Choose Role:</label>
                            <select class="form-control" id="role" name="role">
                                <option value="verhuurder">Verhuurder</option>
                                <option value="huurder">Huurder</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gebruikersnaam">Gebruikersnaam:</label>
                            <input type="text" class="form-control" id="gebruikersnaam" name="gebruikersnaam" required>
                        </div>
                        <div class="form-group">
                            <label for="emailadres">Emailadres:</label>
                            <input type="email" class="form-control" id="emailadres" name="emailadres" required>
                        </div>
                        <div class="form-group">
                            <label for="wachtwoord">Wachtwoord:</label>
                            <input type="password" class="form-control" id="wachtwoord" name="wachtwoord" required>
                        </div>
                        <div class="form-group">
                            <label for="wachtwoord_herhalen">Herhaal Wachtwoord:</label>
                            <input type="password" class="form-control" id="wachtwoord_herhalen" name="wachtwoord_herhalen" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Registreren</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>
