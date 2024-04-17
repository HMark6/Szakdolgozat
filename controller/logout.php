<?php
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (isset($_SESSION['user_id'])) {
    // Ha be van jelentkezve, akkor töröljük a session változókat
    session_unset();
    session_destroy();
}

// Átirányítás az index oldalra
header("Location: ../index.php");
exit();
?>
