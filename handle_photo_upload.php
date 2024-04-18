<?php
include 'database.php';

function handle_photo_upload($files, $advertentie_id, $conn) {
    $upload_dir = 'https://aquamarine-rentals.yen132.nl/images/';
    $allowed_types = array('jpg', 'png', 'jpeg', 'gif');
    $max_size = 5000000;
    
    $uploaded_photos = array();

    foreach ($files['name'] as $key => $filename) {
        $file_tmp = $files['tmp_name'][$key];
        $file_type = $files['type'][$key];
        $file_size = $files['size'][$key];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_types) && $file_size <= $max_size) {
            $new_filename = uniqid() . '.' . $file_ext;
            $file_path = $upload_dir . $new_filename;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $sql_insert = "INSERT INTO foto_links (foto_id, link) VALUES (?, ?)";
                $stmt = $conn->prepare($sql_insert);
                if ($stmt) {
                    $stmt->bind_param("is", $advertentie_id, $file_path);
                    if ($stmt->execute()) {
                        $uploaded_photos[] = $file_path;
                    } else {
                        echo "Kon foto link niet in database invoegen: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    echo "Kon de statement niet voorbereiden: " . $conn->error;
                }
            } else {
                echo "Kon geüploade bestand niet verplaatsen.";
            }
        } else {
            echo "Bestandstype niet toegestaan of te groot.";
        }
    }

    return $uploaded_photos;
}

// Voorbeeldgebruik:
// Stel dat $_FILES['photos'] de geüploade bestanden bevat en $advertentie_id afkomstig is van de vorige invoeging
// $photos = handle_photo_upload($_FILES['photos'], $advertentie_id, $conn);
?>
