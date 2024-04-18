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

        $hashed_password = hash('sha256', $password);
        // Ellenőrizze az e-mail cím és a jelszó egyezését az adatbázisban
        $sql = "SELECT * FROM `profil` WHERE `email`='$email' AND `jelszo`='$hashed_password'";
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