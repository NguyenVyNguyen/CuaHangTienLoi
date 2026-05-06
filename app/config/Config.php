<?php

class Config {
    private static string $connectionString = "";

    public static function init($conn) {
        self::$connectionString = $conn;
    }

    public static function getConnectionString() {
        return self::$connectionString;
    }
}