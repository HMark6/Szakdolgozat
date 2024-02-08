


<?php
require('../helpers/mysql.php'); // Adatbázis kapcsolat létesítése
$db = new DataBase();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Felhasználó által megadott adatok
    $lastname = $_POST['vezetekNev'];
    $firstname = $_POST['keresztNev'];
    $city = $_POST['telepules'];
    $phone = $_POST['telefonSzam'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL lekérdezés a felhasználó létezésének ellenőrzésére
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = DataBase::$conn->query($sql);

    if ($result->num_rows > 0) {
        // Ha már létezik a felhasználó
        echo "A megadott e-mail cím már foglalt.";
    } else {
        // Ha még nem létezik, hozzáadja az adatbázishoz

        $sql= "INSERT INTO `profil`(`vezeteknev`, `keresztnev`, `telepules_ID`, `telefonszam`, `email`, `jelszo`) VALUES ('[vezetekNev]','[keresztNev]','[telepules]','[telefonSzam]','[email]','[password]')";
        if (DataBase::$conn->query($sql) === TRUE) {
            echo "Sikeres regisztráció!";
        } else {
            echo "Hiba a regisztráció során: " . DataBase::$conn->error;
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
                    <input type="text" name="vezetekNev" id="vezetekNev" required>
                </div>
                <div class="field input">
                    <label for="keresztNev">Keresztnév</label>
                    <input type="text" name="keresztNev" id="keresztNev" required>
                </div>
                <div class="field input">
                    <label for="telepules">Település</label>
                    <input type="text" name="telepules" id="telepules" required>
                </div>
                <div class="field input">
                    <label for="telefonSzam">Telefonszám</label>
                    <input type="text" name="telefonSzam" id="telefonSzam" required>
                </div>
                <div class="field input">
                    <label for="email">E-mail cím</label>
                    <input type="text" name="email" id="email" required>
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
            </form>
        </div>
    </div>
</body>
</html>

