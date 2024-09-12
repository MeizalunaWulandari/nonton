<?php

require_once __DIR__ . '/ceklogin.php';
require_once __DIR__ . '/config.php';

// Fungsi untuk membuat slug otomatis
function generateSlug($userId) {
    return md5(time() . $userId);
}

try {
    // Dapatkan koneksi database dari config.php
    $pdo = getDbConnection();

    // Aktifkan mode exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Pastikan user sudah login
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in.');
    }

    // Ambil dan sanitasi data dari form
    $title = isset($_POST['title']) ? htmlspecialchars($_POST['title']) : null;
    $manifest = isset($_POST['manifest']) ? htmlspecialchars($_POST['manifest']) : null;
    $kid = isset($_POST['kid']) ? htmlspecialchars($_POST['kid']) : null;
    $key = isset($_POST['key']) ? htmlspecialchars($_POST['key']) : null;
    $userId = $_SESSION['user_id'];

    // Validasi data
    if (empty($title)) {
        throw new Exception('Title is required.');
    }

    // Generate slug otomatis
    $slug = generateSlug($userId);

    // Query untuk memasukkan data ke tabel channels
    $sql = "INSERT INTO channels (slug, title, manifest, kid, `key`, owner) 
            VALUES (:slug, :title, :manifest, :kid, :key, :owner)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':slug' => $slug,
        ':title' => $title,
        ':manifest' => $manifest, // Atur sesuai kebutuhan Anda
        ':kid' => $kid,
        ':key' => $key,
        ':owner' => $userId
    ]);

    // Redirect dengan pesan sukses
    header('Location: /addchannel.php?message=Channel added successfully');
    exit();

} catch (Exception $e) {
    // Redirect dengan pesan error
    header('Location: /addchannel.php?error=' . urlencode($e->getMessage()));
    exit();
}
