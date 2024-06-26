<?php 
include 'database.php'; 

session_start();

$loggedInUser = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AquaMarine Rentals</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet"> <!-- Pacifico font -->
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="/images/favicon_transparent.jpg">
    <style>
        /* Add shadow under header */
        header {
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.1);
        }
        /* Apply Pacifico font to headings */
        body {
            font-family: 'Pacifico', cursive;
        }
    </style>
</head>
<body>
    <header class="py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Logo -->
            <div>
                <a href="index.php"><img src="/images/AMR.png" alt="Logo" style="height: 5rem; margin-left: -6rem;"></a>
            </div>
            <h1 class="m-0">Aqua Marine Rentals</h1>
            <!-- Profile icon with link -->
            <?php if ($loggedInUser): ?>
                <a href="profile.php" class="text-dark"><i class="far fa-user"></i></a> <!-- Font Awesome profile icon -->
                <a href="logout.php" class="text-dark ml-3">Uitloggen</a> <!-- Logout button -->
            <?php else: ?>
                <a href="login.php" class="text-dark"><i class="far fa-user"></i></a> <!-- Redirect to login.php if not logged in -->
            <?php endif; ?>
        </div>
    </header>
</body>

</html>