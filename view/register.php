<?php
require('../helpers/mysql.php'); // Adatbázis kapcsolat létesítése
$conn = DataBase::getConnection();

$message = ''; // Üzenet inicializálása

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Felhasználó által megadott adatok
    $lastname = $_POST['vezetekNev'];
    $firstname = $_POST['keresztNev'];
    $city_name = $_POST['telepules']; // A város neve
    $phone = $_POST['telefonSzam'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Ellenőrizzük, hogy az adott e-mail címmel már létezik-e fiók az adatbázisban
    $check_email_query = "SELECT email FROM profil WHERE email='$email'";
    $check_email_result = $conn->query($check_email_query);
    if ($check_email_result && $check_email_result->num_rows > 0) {
        $message = "Már létezik fiók ezzel az e-mail címmel! Kérlek adj meg egy másikat!";
        $email = ''; // Töröljük az e-mail mező értékét
    }else{

    // SQL lekérdezés az adott város irányítószámának lekérdezésére
    $sql = "SELECT iranyitoszam FROM irányítószámok WHERE telepulesek='$city_name'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $zip_code = $row['iranyitoszam'];

        // Ellenőrzés, hogy a városnév már szerepel-e a település táblában
        $check_city_query = "SELECT telepules_ID FROM település WHERE iranyitoszam='$zip_code'";
        $check_city_result = $conn->query($check_city_query);

        if ($check_city_result && $check_city_result->num_rows > 0) {
            // Ha már szerepel, a város ID-ját használjuk
            $city_row = $check_city_result->fetch_assoc();
            $city_id = $city_row['telepules_ID'];
        } else {
            // Ha nem szerepel, hozzáadjuk az adatbázishoz és lekérjük az új város ID-ját
            $add_city_query = "INSERT INTO `település` (`iranyitoszam`) VALUES ('$zip_code')";
            if ($conn->query($add_city_query) === TRUE) {
                $city_id = $conn->insert_id;
            } else {
                echo "Hiba a város hozzáadása közben: " . $conn->error;
                exit; // Kilépés, ha hiba történt a város hozzáadása közben
            }
        }

        // Felhasználó hozzáadása a profil táblához
        $sql_insert = "INSERT INTO `profil`(`vezeteknev`, `keresztnev`, `telepules_ID`, `telefonszam`, `email`, `jelszo`) 
                        VALUES ('$lastname','$firstname','$city_id','$phone','$email','$password')";
       
        if ($conn->query($sql_insert) === TRUE) {
            echo "Sikeres regisztráció!";
        } else {
            echo "Hiba a regisztráció során: " . $conn->error;
        }
    } else {
        echo "Nem található város az adatbázisban a megadott név alapján.";
    }
}
}
?>












<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/Szakdolgozat/css/login.css" rel="stylesheet">
    <title>Regisztráció</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header>Regisztrálás</header>
            
            <form action="" method="post">



                <div class="field input">
                    <label for="vezetekNev">Vezetéknév</label>
                    <input type="text" name="vezetekNev" id="vezetekNev" pattern="[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű\s]+" title="Csak betűket tartalmazhat" required>
                </div>
                <div class="field input">
                    <label for="keresztNev">Keresztnév</label>
                    <input type="text" name="keresztNev" id="keresztNev" pattern="[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű\s]+" title="Csak betűket tartalmazhat" required>
                </div>

                <div class="field input">
                    <label for="telepules">Település</label>
                    <select name="telepules" id="telepules" required>
                        <option value="">Válasszon települést...</option>
                        <?php
                        // Irányítószámok táblából lekérdezés
                        $sql = "SELECT * FROM `irányítószámok` ORDER BY `irányítószámok`.`telepulesek` ASC";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['telepulesek'] . "'>" . $row['telepulesek'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <div class="field input">
                    <label for="telefonSzam">Telefonszám</label>
                    <input type="tel" name="telefonSzam" id="telefonSzam" placeholder="20 415 9720" pattern="[0-9]{2} [0-9]{3} [0-9]{4}" required>
                </div>
                <div class="field input">
                    <label for="email">E-mail cím</label>
                    <input type="email" name="email" id="email" required>
                    <div id="emailError" class="error-message"></div>
                </div>
                <div class="field input">
                    <label for="password">Jelszó</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Regisztrálás" required>
                </div>
                <div class="links">
                    Van már fiókod? <a href="\Szakdolgozat\view\login.php">Bejelentkezés</a>
                </div>
                <?php if (!empty($message)) : ?>
                <div class="error-message"><?php echo $message; ?></div>
            <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>