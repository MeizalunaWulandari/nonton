<?php

require_once __DIR__ . '/loadenv.php';

// Koneksi Database
function getDbConnection() {
    $dbHost = getenv('DB_HOST');
    $dbPort = getenv('DB_PORT');
    $dbUsername = getenv('DB_USERNAME');
    $dbPassword = getenv('DB_PASSWORD');
    $dbDatabase = getenv('DB_DATABASE');
    $caPath = __DIR__ . '/' . getenv('CA_PATH');

    try {
        $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbDatabase;charset=utf8mb4";
        $options = [
            PDO::MYSQL_ATTR_SSL_CA => $caPath, // Menambahkan CA Path untuk SSL
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        $pdo = new PDO($dsn, $dbUsername, $dbPassword, $options);
        return $pdo;
    } catch (PDOException $e) {
        throw new Exception("Database connection failed: " . $e->getMessage());
    }


}
