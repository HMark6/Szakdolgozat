<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR kódok</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">QR kódok</h1>
        <ul class="list-group mt-3">
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
                    echo '<li class="list-group-item"><a href="' . $qrCodeURL . '" target="_blank">QR code ' . ($index + 1) . '</a></li>';
                }
            } else {
                echo '<li class="list-group-item">Nincsenek QR kódok a felhasználó mappájában.</li>';
            }
        } else {
            echo '<li class="list-group-item">A felhasználóhoz tartozó mappa nem található.</li>';
        }
        ?>
        </ul>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


