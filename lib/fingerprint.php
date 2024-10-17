<?php
// Mengatur header untuk menerima JSON
header('Content-Type: application/json');

// Mengimpor fungsi koneksi database
require 'config.php';

// Mendapatkan data JSON dari permintaan POST
$data = json_decode(file_get_contents('php://input'), true);

// Memeriksa apakah data diterima
if ($data) {
    // Mengambil data dari JSON
    $deviceHash = $data['permanent_data_hash'] ?? null; // Anda bisa menggunakan permanent_data_hash sebagai device_hash
    $userAgent = $data['comprehensive_fingerprint']['user_agent'] ?? null;
    $language = $data['comprehensive_fingerprint']['language'] ?? null;
    $pixelRatio = $data['comprehensive_fingerprint']['pixel_ratio'] ?? null;
    $hardwareConcurrency = $data['comprehensive_fingerprint']['hardware_concurrency'] ?? null;
    $resolutionWidth = $data['comprehensive_fingerprint']['resolution'][0] ?? null;
    $resolutionHeight = $data['comprehensive_fingerprint']['resolution'][1] ?? null;
    $availableResolutionWidth = $data['comprehensive_fingerprint']['available_resolution'][0] ?? null;
    $availableResolutionHeight = $data['comprehensive_fingerprint']['available_resolution'][1] ?? null;
    $timezoneOffset = $data['comprehensive_fingerprint']['timezone_offset'] ?? null;
    $sessionStorage = $data['comprehensive_fingerprint']['session_storage'] ?? null;
    $localStorage = $data['comprehensive_fingerprint']['local_storage'] ?? null;
    $indexedDb = $data['comprehensive_fingerprint']['indexed_db'] ?? null;
    $openDatabase = $data['comprehensive_fingerprint']['open_database'] ?? null;
    $navigatorPlatform = $data['comprehensive_fingerprint']['navigator_platform'] ?? null;
    $navigatorOscpu = $data['comprehensive_fingerprint']['navigator_oscpu'] ?? null;
    $doNotTrack = $data['comprehensive_fingerprint']['do_not_track'] ?? null;
    $touchSupport = $data['comprehensive_fingerprint']['touch_support'] ?? null;
    $cookieEnabled = $data['comprehensive_fingerprint']['cookie_enabled'] ?? null;
    $fonts = $data['comprehensive_fingerprint']['fonts'] ?? null;
    $deviceOrientation = $data['comprehensive_fingerprint']['device_orientation'] ?? null;
    $connectionType = $data['comprehensive_fingerprint']['connection_type'] ?? null;
    $publicIp = $data['comprehensive_fingerprint']['public_ip'] ?? null;
    $navigationStart = $data['comprehensive_fingerprint']['navigation_start'] ?? null;
    $domComplete = $data['comprehensive_fingerprint']['dom_complete'] ?? null;
    $cssFeatures = json_encode($data['comprehensive_fingerprint']['css_features'] ?? null);
    $webgl = $data['comprehensive_fingerprint']['webgl'] ?? null;
    $p2pSupport = $data['comprehensive_fingerprint']['p2p_support'] ?? null;

    // Memastikan data tidak kosong
    if ($deviceHash && $userAgent) {
        try {
            // Mendapatkan koneksi database
            $pdo = getDb2Connection();

            // Memeriksa apakah device_hash sudah ada
            $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM fingerprints WHERE device_hash = :device_hash");
            $stmtCheck->bindParam(':device_hash', $deviceHash);
            $stmtCheck->execute();
            $exists = $stmtCheck->fetchColumn();

            if ($exists) {
                // Jika device_hash sudah ada, abaikan penyisipan
                echo json_encode(['status' => 'info', 'message' => 'Device hash already exists, ignoring insert']);
            } else {
                // Menyiapkan pernyataan SQL untuk menyimpan data
                $stmt = $pdo->prepare("INSERT INTO fingerprints (
                    device_hash, user_agent, language, pixel_ratio, hardware_concurrency,
                    resolution_width, resolution_height, available_resolution_width,
                    available_resolution_height, timezone_offset, session_storage,
                    local_storage, indexed_db, open_database, navigator_platform,
                    navigator_oscpu, do_not_track, touch_support, cookie_enabled,
                    fonts, device_orientation, connection_type, public_ip,
                    navigation_start, dom_complete, css_features, webgl, p2p_support
                ) VALUES (
                    :device_hash, :user_agent, :language, :pixel_ratio, :hardware_concurrency,
                    :resolution_width, :resolution_height, :available_resolution_width,
                    :available_resolution_height, :timezone_offset, :session_storage,
                    :local_storage, :indexed_db, :open_database, :navigator_platform,
                    :navigator_oscpu, :do_not_track, :touch_support, :cookie_enabled,
                    :fonts, :device_orientation, :connection_type, :public_ip,
                    :navigation_start, :dom_complete, :css_features, :webgl, :p2p_support
                )");

                // Mengikat parameter
                $stmt->bindParam(':device_hash', $deviceHash);
                $stmt->bindParam(':user_agent', $userAgent);
                $stmt->bindParam(':device_hash', $deviceHash);
                $stmt->bindParam(':user_agent', $userAgent);
                $stmt->bindParam(':language', $language);
                $stmt->bindParam(':pixel_ratio', $pixelRatio);
                $stmt->bindParam(':hardware_concurrency', $hardwareConcurrency);
                $stmt->bindParam(':resolution_width', $resolutionWidth);
                $stmt->bindParam(':resolution_height', $resolutionHeight);
                $stmt->bindParam(':available_resolution_width', $availableResolutionWidth);
                $stmt->bindParam(':available_resolution_height', $availableResolutionHeight);
                $stmt->bindParam(':timezone_offset', $timezoneOffset);
                $stmt->bindParam(':session_storage', $sessionStorage, PDO::PARAM_BOOL);
                $stmt->bindParam(':local_storage', $localStorage, PDO::PARAM_BOOL);
                $stmt->bindParam(':indexed_db', $indexedDb, PDO::PARAM_BOOL);
                $stmt->bindParam(':open_database', $openDatabase, PDO::PARAM_BOOL);
                $stmt->bindParam(':navigator_platform', $navigatorPlatform);
                $stmt->bindParam(':navigator_oscpu', $navigatorOscpu);
                $stmt->bindParam(':do_not_track', $doNotTrack);
                $stmt->bindParam(':touch_support', $touchSupport);
                $stmt->bindParam(':cookie_enabled', $cookieEnabled, PDO::PARAM_BOOL);
                $stmt->bindParam(':fonts', $fonts);
                $stmt->bindParam(':device_orientation', $deviceOrientation);
                $stmt->bindParam(':connection_type', $connectionType);
                $stmt->bindParam(':public_ip', $publicIp);
                $stmt->bindParam(':navigation_start', $navigationStart);
                $stmt->bindParam(':dom_complete', $domComplete);
                $stmt->bindParam(':css_features', $cssFeatures);
                $stmt->bindParam(':webgl', $webgl);
                $stmt->bindParam(':p2p_support', $p2pSupport);



                // Menjalankan pernyataan
                $stmt->execute();

                // Mengirimkan respons kembali ke frontend
                echo json_encode(['status' => 'success', 'message' => 'Data received and saved successfully']);
            }
        } catch (Exception $e) {
            // Menangani kesalahan dan mengirimkan respons
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        // Jika data tidak lengkap
        echo json_encode(['status' => 'error', 'message' => 'Incomplete data received', 'data' => $data]);
    }
} else {
    // Jika tidak ada data yang diterima
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
}
?>
