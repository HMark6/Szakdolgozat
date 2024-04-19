<?php 
    require('../helpers/mysql.php');
    $conn = DataBase::getConnection();

    // Ellenőrizd, hogy az étel azonosítója át lett-e adva az URL-ben
    if(isset($_GET['id'])) {
        $etel_id = $_GET['id'];

        $sql = "SELECT * FROM etelek WHERE etel_ID = $etel_id";
        $result = $conn->query($sql);

        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nev = $row['nev'];
            $osszetevok = $row['osszetevok'];
            $kepek = $row['kepek'];
            $reggeli = $row['reggeli'];
            $ebéd = $row['ebed'];
            $uzsonna = $row['uzsonna'];

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