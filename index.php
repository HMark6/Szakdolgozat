<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Főoldal</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="css\index.css" rel="stylesheet">

</head>
<body>

<?php
session_start();

$logged_in = isset($_SESSION['user_id']); // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Menü</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="view/menu.php">Étlap</a>
            </li>
            <?php if ($logged_in) : ?>
                <!-- Ha bejelentkezett a felhasználó -->
                <li class="nav-item">
                    <a class="nav-link" href="view\user_dashboard.php">Profil</a>
                </li>
            <?php endif; ?>
            <!-- Ha a felhasználó be van jelentkezve, akkor a Kijelentkezés link jelenjen meg -->
            <?php if ($logged_in) : ?>
                <!-- Ha a felhasználó be van jelentkezve, akkor megjelenítjük a Kijelentkezés lehetőséget -->
                <li class="nav-item">
                    <a class="nav-link" href="/Szakdolgozat/view/logout.php">Kijelentkezés</a>
                </li>
            <?php else: ?>
                <!-- Ha a felhasználó nincs bejelentkezve, akkor megjelenítjük a Bejelentkezés/Regisztráció lehetőséget -->
                <li class="nav-item">
                    <a class="nav-link" href="\Szakdolgozat\view\login.php">Bejelentkezés/Regisztráció</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
