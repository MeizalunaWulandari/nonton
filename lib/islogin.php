<?php
session_start();

// Periksa apakah pengguna sudah login
if (isset($_SESSION['user_id'])) {
    $username = htmlspecialchars($_SESSION['username']);
    $message = "You are already logged in as " . $username . ".";
    $_SESSION['new_login'] = $message; // Simpan pesan dalam sesi
    header("Location: /index.php"); // Redirect ke index.php
    exit();
}

// Ambil pesan error dan pesan dari URL query atau sesi
$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
$message = isset($_SESSION['new_login']) ? htmlspecialchars($_SESSION['new_login']) : '';

// Hapus sesi new_login setelah ditampilkan
if (isset($_SESSION['new_login'])) {
    unset($_SESSION['new_login']);
}
