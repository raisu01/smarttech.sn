<?php
class Database {
    private static $host = "localhost";
    private static $dbname = "smarttech_db";
    private static $username = "root";
    private static $password = "Root";
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            self::$conn = new mysqli(self::$host, self::$username, self::$password, self::$dbname);
            
            if (self::$conn->connect_error) {
                die("❌ Erreur de connexion à la base de données : " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }
}
?>
