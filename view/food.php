<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Étel részletei</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/food.css" rel="stylesheet">
</head>
<body>

<?php
session_start();

$logged_in = isset($_SESSION['user_id']); // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
<div class="container-fluid">
    <a class="navbar-brand">Étel részletei</a>
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
    <?php 
    // Kapcsolódás az adatbázishoz
    require('../helpers/mysql.php');
    $conn = DataBase::getConnection();

    // Ellenőrizd, hogy az étel azonosítója át lett-e adva az URL-ben
    if(isset($_GET['id'])) {
        $etel_id = $_GET['id'];

        // Lekérdezés az étel részleteiről az adatbázisból
        $sql = "SELECT * FROM etelek WHERE etel_ID = $etel_id";
        $result = $conn->query($sql);

        // Ellenőrizd, hogy találtál-e eredményt
        if($result && $result->num_rows > 0) {
            // Az étel adatainak kiolvasása
            $row = $result->fetch_assoc();
            $nev = $row['nev'];
            $osszetevok = $row['osszetevok'];
            $kepek = $row['kepek'];
            $reggeli = $row['reggeli'];
            $ebéd = $row['ebed'];
            $uzsonna = $row['uzsonna'];

            // Most jöhet az adatok megjelenítése az oldalon
            echo "<h2 class='text-center'>$nev</h2>";
            echo "<div class='row g-3'>";
            echo "<div class='col-md-6'>";
            echo "<img src='../pictures/$kepek.jpg' class='img-fluid'>";
            echo "</div>";
            echo "<div class='col-md-6'>";

            echo "<div class='card bg-dark text-white mb-2'>";
            echo "<div class='card-body'>";
            echo "<p class='card-text'>Osszetevok: $osszetevok</p>";
            echo "</div>";
            echo "</div>";

            // Lekérdezés az ételhez tartozó allergénekről
            $sql_allergens = "SELECT allergének.nev
            FROM allergének
            INNER JOIN etelek_allergenei ON allergének.allergenek_ID = etelek_allergenei.allergenek_ID
            WHERE etelek_allergenei.etel_ID = $etel_id";

            $result_allergens = $conn->query($sql_allergens);

            if ($result_allergens && $result_allergens->num_rows > 0) {
                echo "<div class='card bg-dark text-white mb-2'>";
                echo "<div class='card-body'>";
                echo "<p class='card-text'>Allergén csoport:</p>";
                echo "<ul>";
                while ($row_allergen = $result_allergens->fetch_assoc()) {
                    echo "<li>" . $row_allergen['nev'] . "</li>";
                }
                echo "</ul>";
                echo "</div>";
                echo "</div>";
            }

            echo "<div class='card bg-dark text-white' mb-2>";
            echo "<div class='card-body'>";
            echo "<p class='card-text'>Étkezés típusa: $reggeli $ebéd $uzsonna</p>";
            echo "</div>";
            echo "</div>";

            echo "</div>";
            echo "</div>";
            

        } else {
            echo "<p class='text-center'>Nincs ilyen étel azonosítóval.</p>";
        }
    } else {
        echo "<p class='text-center'>Nem lett átadva étel azonosító.</p>";
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
