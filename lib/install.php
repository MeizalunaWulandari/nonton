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
        email VARCHAR(64) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,           -- Gunakan VARCHAR(255) untuk menampung hasil hash dari kata sandi
        unhash_password VARCHAR(255),             -- Disarankan untuk tidak menyimpan kata sandi yang tidak ter-hash. Hapus kolom ini jika tidak diperlukan.
        role TINYINT(1) NOT NULL DEFAULT 0,       -- 0 untuk user, 1 untuk admin
        adult TINYINT(1) NOT NULL DEFAULT 0,      -- 0 untuk konten umum, 1 untuk konten dewasa
        email_verified TINYINT(1) NOT NULL DEFAULT 0, -- Status verifikasi email
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL,                -- Tambahkan kolom untuk menyimpan waktu login terakhir jika dibutuhkan
        device_hash VARCHAR(64)
    ) ENGINE=INNODB;",
        // Tambahkan query untuk tabel lain sesuai kebutuhan
    ];

    // Eksekusi setiap query dalam array
    foreach ($queries as $query) {
        $pdo->exec($query);
    }
}

try {
    // Dapatkan koneksi database dari config.php
    $pdo = getDbConnection();
    echo "Connected to the database successfully." . PHP_EOL;

    // Panggil fungsi untuk membuat tabel
    createTables($pdo);
    echo "Tables created successfully." . PHP_EOL;

} catch (Exception $e) {
    // Tampilkan pesan error jika terjadi kegagalan
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
