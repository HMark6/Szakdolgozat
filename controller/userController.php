<?php
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require('../helpers/mysql.php');
$conn = DataBase::getConnection();

$user_id = $_SESSION['user_id'];

$sql_check_subscription = "SELECT COUNT(*) as count FROM elofizetes WHERE profil_ID = $user_id";
$result_check_subscription = $conn->query($sql_check_subscription);
$has_subscription = false;

if ($result_check_subscription && $result_check_subscription->num_rows > 0) {
    $row = $result_check_subscription->fetch_assoc();
    $has_subscription = $row['count'] > 0;
}


// Ellenőrizze, hogy az előfizetés lemondására van-e kérés
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cancel_subscription'])) {
        // Legkorábbi étkezési dátum lekérdezése az elofizetett_napok táblából
        $sql_min_meal_date = "SELECT MIN(etkezes_datum) AS min_meal_date FROM elofizetett_napok 
                              WHERE elofizetes_ID IN (SELECT elofizetes_ID FROM elofizetes WHERE profil_ID = $user_id)";
        $result_min_meal_date = $conn->query($sql_min_meal_date);

        if ($result_min_meal_date && $result_min_meal_date->num_rows > 0) {
            $row_min_meal_date = $result_min_meal_date->fetch_assoc();
            $min_meal_date = strtotime($row_min_meal_date['min_meal_date']);
            $today = strtotime('today');
            // Ellenőrizze, hogy az előfizetés lemondásának dátuma legalább egy nappal az első étkezés előtt van-e
            if ($today < $min_meal_date) {
                $cancel_date = date("Y-m-d H:i:s");
                $sql_update_subscription = "UPDATE elofizetes SET lemondas_datum = '$cancel_date' WHERE profil_ID = $user_id";
                if ($conn->query($sql_update_subscription) === TRUE) {
                    $message = "Az előfizetés sikeresen lemondva!";
                } else {
                    $message = "Hiba történt az előfizetés lemondása közben: " . $conn->error;
                }
            } else {
                $message = "Az előfizetés lemondását csak az első étkezés dátumát megelőzően legalább egy nappal teheted meg!";
            }
        } else {
            $message = "Nem található legkorábbi étkezési dátum!";
        }
    }
}





if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $sql_update = "UPDATE profil SET telefonszam='$phone', email='$email' WHERE profil_ID = $user_id";
    if ($conn->query($sql_update) === TRUE) {
        $message = "Az adatok sikeresen frissültek!";
    } else {
        $message = "Hiba történt az adatok frissítése közben: " . $conn->error;
    } 
}


$sql = "SELECT vezeteknev, keresztnev, telefonszam, email FROM profil WHERE profil_ID = $user_id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lastname = $row['vezeteknev'];
    $firstname = $row['keresztnev'];
    $phone = $row['telefonszam'];
    $email = $row['email'];
} else {
    $lastname = "Hiba a lekérdezésnél a vezetéknévben!";
    $firstname = "Hiba a lekérdezésnél a keresztnévben!";
    $phone = "Hiba a lekérdezésnél a telefonszámban!";
    $email = "Hiba a lekérdezésnél az e-mail címben!";
}

$sql_check_subscription_cancelled = "SELECT lemondas_datum FROM elofizetes WHERE profil_ID = $user_id";
$result_check_subscription_cancelled = $conn->query($sql_check_subscription_cancelled);
$subscription_cancelled = false;

if ($result_check_subscription_cancelled && $result_check_subscription_cancelled->num_rows > 0) {
    $row_subscription_cancelled = $result_check_subscription_cancelled->fetch_assoc();
    $subscription_cancelled = !empty($row_subscription_cancelled['lemondas_datum']);
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>