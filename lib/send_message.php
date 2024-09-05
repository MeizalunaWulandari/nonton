<?php
// Aktifkan tampilan kesalahan
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);




// Memuat file yang diperlukan
require '../lib/session.php'; // Pastikan file ini menginisialisasi Pusher
require '../lib/pusher.php'; // Pastikan file ini menginisialisasi Pusher
require '../lib/config.php'; // Memuat config.php


if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header("Location: /login.php?message=You+must+login+to+send+message"); // Redirect ke index.php
}


// Ambil data dari request
$data = json_decode(file_get_contents('php://input'), true);

// Debug: Tampilkan data yang diterima
if ($data === null) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
    exit;
}

$username = isset($data['username']) ? $data['username'] : 'Anonymous';
$color = $_SESSION['color'];
$message = isset($data['message']) ? $data['message'] : '';
$channel = isset($data['channel']) ? $data['channel'] : 'default_channel';
$user_id = $_SESSION['user_id']; // Gantilah dengan ID pengguna yang sebenarnya dari sesi atau autentikasi

// Validasi pesan
if ($message !== '') {
    try {
        // Cek koneksi database
        try {
            $pdo = getDbConnection();
        } catch (Exception $e) {
            echo 'Database connection failed: ' . $e->getMessage();
            exit;
        }

        // Simpan pesan ke database
        $stmt = $pdo->prepare('
            INSERT INTO chats (
                color,
                channel_id,
                user_id,
                message
            ) VALUES (
                :color, 
                :channel_id, 
                :user_id, 
                :message
            )');
        $stmt->execute([
            ':color' => $color,
            ':channel_id' => $channel,
            ':user_id' => $user_id,
            ':message' => $message
        ]);

        // Kirim pesan ke channel Pusher
        $pusher->trigger($channel, 'new-message', [
            'username' => $_SESSION['username'],
            'color' => $color,
            'message' => $message
        ]);

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Message cannot be empty']);
}
?>
