<?php
    class Database {
        // Database connection parameters
        private static $host = "localhost";
        private static $dbname = "pr_ind_db";
        private static $username = "root";
        private static $password = "";
        private static $conn = null;

        public static function connect() {
            if (self::$conn === null) {
                self::$conn = new mysqli(self::$host, self::$username, self::$password, self::$dbname);
                if (self::$conn->connect_error) {
                    die("Connection failed: " . self::$conn->connect_error);
                }
            }
            return self::$conn;
        }

        public static function close() {
            if (self::$conn !== null) {
                self::$conn->close();
                self::$conn = null;
            }
        }
    }
?>