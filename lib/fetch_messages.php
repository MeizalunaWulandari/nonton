<?php
// Memuat file yang diperlukan
require '../lib/session.php'; // Inisialisasi sesi
require '../lib/pusher.php'; // Inisialisasi Pusher
require '../lib/config.php'; // Memuat konfigurasi database

// Ambil channel dari request GET
$channel = isset($_GET['channel']) ? $_GET['channel'] : 'default_channel';

try {
    // Cek koneksi database
    $pdo = getDbConnection();

    // Ambil pesan chat dan data user dari database berdasarkan channel
    $stmt = $pdo->prepare('
        SELECT c.message, c.channel_id, u.username, u.color 
        FROM chats c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.channel_id = :channel 
        ORDER BY c.id DESC 
        LIMIT 50
    ');
    $stmt->execute([':channel' => $channel]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Kirim data pesan sebagai respons JSON
    echo json_encode($messages);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to load messages: ' . $e->getMessage()]);
}
?>
