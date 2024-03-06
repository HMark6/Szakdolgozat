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

            echo "<div class='card bg-dark text-white' mb-2>";
            echo "<div class='card-body'>";
            echo "<p class='card-text'>Étkezés típusa: $reggeli $ebéd $uzsonna</p>";
            echo "</div>";
            echo "</div>";

            echo "</div>";
            echo "</div>";
            // további adatok megjelenítése ...
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
