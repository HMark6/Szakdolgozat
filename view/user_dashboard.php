<?php
session_start();
// Ellenőrizze, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Ha nincs bejelentkezve, átirányítja a bejelentkezési oldalra
    header("Location: login.php");
    exit();
}

// Itt bármilyen további műveletet végezhet, például felhasználói adatok betöltése az adatbázisból

// Példa: Felhasználó nevének és e-mail címének megjelenítése
$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

// Példa: Kilépés a munkamenetből
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felhasználói Vezérlőpult</title>
</head>
<body>
    <h1>Felhasználói Vezérlőpult</h1>
    <p>Üdvözöllek, <?php echo $email; ?>!</p>
    <form action="" method="post">
        <input type="submit" name="logout" value="Kijelentkezés">
    </form>
</body>
</html>
