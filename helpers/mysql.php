<?php

class DataBase {
  private static $servername = "localhost";
  private static $username = "admin";
  private static $password = "Lo83]lDv.g9-OlFK";
  private static $db = "Szakdolgozat";

  public static function getConnection() {
      $conn = new mysqli(self::$servername, self::$username, self::$password, self::$db);

      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      $conn->set_charset("utf8");

      return $conn;
  }
}



?>
