<?php include 'header.php'; ?>

<h2>Registration</h2>
<form action="register.php" method="POST">
    <div>
        <label for="role">Choose Role:</label>
        <select name="role" id="role">
            <option value="verhuurder">Verhuurder</option>
            <option value="huurder">Huurder</option>
        </select>
    </div>
    <div>
        <label for="gebruikersnaam">Gebruikersnaam:</label>
        <input type="text" id="gebruikersnaam" name="gebruikersnaam" required>
    </div>
    <div>
        <label for="emailadres">Emailadres:</label>
        <input type="email" id="emailadres" name="emailadres" required>
    </div>
    <div>
        <label for="wachtwoord">Wachtwoord:</label>
        <input type="password" id="wachtwoord" name="wachtwoord" required>
    </div>
    <div>
        <label for="wachtwoord_herhalen">Herhaal Wachtwoord:</label>
        <input type="password" id="wachtwoord_herhalen" name="wachtwoord_herhalen" required>
    </div>
    <button type="submit">Registreren</button>
</form>

<?php include 'footer.php'; ?>
