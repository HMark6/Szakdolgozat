<?php

session_start(); // Session kezelésének indítása
require('../helpers/mysql.php'); // Adatbázis kapcsolat létesítése
$conn = DataBase::getConnection();

if (isset($_POST['submit'])) {
    // Felhasználó azonosítójának lekérdezése a sessionból
    $user_id = $_SESSION['user_id'];

    // Jelenlegi időpont lekérdezése
    $befizetes_datum = date('Y-m-d H:i:s');

    // A lemondás dátuma null értéket kap
    $lemondas_datum = null;

    // SQL lekérdezés előkészítése és végrehajtása az előfizetés hozzáadására
    $sql = "INSERT INTO elofizetes (profil_ID, befizetes_datum, lemondas_datum) VALUES ('$user_id', '$befizetes_datum', NULL)";

    if ($conn->query($sql) === TRUE) {
        $elofizetes_id = $conn->insert_id; // Az új előfizetési ID lekérése

        echo "Az előfizetés sikeresen hozzáadva az előfizetések táblához.";

        // Lekérdezzük az étkezési dátumokat az etkezesek táblából
        $etkezes_sql = "SELECT etkezes_datum FROM etkezesek";
        $result = $conn->query($etkezes_sql);

        if ($result->num_rows > 0) {
            // Beszúrjuk az étkezési dátumokat az elofizetett_napok táblába
            while ($row = $result->fetch_assoc()) {
                $etkezes_datum = $row['etkezes_datum'];

                // Ellenőrizzük, hogy az előfizetési azonosítóhoz és étkezési dátumhoz már létezik-e sor az elofizetett_napok táblában
                $check_existing_sql = "SELECT * FROM elofizetett_napok WHERE elofizetes_ID = '$elofizetes_id' AND etkezes_datum = '$etkezes_datum'";
                $existing_result = $conn->query($check_existing_sql);

                if ($existing_result->num_rows == 0) {
                    // Ha nem létezik ilyen páros, akkor beszúrjuk az új párost az elofizetett_napok táblába
                    $insert_sql = "INSERT INTO elofizetett_napok (elofizetes_ID, etkezes_datum) VALUES ('$elofizetes_id', '$etkezes_datum')";
                    if ($conn->query($insert_sql) !== TRUE) {
                        echo "Hiba az étkezési nap hozzáadásakor: " . $conn->error;
                    }
                }
            }
            echo "Az étkezési napok sikeresen hozzáadva az előfizetett_napok táblához.";
        } else {
            echo "Nincsenek étkezési napok az etkezesek táblában.";
        }
    } else {
        echo "Hiba az előfizetés hozzáadásakor: " . $conn->error;
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
          <form id="payment-form" method="post">

            <div class="mb-3">
              <label for="amount-to-pay" class="form-label">Fizetendő összeg</label>
              <p class="form-control-static">10000 Ft</p>
            </div>
            <div class="mb-3 position-relative">
              <label for="card-holder-name" class="form-label">Kártyatulajdonos Neve</label>
              <input type="text" class="form-control" id="card-holder-name" name="card_holder_name" placeholder="John Doe" required>
              <small id="name-error" class="text-danger d-none">A név nem tartalmazhat számot.</small>
            </div>
            <div class="mb-3 position-relative">
              <label for="card-number" class="form-label">Kártyaszám</label>
              <input type="text" class="form-control" id="card-number" name="card_number" placeholder="1234 5678 9012 3456" required>
              <small id="card-error" class="text-danger d-none">A kártyaszámnak 16 számjegyből kell állnia.</small>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="expiration-date" class="form-label">Lejárati dátum</label>
                <input type="text" class="form-control" id="expiration-date" name="expiration_date" placeholder="MM/YY" required>
                <small id="date-error" class="text-danger d-none">Helytelen dátumformátum (pl. YY/MM).</small>
              </div>
              <div class="col-md-6 mb-3 position-relative">
                <label for="cvv" class="form-label">CVV</label>
                <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123" required>
                <small id="cvv-error" class="text-danger d-none">A CVV kód 3 vagy 4 számjegyből kell állnia.</small>
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
  function validateForm() {
    var name = document.getElementById("card-holder-name").value;
    var cardNumber = document.getElementById("card-number").value;
    var expirationDate = document.getElementById("expiration-date").value;
    var cvv = document.getElementById("cvv").value;

    var namePattern = /^[a-zA-Z\s]*$/; // csak betűket és szóközöket engedélyez
    var cardNumberPattern = /^\d{4} \d{4} \d{4} \d{4}$/; // 16 számjegyből álló sztring
    var expirationDatePattern = /^\d{2}\/(0[1-9]|1[0-2])$/;// MM/YY formátum
    var cvvPattern = /^\d{3,4}$/; // 3 vagy 4 számjegyből áll

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
