<?php
// Ambil ID dari parameter 'live' di URL
$liveId = isset($_GET['live']) ? $_GET['live'] : null;

// Pastikan ID tersedia sebelum melanjutkan
if ($liveId) {
    // URL untuk permintaan API
    $url = "https://proxy-gateway.sports.naver.com/livecloud/lives/{$liveId}/playback?countryCode=SG&devt=HTML5_PC&timeMachine=false&p2p=false&includeThumbnail=false&pollingStatus=false";

    // Inisialisasi cURL
    $ch = curl_init($url);

    // Set opsi cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'authority: proxy-gateway.sports.naver.com',
        'accept: application/json, text/plain, */*',
        'accept-language: en-US,en;q=0.7',
        'origin: https://m.sports.naver.com',
        'referer: https://m.sports.naver.com/',
        'sec-ch-ua: "Brave";v="111", "Not(A:Brand";v="8", "Chromium";v="111"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Linux"',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-site',
        'sec-gpc: 1',
        'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36',
    ]);

    // Eksekusi permintaan cURL dan ambil hasilnya
    $response = curl_exec($ch);

    // Cek jika ada error
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    // Tutup sesi cURL
    curl_close($ch);

    // Dekode hasil JSON
    $data = json_decode($response, true);

    // Tampilkan hasil
    // echo "<pre>";
    // print_r($data['media']);
    // echo "</pre>";
    // die();
} else {
    echo "ID live tidak ditemukan di URL.";
}
// die();
$title = 'Naver';
require_once '../templates/header.php';
?><div class="container mx-auto p-4">
    <!-- Button to toggle episodes on mobile -->
    <div class="md:hidden mb-4">
        <button id="episodes-button" class="w-full p-2 bg-blue-600 text-white rounded-md">Show Channels</button>
    </div>

    <div class="grid grid-cols-12 gap-4">
        <!-- Sidebar for Episodes -->
        <div class="col-span-2 bg-gray-800 p-2 rounded-md hidden md:block" id="episodes-list">
            <h2 class="text-xl font-bold mb-4">Channels</h2>
            <ul class="space-y-2 overflow-y-auto" style="max-height: 70vh;">
                <!-- Add more episodes as needed -->
            </ul>
        </div>

        <!-- Main Video Section -->
        <div class="col-span-12 md:col-span-7 bg-gray-800 p-2 rounded-md">
            <h2 class="text-xl font-bold mb-4">Video Player</h2>
            <div class="relative" id="player" style="padding-top: 44.44%;"></div>
        </div>

        <!-- Live Chat Section -->
        <?php 
            require_once '../templates/chat.php';
        ?>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@1.6.5/dist/flowbite.min.js"></script>
<script src="//ssl.p.jwpcdn.com/player/v/8.21.0/jwplayer.js"></script>
<script> jwplayer.key = 'XSuP4qMl+9tK17QNb+4+th2Pm9AWgMO/cYH8CI0HGGr7bdjo';</script>
<script src="../navbar.js"></script>

<script>
        // Data dari PHP
        const streamData = <?php echo json_encode($data['media'][0]); ?>;


        // Jika data berhasil diambil, inisialisasi JW Player
        if (streamData) {
            const hlsUrl = streamData.path;

            jwplayer("player").setup({
                playlist: [{
                    title: "Live Stream",
                    sources: [{
                        default: false,
                        type: "hls",
                        file: hlsUrl
                    }]
                }],
                width: "100%",
                height: "100%",
                aspectratio: "16:9",
                autostart: true,
                logo: {
                    file: "/logo.svg", // Path ke file logo SVG
                    position: "top-right", // Posisi logo, bisa 'top-right', 'top-left', 'bottom-right', atau 'bottom-left'
                    hide: false // Logo akan selalu ditampilkan
                },
                sharing: {},
                generateSEOMetadata: true,
                autostart:"viewable",
                aboutlink: "https://nonton.micinproject.my.id",
                abouttext: "Micin Project"
            });
        } else {
            console.error('Failed to get HLS URL:', streamData);
        }
    </script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="/assets/js/chat.js"></script>
</body>
</html>
