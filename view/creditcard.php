<?php

session_start(); // Session kezelésének indítása
require('../helpers/mysql.php'); // Adatbázis kapcsolat létesítése
$conn = DataBase::getConnection();

// Étkezési díjak lekérdezése és összegzése
$etkezes_dijak_sql = "SELECT SUM(etkezesidij) AS osszeg FROM etkezesek";
$result = $conn->query($etkezes_dijak_sql);
$osszeg = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $osszeg = $row['osszeg'];
}

if (isset($_POST['submit'])) {
    // Felhasználó azonosítójának lekérdezése a sessionból
    $user_id = $_SESSION['user_id'];

    // Ellenőrizzük, hogy van-e már előfizetése a felhasználónak
    $check_subscription_sql = "SELECT * FROM elofizetes WHERE profil_ID = '$user_id'";
    $subscription_result = $conn->query($check_subscription_sql);

    if ($subscription_result->num_rows > 0) {
        echo '<div class="alert alert-danger" role="alert">Már van aktív előfizetésed, nem tudsz újat létrehozni!</div>';
    } else {
        // Jelenlegi időpont lekérdezése
        $befizetes_datum = date('Y-m-d H:i:s');

        // A lemondás dátuma null értéket kap
        $lemondas_datum = null;

        // Lekérdezzük az étkezési dátumokat az etkezesek táblából
        $etkezes_sql = "SELECT etkezes_datum FROM etkezesek";
        $result = $conn->query($etkezes_sql);

        if ($result->num_rows > 0) {
            // Ellenőrizzük az étkezési dátumokat
            $allowed_to_subscribe = true;
            while ($row = $result->fetch_assoc()) {
                $etkezes_datum = $row['etkezes_datum'];
                
                // Ha az aktuális dátum nagyobb vagy egyenlő az étkezési dátumnál,
                // akkor ne engedélyezzük az előfizetést
                if (strtotime(date('Y-m-d')) >= strtotime($etkezes_datum)) {
                    $allowed_to_subscribe = false;
                    break; // Kilépünk a ciklusból, ha már találtunk egy olyan étkezést, amely már elkezdődött
                }
            }

            if ($allowed_to_subscribe) {
                // Beszúrjuk az étkezési dátumokat az elofizetett_napok táblába
                $etkezes_sql = "SELECT etkezes_datum FROM etkezesek";
                $result = $conn->query($etkezes_sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $etkezes_datum = $row['etkezes_datum'];

                        // Beszúrjuk az új sort az elofizetett_napok táblába
                        $insert_sql = "INSERT INTO elofizetett_napok (elofizetes_ID, etkezes_datum) VALUES (NULL, '$etkezes_datum')";
                        if ($conn->query($insert_sql) !== TRUE) {
                            echo "Hiba az étkezési nap hozzáadásakor: " . $conn->error;
                        }
                    }
                }

                // Az előfizetett_napok táblából lekérjük az elofizetes_ID-ket
                $elofizetes_sql = "SELECT elofizetes_ID FROM elofizetett_napok";
                $elofizetes_result = $conn->query($elofizetes_sql);

                if ($elofizetes_result->num_rows > 0) {
                    // Beszúrjuk az előfizetéseket az elofizetes táblába
                    while ($elofizetes_row = $elofizetes_result->fetch_assoc()) {
                        $elofizetes_id = $elofizetes_row['elofizetes_ID'];

                        $insert_elofizetes_sql = "INSERT INTO elofizetes (profil_ID, elofizetes_ID, befizetes_datum, lemondas_datum) VALUES ('$user_id', '$elofizetes_id', '$befizetes_datum', NULL)";
                        if ($conn->query($insert_elofizetes_sql) !== TRUE) {
                            echo "Hiba az előfizetés hozzáadásakor: " . $conn->error;
                        }
                    }
                    echo '<div class="alert alert-success" role="alert">Az előfizetés sikeres volt!</div>';
                } else {
                    echo "Nincsenek előfizetett napok az előfizetett_napok táblában.";
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">A heti étkezés befizetéséről sajnos lemaradtál!</div>';
            }
        } else {
            echo "Nincsenek étkezési napok az etkezesek táblában.";
        }
    }

    // Adatbázis kapcsolat bezárása
    $conn->close();
}
?>






