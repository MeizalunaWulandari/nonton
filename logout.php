<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Hapus cookie remember_token jika ada
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/', '', true, true); // Set cookie untuk expired
}

// Redirect ke halaman login dengan pesan
header("Location: /login.php?message=" . urlencode("You have been logged out."));
exit();
