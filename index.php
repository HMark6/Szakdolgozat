<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Főoldal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
</head>
<body>

<?php
session_start();

$logged_in = isset($_SESSION['user_id']);
?>

<?php
// Ellenőrizd, hogy a felhasználó be van-e jelentkezve
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    
    // Ellenőrizd, hogy van-e QR kód a felhasználónak
    $userFolderPath = 'view/users/user_' . $email;
    $hasQRCodes = file_exists($userFolderPath) && !empty(glob($userFolderPath . '/*.png'));
}
?>



<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
    <a class="navbar-brand">Menü</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="view/menu.php">Étlap</a>
            </li>
            <?php if ($logged_in) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="view/user.php">Profil</a>
                </li>
            <?php endif; ?>

            <?php if (isset($hasQRCodes) && $hasQRCodes) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="view/qrcodeview.php">QR kódjai</a>
                </li>
            <?php endif; ?>
            <?php if ($logged_in) : ?>
                <!-- Ha a felhasználó be van jelentkezve, akkor megjelenítjük a Kijelentkezés lehetőséget -->
                <li class="nav-item">
                    <a class="nav-link" href="controller/logout.php">Kijelentkezés</a>
                </li>
            <?php else: ?>
                <!-- Ha a felhasználó nincs bejelentkezve, akkor megjelenítjük a Bejelentkezés/Regisztráció lehetőséget -->
                <li class="nav-item">
                    <a class="nav-link" href="view/login.php">Bejelentkezés/Regisztráció</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <h2>Iskolai menza</h2>
            <p>Az iskolai menza nemcsak egy hely a diákoknak, ahol étkezhetnek, hanem egy fontos közösségi tér is. Itt találkoznak barátaikkal, beszélgetnek egymással, és együtt élvezik az ízletes ételeket. Az iskolai menza kiemelt figyelmet fordít az egészséges étkezésre, biztosítva a diákoknak azokat az ételeket, amelyek szükségesek az egészséges növekedéshez és fejlődéshez.</p>
            <p>A menü kialakításánál figyelembe veszik az egészséges táplálkozás alapelveit, és próbálnak változatos, kiegyensúlyozott ételeket kínálni, amelyek megfelelnek a különböző étkezési preferenciáknak és diétáknak is. Emellett fontosnak tartják a friss, minőségi alapanyagok felhasználását és az ételkészítés higiénés szabályainak betartását.</p>
            <p>Az iskolai menza nemcsak az étkezésről szól, hanem egyben a diákok közösségi életének fontos része is. Itt találkoznak, megosztják egymással a mindennapi élményeiket, és lehetőségük van új barátságokat kötni. Az étkezőkön túl a menza teret ad különböző programoknak, rendezvényeknek is, amelyek tovább színesítik az iskolai életet.</p>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <img src="pictures/ebedlo1.jpg" alt="Iskolai menza" class="img-fluid rounded float-start mb-3">
                </div>
                <div class="col-md-6">
                    <img src="pictures/gyerekek1.jpg" alt="Iskolai menza, gyerekek" class="img-fluid rounded float-start mb-3">
                </div>
                <div class="col-md-6">
                    <img src="pictures/gyerekek2.jpg" alt="Iskolai menza, gyerekek" class="img-fluid rounded float-start mb-3">
                </div>
                <div class="col-md-6">
                    <img src="pictures/ebedlo2.jpg" alt="Iskolai menza" class="img-fluid rounded float-start mb-3">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <img src="pictures/etel1.jpg" alt="Iskolai menza" class="img-fluid rounded float-start mb-3">
                </div>
                <div class="col-md-6">
                    <img src="pictures/etel2.jpg" alt="Iskolai menza, gyerekek" class="img-fluid rounded float-start mb-3">
                </div>
                <div class="col-md-6">
                    <img src="pictures/etel4.jpg" alt="Iskolai menza, gyerekek" class="img-fluid rounded float-start mb-3">
                </div>
                <div class="col-md-6">
                    <img src="pictures/etel3.jpg" alt="Iskolai menza" class="img-fluid rounded float-start mb-3">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h2>Iskolai menza</h2>
            <p>Az iskolai menzánk elkötelezett a friss és egészséges összetevők használata mellett minden ételünkben. Tudjuk, hogy a gyermekek fejlődésének és tanulmányi teljesítményének alapja a megfelelő táplálkozás. Ezért minden nap friss alapanyagokat válogatunk, hogy olyan ételeket készítsünk, amelyek gazdagok vitaminokban, ásványi anyagokban és egyéb tápanyagokban. Célunk, hogy a gyerekek ne csak jóllakjanak, hanem energikusak és egészségesek legyenek az iskolai nap során.</p>
            <p>Az itt kínált ételek minősége és tápanyagtartalma számunkra kiemelten fontos. Minden fogásunkat úgy tervezzük meg, hogy kiegyensúlyozott tápanyagokban gazdag legyen, és segítse a gyermekek egészséges fejlődését. Az ételeinkben kizárólag olyan alapanyagokat használunk, amelyek megfelelnek a szigorú minőségi és biztonsági előírásoknak. Ezáltal biztosítjuk, hogy az iskolánkban fogyasztott ételek mindig magas minőségűek és táplálóak legyenek.</p>
            <p>Tisztában vagyunk azzal, hogy minden gyermek más és más táplálkozási igényekkel rendelkezik. Ezért igyekszünk széles választékot kínálni étlapunkon, amely lehetővé teszi a személyre szabott választást és az egészséges étkezési szokások kialakítását. Szakácsaink és dietetikusaink közösen dolgoznak azon, hogy olyan ételeket készítsenek, amelyek egészségesek, ízletesek és változatosak. Így minden gyermek megtalálhatja az számára legmegfelelőbb ételt az iskolai menzánkban.</p>
        </div>
        
    </div>
</div>

<?php include 'view/footer.php'; ?>



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
