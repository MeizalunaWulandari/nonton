<?php

function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception("File .env not found at $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2) + [NULL, NULL];
        if ($key && $value !== NULL) {
            putenv(trim("$key=$value"));
            $_ENV[trim($key)] = trim($value);
        }
    }
}

loadEnv(__DIR__ . '/.env');

// Koneksi Database
function getDbConnection() {
    $dbHost = getenv('DB_HOST');
    $dbPort = getenv('DB_PORT');
    $dbUsername = getenv('DB_USERNAME');
    $dbPassword = getenv('DB_PASSWORD');
    $dbDatabase = getenv('DB_DATABASE');
    $caPath = getenv('CA_PATH');

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
