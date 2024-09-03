<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/islogin.php';

function redirectToRegisterWithError($error) {
    header("Location: /register.php?error=" . urlencode($error));
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari formulir
    $data = [
        'fullname' => trim(htmlspecialchars($_POST['fullname'])) ?? null,
        'username' => trim(htmlspecialchars($_POST['username'])) ?? null,
        'email' => trim(htmlspecialchars($_POST['email'])) ?? null,
        'password' => trim(htmlspecialchars($_POST['password'])) ?? null,
        'unhash_password' => trim(htmlspecialchars($_POST['password'])) ?? null,
        'confirm_password' => trim(htmlspecialchars($_POST['confirm_password'])) ?? null,
    ];

    // Validasi data
    if ($data['password'] !== $data['confirm_password']) {
        redirectToRegisterWithError("Passwords do not match.");
    }

    // Validasi Username
    function validateUsername($username) {
        // Username tidak boleh kosong
        if (empty($username)) {
            return false;
        }

        // Ekspresi reguler untuk memastikan username hanya mengandung huruf dan angka, 
        // tidak boleh dimulai dengan angka, dan harus mengandung setidaknya satu huruf
        $pattern = '/^[a-zA-Z][a-zA-Z0-9]*$/';

        return preg_match($pattern, $username);
    }

    // Validasi username
    $username = $data['username'];
    if (!validateUsername($username)) {
        redirectToRegisterWithError("Invalid Username: Username must start with a letter, contain only letters and numbers, and not be numeric.");
    }

    // Lanjutkan dengan logika registrasi di sini


    try {
        $pdo = getDbConnection();

        // Cek apakah username atau email sudah digunakan
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $data['username'], 'email' => $data['email']]);
        if ($stmt->fetchColumn() > 0) {
            redirectToRegisterWithError("Username or email already taken.");
        }

        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $unhash_password = $data['password'];
        $device_hash = 'Unready';

        // Simpan data pengguna
        $stmt = $pdo->prepare("INSERT INTO users 
            (
                fullname, 
                username, 
                email, 
                password, 
                unhash_password,
                device_hash
            ) VALUES (
                :fullname, 
                :username, 
                :email, 
                :password, 
                :unhash_password,
                :device_hash
            )");
        $stmt->execute([
            'fullname' => $data['fullname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'unhash_password' => $unhash_password,
            'device_hash' => $device_hash
        ]);

        // Redirect ke halaman sukses atau login
        header("Location: /login.php?message=" . urlencode("Registration successful!"));
        exit();

    } catch (Exception $e) {
        redirectToRegisterWithError("An error occurred: " . $e->getMessage());
    }
} else {
    redirectToRegisterWithError("Invalid request method.");
}
