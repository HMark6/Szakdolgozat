<?php

session_start(); // Session kezelésének indítása
require('../helpers/mysql.php'); // Adatbázis kapcsolat létesítése
$conn = DataBase::getConnection();

// Étkezési díjak lekérdezése és összegzése
$etkezes_dijak_sql = "SELECT SUM(etkezesidij) AS osszeg FROM etkezesek";
$result = $conn->query($etkezes_dijak_sql);
$osszeg = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $osszeg = $row['osszeg'];
}

if (isset($_POST['submit'])) {
    // Felhasználó azonosítójának lekérdezése a sessionból
    $user_id = $_SESSION['user_id'];

    // Ellenőrizzük, hogy van-e már előfizetése a felhasználónak
    $check_subscription_sql = "SELECT * FROM elofizetes WHERE profil_ID = '$user_id'";
    $subscription_result = $conn->query($check_subscription_sql);

    if ($subscription_result->num_rows > 0) {
        echo '<div class="alert alert-danger" role="alert">Már van aktív előfizetésed, nem tudsz újat létrehozni!</div>';
    } else {
        // Jelenlegi időpont lekérdezése
        $befizetes_datum = date('Y-m-d H:i:s');

        // A lemondás dátuma null értéket kap
        $lemondas_datum = null;

        // Lekérdezzük az étkezési dátumokat az etkezesek táblából
        $etkezes_sql = "SELECT etkezes_datum FROM etkezesek";
        $result = $conn->query($etkezes_sql);

        if ($result->num_rows > 0) {
            // Ellenőrizzük az étkezési dátumokat
            $allowed_to_subscribe = true;
            while ($row = $result->fetch_assoc()) {
                $etkezes_datum = $row['etkezes_datum'];
                
                // Ha az aktuális dátum nagyobb vagy egyenlő az étkezési dátumnál,
                // akkor ne engedélyezzük az előfizetést
                if (strtotime(date('Y-m-d')) >= strtotime($etkezes_datum)) {
                    $allowed_to_subscribe = false;
                    break; // Kilépünk a ciklusból, ha már találtunk egy olyan étkezést, amely már elkezdődött
                }
            }

            if ($allowed_to_subscribe) {
                // Beszúrjuk az étkezési dátumokat az elofizetett_napok táblába
                $etkezes_sql = "SELECT etkezes_datum FROM etkezesek";
                $result = $conn->query($etkezes_sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $etkezes_datum = $row['etkezes_datum'];

                        // Beszúrjuk az új sort az elofizetett_napok táblába
                        $insert_sql = "INSERT INTO elofizetett_napok (elofizetes_ID, etkezes_datum) VALUES (NULL, '$etkezes_datum')";
                        if ($conn->query($insert_sql) !== TRUE) {
                            echo "Hiba az étkezési nap hozzáadásakor: " . $conn->error;
                        }
                    }
                }

                // Az előfizetett_napok táblából lekérjük az elofizetes_ID-ket
                $elofizetes_sql = "SELECT elofizetes_ID FROM elofizetett_napok";
                $elofizetes_result = $conn->query($elofizetes_sql);

                if ($elofizetes_result->num_rows > 0) {
                    // Beszúrjuk az előfizetéseket az elofizetes táblába
                    while ($elofizetes_row = $elofizetes_result->fetch_assoc()) {
                        $elofizetes_id = $elofizetes_row['elofizetes_ID'];

                        $insert_elofizetes_sql = "INSERT INTO elofizetes (profil_ID, elofizetes_ID, befizetes_datum, lemondas_datum) VALUES ('$user_id', '$elofizetes_id', '$befizetes_datum', NULL)";
                        if ($conn->query($insert_elofizetes_sql) !== TRUE) {
                            echo "Hiba az előfizetés hozzáadásakor: " . $conn->error;
                        }
                    }
                    echo '<div class="alert alert-success" role="alert">Az előfizetés sikeres volt!</div>';
                } else {
                    echo "Nincsenek előfizetett napok az előfizetett_napok táblában.";
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">A heti étkezés befizetéséről sajnos lemaradtál!</div>';
            }
        } else {
            echo "Nincsenek étkezési napok az etkezesek táblában.";
        }
    }
echo '<div class="alert alert-success" role="alert">Az előfizetés sikeres volt! Kérlek, várj egy kicsit...</div>';
echo '<script>
    setTimeout(function() {
        window.location.href = "../index.php";
    }, 3000); // 3000 milliszekundum = 3 másodperc
</script>';


require('../helpers/creditcardHelper.php');

}
?>