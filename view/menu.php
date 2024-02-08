<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menü</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Szakdolgozat/css/menu.css" rel="stylesheet">
</head>
<body>

<?php
$logged_in = false;
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand">Étlap</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="\Szakdolgozat\index.php">Menü</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="\Szakdolgozat\view\register.php">Regisztráció/Bejelentkezés</a>
            </li>
            <?php if ($logged_in) : ?>
                <!-- Ha bejelentkezett a felhasználó -->
                <li class="nav-item">
                    <a class="nav-link" href="#">Profil</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="text-center">Étlap</h1>
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Hétfő</th>
                <th scope="col">Kedd</th>
                <th scope="col">Szerda</th>
                <th scope="col">Csütörtök</th>
                <th scope="col">Péntek</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">Reggeli</th>
                <td class="food-item">Tojásrántotta</td>
                <td class="food-item">Zabkása</td>
                <td class="food-item">Tejfölös palacsinta</td>
                <td class="food-item">Paradicsomos tojás</td>
                <td class="food-item">Kesudió krémmel töltött briós</td>
            </tr>
            <tr>
                <th scope="row">Ebéd</th>
                <td class="food-item">Csirkepörkölt</td>
                <td class="food-item">Rántott hús</td>
                <td class="food-item">Bableves</td>
                <td class="food-item">Rakott karfiol</td>
                <td class="food-item">Csirkecomb grillezett zöldségekkel</td>
            </tr>
            <tr>
                <th scope="row">Uzsonna</th>
                <td class="food-item">Gyümölcs saláta</td>
                <td class="food-item">Joghurt</td>
                <td class="food-item">Ropogós alma</td>
                <td class="food-item">Rakott sajtos zsemle</td>
                <td class="food-item">Mogyorókrém</td>
            </tr>
        </tbody>
    </table>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


