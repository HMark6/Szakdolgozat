<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/Szakdolgozat/css/register.css" rel="stylesheet">
    <title>Regisztráció</title>
</head>
<body>
    <h2>Regisztráció</h2>
    <form action="register.php" method="post">
        <div>
            <label for="vezetekNev">Vezetéknév:</label>
            <input type="text" id="vezetekNev" name="vezetekNev" required>
        </div>
        <div>
            <label for="keresztNev">Keresztnév:</label>
            <input type="text" id="keresztNev" name="keresztNev" required>
        </div>
        <div>
            <label for="varos">Település:</label>
            <input type="text" id="varos" name="varos" required>
        </div>
        <div>
            <label for="telefonSzam">Telefonszám:</label>
            <input type="tel" id="telefonSzam" name="telefonSzam" required>
        </div>
        <div>
            <label for="email">Email cím:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Regisztráció</button>
    </form>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Adatbázis kapcsolódási adatok
    $servername = "localhost";
    $username = "admin"; // Az adatbázis felhasználóneve
    $password = "Lo83]lDv.g9-OlF"; // Az adatbázis jelszava
    $dbname = "Szakdolgozat"; // Az adatbázis neve

    // Kapcsolódás az adatbázishoz
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kapcsolat ellenőrzése
    if ($conn->connect_error) {
        die("Sikertelen kapcsolódás az adatbázishoz: " . $conn->connect_error);
    }

    // Felhasználó által megadott adatok
    $lastname = $_POST['vezetekNev'];
    $firstname = $_POST['keresztNev'];
    $city = $_POST['varos'];
    $phone = $_POST['telefonSzam'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL lekérdezés a felhasználó létezésének ellenőrzésére
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Ha már létezik a felhasználó
        echo "A megadott e-mail cím már foglalt.";
    } else {
        // Ha még nem létezik, hozzáadja az adatbázishoz
        $sql = "INSERT INTO users (lastname, firstname, city, phone, email, password) 
                VALUES ('$lastname', '$firstname', '$city', '$phone', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "Sikeres regisztráció!";
        } else {
            echo "Hiba a regisztráció során: " . $conn->error;
        }
    }
    $conn->close();
}
?>
