<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Error</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Login Mislukt!</h4>
                    <p>Sorry, de ingevulde gebruikersnaam, wachtwoord of rol was verkeerd. Probeer opnieuw.</p>
                </div>
                <div class="text-center">
                    <a href="login.php" class="btn btn-primary">Terug naar Login</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Automatically redirect to login page after 2.5 seconds
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 2500);
    </script>
</body>
</html>
