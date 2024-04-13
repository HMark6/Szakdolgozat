<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR kódok</title>
</head>
<body>
    <h1>QR kódok</h1>
    <ul>
    <?php
    // Email cím lekérése a sessionból
    session_start();
    $email = $_SESSION['email'];

    // Felhasználó mappájának elérési útja
    $userFolderPath = '../view/users/user_' . $email . '/';

    // Ellenőrizzük, hogy a felhasználónak vannak-e QR kódjai
    if (file_exists($userFolderPath)) {
        $userQRCodes = glob($userFolderPath . '*.png');
        // Ha a felhasználónak vannak QR kódjai, listázzuk azokat
        if (!empty($userQRCodes)) {
            foreach ($userQRCodes as $index => $qrCode) {
                // Az URL generálása a QR kód megjelenítéséhez
                $qrCodeURL = $qrCode;
                // A QR kód megjelenítése egy listaelemmel
                echo '<li><a href="' . $qrCodeURL . '" target="_blank">QR code ' . ($index + 1) . '</a></li>';
            }
        } else {
            echo '<li>Nincsenek QR kódok a felhasználó mappájában.</li>';
        }
    } else {
        echo '<li>A felhasználóhoz tartozó mappa nem található.</li>';
    }
    ?>
    </ul>
</body>
</html>

