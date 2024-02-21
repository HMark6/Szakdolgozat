<?php
session_start();
// Ellenőrizze, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Ha nincs bejelentkezve, átirányítja a bejelentkezési oldalra
    header("Location: login.php");
    exit();
}

require('../helpers/mysql.php'); // Adatbázis kapcsolat létesítése
$conn = DataBase::getConnection();

//Felhasználó nevének és e-mail címének megjelenítése
$user_id = $_SESSION['user_id'];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
    // Ellenőrzés: Telefonszám és e-mail cím formátumának validálása
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    // TODO: Végrehajtani a validálást

    // Adatok frissítése az adatbázisban
    $sql_update = "UPDATE profil SET telefonszam='$phone', email='$email' WHERE profil_ID = $user_id";
    if ($conn->query($sql_update) === TRUE) {
        // Sikeres frissítés esetén frissítsük a megjelenített adatokat is
        $message = "Az adatok sikeresen frissültek!";
    } else {
        $message = "Hiba történt az adatok frissítése közben: " . $conn->error;
    }
}

// SQL lekérdezés az adott profilhoz tartozó vezeték- és keresztnév lekérésére
$sql = "SELECT vezeteknev, keresztnev, telefonszam, email FROM profil WHERE profil_ID = $user_id";
$result = $conn->query($sql);



if ($result && $result->num_rows > 0) {
    // Adatok kiolvasása és tárolása a változókban
    $row = $result->fetch_assoc();
    $lastname = $row['vezeteknev'];
    $firstname = $row['keresztnev'];
    $phone = $row['telefonszam'];
    $email = $row['email'];
} else {
    // Ha nem sikerült az adatok lekérdezése
    $lastname = "Hiba a lekérdezésnél a vezetéknévben!";
    $firstname = "Hiba a lekérdezésnél a keresztnévben!";
    $phone = "Hiba a lekérdezésnél a telefonszámban!";
    $email = "Hiba a lekérdezésnél az e-mail címben!";
}

//Kilépés
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        Profil
                    </div>
                    <div class="card-body">
                        <p class="card-text">Üdvözöllek, <?php echo $lastname . ' ' . $firstname; ?>!</p>
                        
                        <form action="" id="profileForm" method="post">
                            <div class="form-group">
                                <label for="phone">Telefonszám:</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>" pattern="[0-9]{2} [0-9]{3} [0-9]{4}" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail cím:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="save">Mentés</button>
                            <input type="submit" name="logout" class="btn btn-danger" value="Kijelentkezés">
                        </form>
                        <?php if (!empty($message)) : ?>
                            <div class="alert alert-success mt-3" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ellenőrzés a mentés előtt
        document.getElementById('profileForm').addEventListener('submit', function(event) {
            var phoneInput = document.getElementById('phone');
            var emailInput = document.getElementById('email');

            if (!phoneInput.checkValidity()) {
                phoneInput.classList.add('is-invalid');
                event.preventDefault();
            } else {
                phoneInput.classList.remove('is-invalid');
            }

            if (!emailInput.checkValidity()) {
                emailInput.classList.add('is-invalid');
                event.preventDefault();
            } else {
                emailInput.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>
