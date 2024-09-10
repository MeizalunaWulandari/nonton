<?php
session_start();
require_once __DIR__ . '/config.php';

// Fungsi untuk mengarahkan ke halaman login dengan pesan
function redirectToLoginWithMessage($message) {
    header("Location: /login.php?message=" . urlencode($message));
    exit();
}

// Periksa sesi pengguna
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    // Jika sesi tidak ada, periksa token di cookie
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];

        try {
            $pdo = getDbConnection();

            // Verifikasi token di database
            $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token = :token");
            $stmt->execute(['token' => $token]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Token valid, mulai sesi
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['color'] = $user['color'];
            } else {
                // Token tidak valid
                redirectToLoginWithMessage("You must login to access this page.");
            }

        } catch (Exception $e) {
            // Tangani kesalahan koneksi atau query
            redirectToLoginWithMessage("An error occurred: " . $e->getMessage());
        }
    } else {
        // Token tidak ada di cookie
        redirectToLoginWithMessage("You must login to access this page.");
    }
}

// Kode untuk halaman yang memerlukan login dilanjutkan di sini
