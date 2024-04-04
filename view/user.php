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

// Ellenőrizzük, hogy van-e előfizetése a felhasználónak
$sql_check_subscription = "SELECT COUNT(*) as count FROM elofizetes WHERE profil_ID = $user_id";
$result_check_subscription = $conn->query($sql_check_subscription);
$has_subscription = false;

if ($result_check_subscription && $result_check_subscription->num_rows > 0) {
    $row = $result_check_subscription->fetch_assoc();
    $has_subscription = $row['count'] > 0; // Ha a számláló több, mint 0, akkor van előfizetése
}

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
}elseif (isset($_POST['cancel_subscription'])) {
     // Kilépés előfizetésből
    // Először töröljük az előfizetéseket
    $sql_delete_subscriptions = "DELETE FROM elofizetes WHERE profil_ID = $user_id";
    if ($conn->query($sql_delete_subscriptions) === TRUE) {
        // Törlés után töröljük a felesleges sorokat az elofizetett_napok táblából
        $sql_delete_unused_subscriptions = "DELETE FROM elofizetett_napok WHERE elofizetes_ID NOT IN (SELECT elofizetes_ID FROM elofizetes)";
        if ($conn->query($sql_delete_unused_subscriptions) === TRUE) {
            $message = "Az előfizetés sikeresen megszűnt.";
            $has_subscription = false;
        } else {
            $message = "Hiba történt az előfizetett napok törlése közben: " . $conn->error;
        }
    } else {
        $message = "Hiba történt az előfizetések törlése közben: " . $conn->error;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="../css/dashboard.css" rel="stylesheet">
</head>
<body>

<?php


$logged_in = isset($_SESSION['user_id']); // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
?>




<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Menü</a>
                
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../view/menu.php">Étlap</a>
            </li>
            
        </ul>
    </div>
</nav>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                    <p class="card-text">Üdvözöllek, <?php echo $lastname . ' ' . $firstname; ?>!</p>
                    </div>
                    <div class="card-body">
                        

                        <p>Szerkezthető adatok:</p>
                        
                        <form action="" id="profileForm" class="row g-3" method="post">
                            <div class="col-12">
                                <label for="phone" class="form-label">Telefonszám:</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>" pattern="[0-9]{2}[0-9]{3}[0-9]{4}" required>
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">E-mail cím:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="save">Mentés</button>
                            <input type="submit" name="logout" class="btn btn-danger" value="Kijelentkezés">
                            <?php if ($has_subscription) : ?>
                                <button type="submit" class="btn btn-danger" name="cancel_subscription">Előfizetés lemondása</button>
                            <?php endif; ?>

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

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    </script>
</body>
</html>
