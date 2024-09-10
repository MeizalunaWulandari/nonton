<?php

require_once __DIR__ . '/config.php';

// Fungsi untuk membuat tabel
function createTables($pdo) {

    // Query SQL untuk membuat tabel "users"
    $queries = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(32) NOT NULL,
        username VARCHAR(24) NOT NULL UNIQUE,
        color VARCHAR(24),                        -- Kolom untuk warna, tanpa nilai default
        email VARCHAR(64) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,           -- Menampung hash kata sandi
        unhash_password VARCHAR(255),             -- Disarankan untuk dihapus jika tidak diperlukan
        role TINYINT(1) NOT NULL DEFAULT 0,       -- 0 untuk user, 1 untuk admin
        adult TINYINT(1) NOT NULL DEFAULT 0,      -- 0 untuk konten umum, 1 untuk konten dewasa
        email_verified TINYINT(1) NOT NULL DEFAULT 0, -- Status verifikasi email
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        remember_token VARCHAR(64) NULL;
        last_login TIMESTAMP NULL,                -- Kolom untuk waktu login terakhir
        device_hash VARCHAR(64)
    ) ENGINE=INNODB;",
    "CREATE TABLE IF NOT EXISTS chats (
        id INT AUTO_INCREMENT PRIMARY KEY,
        color VARCHAR(24),                        -- Kolom untuk warna, tanpa nilai default
        channel_id VARCHAR(50) NOT NULL,         -- Misalnya 'channel1', 'channel2', dll.
        user_id INT NOT NULL,                    -- ID user yang mengirim pesan
        message TEXT NOT NULL,                   -- Isi pesan chat
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Waktu pesan dikirim
        FOREIGN KEY (user_id) REFERENCES users(id)  -- Relasi dengan tabel users
    ) ENGINE=INNODB;"
];


    // Eksekusi setiap query dalam array
    foreach ($queries as $query) {
        $pdo->exec($query);
    }
}

try {
    // Dapatkan koneksi database dari config.php
    $pdo = getDbConnection();

    // Aktifkan mode exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to the database successfully." . PHP_EOL;

    // Panggil fungsi untuk membuat tabel
    createTables($pdo);
    echo "Tables created successfully." . PHP_EOL;

} catch (Exception $e) {
    // Tampilkan pesan error jika terjadi kegagalan
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
