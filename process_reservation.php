<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $selected_dates = $_POST['selected_dates'] ?? '';
    $additional_info = $_POST['additional_info'] ?? '';

    // Check if dates and additional info are provided
    if (empty($selected_dates) || empty($additional_info)) {
        $error_message = "Please provide selected dates and additional information.";
    } else {
        // Include database connection
        include 'database.php';

        // Retrieve the email of the user making the reservation
        session_start();
        if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true || !isset($_SESSION['username'])) {
            header("Location: login.php");
            exit();
        }
        $username = $_SESSION['username'];
        $stmtUserEmail = $conn->prepare("SELECT emailadres FROM gebruikers WHERE gebruikersnaam = ?");
        $stmtUserEmail->bind_param("s", $username);
        $stmtUserEmail->execute();
        $resultUserEmail = $stmtUserEmail->get_result();
        $rowUserEmail = $resultUserEmail->fetch_assoc();
        $user_email = $rowUserEmail['emailadres'];

        // Retrieve the email of the boat owner
        $advertentie_id = $_POST['advertentie_id'] ?? '';
        if (empty($advertentie_id)) {
            $error_message = "Invalid advertisement ID.";
        } else {
            $stmtBoatOwnerEmail = $conn->prepare("SELECT g.emailadres FROM advertenties AS a JOIN gebruikers AS g ON a.verhuurder_id = g.gebruiker_id WHERE a.advertentie_id = ?");
            $stmtBoatOwnerEmail->bind_param("i", $advertentie_id);
            $stmtBoatOwnerEmail->execute();
            $resultBoatOwnerEmail = $stmtBoatOwnerEmail->get_result();
            $rowBoatOwnerEmail = $resultBoatOwnerEmail->fetch_assoc();
            $boat_owner_email = $rowBoatOwnerEmail['emailadres'];

            // Send email to the boat owner
            $to = $boat_owner_email;
            $subject = "Reservation Request";
            $message = "Selected Dates: " . $selected_dates . "\n\nAdditional Information: " . $additional_info . "\n\nUser Email: " . $user_email;
            $headers = "From: " . $user_email;

            // Send email
            if (mail($to, $subject, $message, $headers)) {
                $success_message = "Reservation request sent successfully!";
            } else {
                $error_message = "Failed to send reservation request. Please try again later.";
            }
        }

        // Close database connection
        $stmtUserEmail->close();
        $stmtBoatOwnerEmail->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Processed</title>
    <style>
        /* Basic CSS for styling */
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php elseif (isset($success_message)): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <a href="index.php">&laquo; Go back to reservation form</a>
    </div>
</body>
</html>
