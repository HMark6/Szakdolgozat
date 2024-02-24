<?php

class DataBase {
  private static $servername = "localhost";
  private static $username = "c31hadnagyM";
  private static $password = "mhaGFXiqB!35";
  private static $db = "c31hadnagyM_db";

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
