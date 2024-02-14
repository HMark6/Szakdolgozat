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

// Példa: Felhasználó nevének és e-mail címének megjelenítése
$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

// SQL lekérdezés az adott profilhoz tartozó vezeték- és keresztnév lekérésére
$sql = "SELECT vezeteknev, keresztnev FROM profil WHERE profil_ID = $user_id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // Adatok kiolvasása és tárolása a változókban
    $row = $result->fetch_assoc();
    $lastname = $row['vezeteknev'];
    $firstname = $row['keresztnev'];
} else {
    // Ha nem sikerült az adatok lekérdezése, használj alapértelmezett értékeket vagy kezelj hibát
    $lastname = "Hiba a lekérdezésnél a vezetéknévben!";
    $firstname = "Hiba a lekérdezésnél a keresztnévben!";
}

// Példa: Kilépés a munkamenetből
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
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/user_dashboard.css" rel="stylesheet">
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
                        <form action="" method="post">
                            <input type="submit" name="logout" class="btn btn-danger" value="Kijelentkezés">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
