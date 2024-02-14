<?php
session_start(); // Session kezelésének indítása
require('../helpers/mysql.php'); // Adatbázis kapcsolat létesítése
$conn = DataBase::getConnection();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];


    // Ellenőrizze az e-mail cím és a jelszó mezők kitöltöttségét
    if (empty($email) || empty($password)) {
        $message = "Kérem, töltse ki mindkét mezőt.";

    }else{
    // Ellenőrizze az e-mail cím és a jelszó egyezését az adatbázisban
    $sql = "SELECT * FROM `profil` WHERE `email`='$email' AND `jelszo`='$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Sikeres bejelentkezés
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['profil_ID'];
        $_SESSION['email'] = $row['email'];
        // További műveletek, például átirányítás a felhasználó saját oldalára
        header("Location: ../index.php"); // Cserélje ki erre az oldalra, amely a felhasználó vezérlőpultja
        exit();
    } else {
        // Sikertelen bejelentkezés
        $message = "Hibás e-mail cím vagy jelszó.";
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
    <title>Bejelentkezés</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header>Bejelentkezés</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="field input">
                    <label for="password">Jelszó</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Bejelentkezés" required>
                </div>
                <div class="links">
                    Nincs még fiókod? <a href="\Szakdolgozat\view\register.php">Regisztrálás</a>
                </div>
                    <?php if (!empty($message)) : ?>
                        <div class="error-message"><?php echo $message; ?></div>
                    <?php endif; ?> 
            </form>
        </div>
        
    </div>
    
</body>
</html>

