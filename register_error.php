<?php 
include 'header.php';
?>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Registreren Mislukt!</h4>
                    <p>Sorry, de ingevulde gebruikersnaam, wachtwoord of rol was verkeerd. Probeer opnieuw.</p>
                </div>
                <div class="text-center">
                    <a href="registreren.php" class="btn btn-primary">Terug naar Registreren</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            window.location.href = 'registreren.php';
        }, 2500);
    </script>
</body>
</html>

<?php 
include 'footer.php';
?>
