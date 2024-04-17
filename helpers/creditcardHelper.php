<?php
require('../model/qrcode.php');
$email = $_SESSION['email'];
echo $email;


// Függvény az adott felhasználóhoz tartozó mappa létrehozására
function createUserFolder($email) {
  $folderPath = 'users/user_' . $email;
  if (!file_exists($folderPath)) {
      if (!mkdir($folderPath, 0777, true)) {
          // Ha nem sikerült létrehozni a mappát, kezeljük a hibát
          die('Nem sikerült létrehozni a mappát: ' . $folderPath);
      }
  }
}

// Függvény egyedi QR kód generálására és mentésére az adott felhasználó mappájába
function generateAndSaveQRCode($email, $qrCodeData) {
  // Ellenőrizzük, hogy a felhasználó mappa létezik-e, ha nem, létrehozzuk
  createUserFolder($email);


  // Generálunk egyedi fájlnevet a QR kódnak
  $fileName = uniqid('qr_code_'.$email . '_') . '.png';
  $filePath = 'users/user_' . $email . '/' . $fileName;
  
  // QR kód generálása és mentése
  $qrGenerator = new QRCodeGenerator();
  $base64Data = $qrGenerator->generateQRCodeBase64($qrCodeData);
  if (file_put_contents($filePath, base64_decode(substr($base64Data, strpos($base64Data, ',') + 1)))) {
      return $fileName; // Sikeres mentés esetén visszaadjuk a fájlnevet
  } else {
      // Ha nem sikerült menteni a fájlt, kezeljük a hibát
      die('Nem sikerült menteni a fájlt: ' . $filePath);
  }
}

// Példa felhasználók azonosítói és hozzájuk tartozó QR kód adatok
$usersQRCodeData = array(
  $email => array('QR code data 1', 'QR code data 2', 'QR code data 3','QR code data 4', 'QR code data 5'),

);

// Minden felhasználóhoz generálunk egyedi QR kódokat
foreach ($usersQRCodeData as $userEmail => $qrCodeDataArray) {
  foreach ($qrCodeDataArray as $qrCodeData) {
      generateAndSaveQRCode($userEmail, $qrCodeData);
  }
}

    // Adatbázis kapcsolat bezárása
    $conn->close();
?>