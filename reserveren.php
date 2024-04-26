<?php 
include 'header.php';
?>

<body>
    <h2>Reserveren</h2>
    <form action="process_reservation.php" method="POST">
        <label for="selected_dates">Selecteer datums</label><br>
        <!-- Calendar input field -->
        <input type="date" id="selected_dates" name="selected_dates" multiple><br>

        <label for="additional_info">Aanvullende informatie:</label><br>
        <!-- Text area for additional information -->
        <textarea id="additional_info" name="additional_info" rows="4" cols="50"></textarea><br>

        <!-- Submit button -->
        <button type="submit" name="submit">Reserveren</button>
    </form>

    <!-- Include necessary JavaScript files for calendar input -->
</body>
</html>

<?php 
include 'footer.php';
?>