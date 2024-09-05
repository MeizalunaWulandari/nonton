<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/islogin.php';

function redirectToLoginWithError($error) {
    header("Location: /login.php?error=" . urlencode($error));
    exit();
}

function redirectToIndexWithMessage($message) {
    session_start();
    $_SESSION['new_login'] = $message;
    header("Location: /index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari formulir
    $data = [
        'username' => trim(htmlspecialchars($_POST['username'])) ?? null,
        'password' => trim(htmlspecialchars($_POST['password'])) ?? null,
    ];

    // Validasi data
    if (empty($data['username']) || empty($data['password'])) {
        redirectToLoginWithError("Username and password are required.");
    }

    try {
        $pdo = getDbConnection();

        // Cek apakah username ada
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $data['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            redirectToLoginWithError("Invalid username or password.");
        }

        // Verifikasi password
        if (!password_verify($data['password'], $user['password'])) {
            redirectToLoginWithError("Invalid username or password.");
        }

        // Update kolom last_login
        $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = :id");
        $stmt->execute(['id' => $user['id']]);

        // Set session
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['color'] = $user['color'];

        // Redirect ke halaman index dengan pesan login berhasil
        redirectToIndexWithMessage("Login successful, welcome back " . htmlspecialchars(ucwords($user['fullname'])) . "!");

    } catch (Exception $e) {
        redirectToLoginWithError("An error occurred: " . $e->getMessage());
    }
} else {
    redirectToLoginWithError("Invalid request method.");
}
