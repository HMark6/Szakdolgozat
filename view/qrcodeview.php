<?php
// QR kódok megjelenítése
session_start();
if (isset($_SESSION['qrCodes']) && !empty($_SESSION['qrCodes'])) {
    echo '<h1>QR kódok</h1>';
    echo '<ul>';
    foreach ($_SESSION['qrCodes'] as $index => $qrCodeData) {
        // QR kód generálása az adatok alapján
        $base64Data = generateQRCodeBase64($qrCodeData); // Itt a megfelelő generáló függvényt kell meghívni
        $file = 'decoded_image' . ($index + 1) . '.png';
        file_put_contents($file, base64_decode(substr($base64Data, strpos($base64Data, ',') + 1)));

        // Link generálása a QR kódhoz
        $qrCodePath = 'view/' . $file;
        $link = '<a href="' . $qrCodePath . '" target="_blank">QR code ' . ($index + 1) . '</a>';

        // Listaelem
        echo '<li>' . $link . '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>Nincsenek QR kódok.</p>';
}

?>

