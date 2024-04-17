<?php 
include 'header.php'; 

// Start session
session_start();

?>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form action="process_login.php" method="post">
                            <div class="form-group">
                                <label for="role">Selecteer Rol:</label>
                                <select class="form-control" id="role" name="role">
                                    <option value="verhuurder">Verhuurder</option>
                                    <option value="huurder">Huurder</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gebruikersnaam">Gebruikersnaam:</label>
                                <input type="text" class="form-control" id="gebruikersnaam" name="gebruikersnaam">
                            </div>
                            <div class="form-group">
                                <label for="wachtwoord">Wachtwoord:</label>
                                <input type="password" class="form-control" id="wachtwoord" name="wachtwoord">
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
