<?php
    session_start();
    require('../helpers/mysql.php');
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
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['profil_ID'];
            $_SESSION['email'] = $row['email'];
            header("Location: ../index.php");
            exit();
        } else {
            $message = "Hibás e-mail cím vagy jelszó.";
        }
        }
    }
    ?>