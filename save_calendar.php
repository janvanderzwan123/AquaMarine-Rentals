<?php
session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

include 'database.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];

$sql = "SELECT advertentie_id FROM advertenties WHERE verhuurder_id = (
    SELECT gebruiker_id FROM gebruikers WHERE gebruikersnaam = ?
)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $advertentieID = $row['advertentie_id'];
} else {
    header("Location: login.php");
    exit();
}

function getNumDaysInMonth() {
    return date('t');
}

function updateCalendarEvents($conn, $advertentieID) {
    $numDays = getNumDaysInMonth();

    for ($i = 1; $i <= $numDays; $i++) {
        $date = date('Y-m-d', mktime(0, 0, 0, date('n'), $i, date('Y')));
        if (isset($_POST['selected_dates'])) {
            if (in_array($i, $_POST['selected_dates'])) {
                $eventTitle = 'Beschikbaar';
                echo '<div>eventtitle = ' . $eventTitle . '</div>';
            } else {
                $eventTitle = 'Onbeschikbaar';
                echo '<div>eventtitle = ' . $eventTitle . '</div>';
            }
            $sql = "UPDATE verhuurder_calendar SET event_title = 'Beschikbaar' WHERE advertentie_id = $advertentieID AND start_date = $date";
            echo '<div>advertentie id = ' . $advertentieID . '</div>';
            echo '<div>datum = ' . $date . '</div>';
            echo '<div>datum = ' . gettype($date) . '</div>';

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sis", $eventTitle, $advertentieID, $date);
            $stmt->execute();
        } else {
            $sql = "UPDATE verhuurder_calendar SET event_title = 'Onbeschikbaar' WHERE advertentie_id = ? AND start_date = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $advertentieID, $date);
            $stmt->execute();
        }
    }
}

updateCalendarEvents($conn, $advertentieID);

header("Location: profile.php");
exit();
?>