<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fizetési Felület</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/creditcard.css" rel="stylesheet">
</head>
<body>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Fizetés</h5>
        </div>
        <div class="card-body">
          <form id="payment-form" method="post" onsubmit="return validateForm()">

            <div class="mb-3">
              <label for="amount-to-pay" class="form-label">Fizetendő összeg</label>
              <p class="form-control-static"><?php echo $osszeg; ?> Ft</p>
            </div>
            <div class="mb-3 position-relative">
              <label for="card-holder-name" class="form-label">Kártyatulajdonos Neve</label>
              <input type="text" class="form-control" id="card-holder-name" name="card_holder_name" placeholder="John Doe" required>
              <small id="name-error" class="text-danger d-none">A név nem tartalmazhat számot.</small>
            </div>

            <div class="mb-3 position-relative">
              <label for="card-number" class="form-label">Kártyaszám</label>
              <div class="input-group">
                  <input type="text" class="form-control" id="card-number" name="card_number" placeholder="1234-5678-9012-3456" required oninput="formatCardNumber(this)">
                  <span class="input-group-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card" viewBox="0 0 16 16">
                      <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z"/>
                      <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
                    </svg>
                  </span>
              </div>
                <small id="card-error" class="text-danger d-none">A kártyaszámnak 16 számjegyből kell állnia.</small>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="expiration-date" class="form-label">Lejárati dátum</label>
                <div class="input-group">
                  <input type="text" class="form-control" id="expiration-date" name="expiration_date" placeholder="YY/MM" required oninput="formatExpirationDate(this)">
                  <span class="input-group-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                      <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                    </svg>
                  </span>
                </div>
                <small id="date-error" class="text-danger d-none">Helytelen dátumformátum (pl. YY/MM).</small>
              </div>
              <div class="col-md-6 mb-3 position-relative">
                <label for="cvv" class="form-label">CVV</label>
                <div class="input-group">
                  <input type="password" class="form-control" id="cvv" name="cvv" placeholder="123" required>
                  <span class="input-group-text">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                      <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/>
                    </svg>
                  </span>
                </div>
                <small id="cvv-error" class="text-danger d-none">A CVV kód 3 számjegyből kell állnia.</small>
              </div>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Fizetés</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

function formatCardNumber(input) {
    // Távolítsuk el az összes nem szám karaktert (kivéve a kötőjelet)
    var value = input.value.replace(/\D/g, '');

    // Ha az érték hossza nagyobb, mint 16, vegyük csak az első 16 karaktert
    value = value.substring(0, 16);

    // Helyezzünk kötőjelet minden negyedik karakter után, ha az érték hossza nagyobb, mint 4
    if (value.length > 4) {
        value = value.match(new RegExp('.{1,4}', 'g')).join('-');
    }

    // Állítsuk be az input mező értékét formázva
    input.value = value;
}

function formatExpirationDate(input) {
    var value = input.value.replace(/\D/g, ''); // Csak számokat engedélyezünk
    var year = value.substring(0, 2); // Az első két számjegy a hónap
    var month = value.substring(2, 4); // A harmadik és negyedik számjegy az év

    // Ha a hónap vagy év túl nagy, korlátozzuk azokat
    if (parseInt(year) > 99) year = '99';
    if (parseInt(month) > 12) month = '12';
    
    // Állítsuk be az input mező értékét formázva
    input.value = year + '/' + month;
}

  function validateForm() {
    var name = document.getElementById("card-holder-name").value;
    var cardNumber = document.getElementById("card-number").value.replace(/\D/g, ''); // Kártyaszám formázás előtti érték
    var expirationDate = document.getElementById("expiration-date").value;
    var cvv = document.getElementById("cvv").value;

    var namePattern = /^[a-zA-Z\s]*$/; // csak betűket és szóközöket engedélyez
    var cardNumberPattern = /^\d{16}$/; // 16 számjegyből álló sztring
    var expirationDatePattern = /^\d{2}\/\d{2}$/;// YY/MM formátum
    var cvvPattern = /^\d{3}$/; // 3 számjegyből áll

    var isValid = true;

    if (!namePattern.test(name)) {
      document.getElementById("name-error").classList.remove("d-none");
      isValid = false;
    } else {
      document.getElementById("name-error").classList.add("d-none");
    }

    if (!cardNumberPattern.test(cardNumber)) {
      document.getElementById("card-error").classList.remove("d-none");
      isValid = false;
    } else {
      document.getElementById("card-error").classList.add("d-none");
    }

    if (!expirationDatePattern.test(expirationDate)) {
      document.getElementById("date-error").classList.remove("d-none");
      isValid = false;
    } else {
      document.getElementById("date-error").classList.add("d-none");
    }

    if (!cvvPattern.test(cvv)) {
      document.getElementById("cvv-error").classList.remove("d-none");
    } else {
        document.getElementById("cvv-error").classList.add("d-none");
      }

    return isValid;
  }

</script>

</body>
</html>
</script>

</body>
</html>