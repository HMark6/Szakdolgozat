<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menü</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../css/menu.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body>

<?php
session_start();

$logged_in = isset($_SESSION['user_id']); // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
<div class="container-fluid">
    <a class="navbar-brand">Étlap</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Menü</a>
            </li>
            <?php if ($logged_in) : ?>
                <!-- Ha bejelentkezett a felhasználó -->
                <li class="nav-item">
                <a class="nav-link" href="user.php">Profil</a>
                </li>
            <?php endif; ?>
            <!-- Ha a felhasználó be van jelentkezve, akkor a Kijelentkezés link jelenjen meg -->
            <?php if ($logged_in) : ?>
                <!-- Ha a felhasználó be van jelentkezve, akkor megjelenítjük a Kijelentkezés lehetőséget -->
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Kijelentkezés</a>
                </li>
            <?php else: ?>
                <!-- Ha a felhasználó nincs bejelentkezve, akkor megjelenítjük a Bejelentkezés/Regisztráció lehetőséget -->
                <li class="nav-item">
                    <a class="nav-link" href="../view/login.php">Bejelentkezés/Regisztráció</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    </div>
</nav>


<div class="container mt-5">
    <h1 class="text-center">Étlap</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="table-responsive">
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
                            <td class="food-item my-link">
                                <a href="food.php?id=1">Csokis gabonagolyó tejjel</a>
                            </td>
                            <td class="food-item my-link">
                                <a href="food.php?id=5">Búzakenyér löncs felvágottal zöldpaprikával és gyümölcstea</a>
                            </td>
                            <td class="food-item my-link">
                                <a href="food.php?id=9">Kifli danone joghurttal</a>
                            </td>
                            <td class="food-item my-link">
                                <a href="food.php?id=13">Fonott kalács tejeskávéval (instant pótkávéból)</a>
                            </td>
                            <td class="food-item my-link">
                                <a href="food.php?id=17">Hagymás tojás búzakenyérrel és gyümölcsteával</a>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Ebéd</th>
                            <td class="food-item my-link">
                                <a href="food.php?id=2">Rántott leves,</a>
                                <a href="food.php?id=3">Zöldborsófőzelék főtt tojással és búzakenyérrel</a>
                            </td>
                            <td class="food-item my-link">
                                <a href="food.php?id=6">Májgombócleves,</a>
                                <a href="food.php?id=7">Mákos metélt</a>
                            </td>
                            <td class="food-item my-link">
                                <a href="food.php?id=10">Vajgaluska leves,</a>
                                <a href="food.php?id=11">Rizseshús meggy befőttel</a>
                            </td>
                            <td class="food-item my-link">
                                <a href="food.php?id=14">Paradicsomleves,</a>
                                <a href="food.php?id=15">Rakott burgonya kolbásszal</a>
                            </td>
                            <td class="food-item my-link">
                                <a href="food.php?id=18">Burgonyaleves,</a>
                                <a href="food.php?id=19">Székelykáposzta búzakenyérrel</a>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Uzsonna</th>
                            <td class="food-item my-link">
                                <a href="food.php?id=4">Görögdinnye, búzakenyér és tonhalkrémmel</a>
                            </td>
                            <td class="food-item my-link">
                                <a href="food.php?id=8">Tejberizs</a>
                            </td>
                            <td class="food-item my-link">
                                <a href="food.php?id=12">Búzakenyér sajtkrémmel és Ivólével(100%)</a>
                            </td>
                            <td class="food-item my-link">
                                <a href="food.php?id=16">Buzakenyér margarinnal, kígyóuborkával és szilvával</a>
                            </td>
                            <td class="food-item my-link">
                                <a href="food.php?id=20">Túró rudi, Szőlő fehér</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php if ($logged_in) : ?>
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- ID hozzáadása a gombhoz -->
                <button id="subscriptionBtn" type="button" class="btn btn-primary w-100">Előfizetés</button>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    // Gomb kiválasztása az ID alapján
    const subscriptionBtn = document.getElementById('subscriptionBtn');

    // Gombra kattintás eseménykezelő hozzáadása
    subscriptionBtn.addEventListener('click', function() {
        // Átirányítás a creditcard.php oldalra
        window.location.href = 'creditcard.php';
    });
</script>



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>


