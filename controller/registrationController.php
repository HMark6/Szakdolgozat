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

    $hashed_password = hash('sha256', $password);
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
                        VALUES ('$lastname','$firstname','$city_id','$phone','$email','$hashed_password')";
       
       if ($conn->query($sql_insert) === TRUE) {
        $message = "Sikeres regisztráció!";
    } else {
        $message = "Hiba a regisztráció során: " . $conn->error;
    }
} else {
    $message = "Nem található város az adatbázisban a megadott név alapján.";
}
}
}
?>