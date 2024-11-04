<?php
function fetchLiveData($liveId) {
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
        curl_close($ch);
        return ['error' => curl_error($ch)];
    }

    // Tutup sesi cURL
    curl_close($ch);

    // Dekode hasil JSON
    return json_decode($response, true);
}

function getLiveId() {
    // Ambil ID dari parameter 'live' di URL
    $live = isset($_GET['live']) ? $_GET['live'] : null;

    // Definisikan pola format yang valid (misalnya hanya angka)
    $pattern = '/^[0-9]{7}$/';

    // Cek apakah $live sesuai dengan pola format yang valid
    if ($live && preg_match($pattern, $live)) {
        return $live; // Format sesuai, kembalikan nilai $live
    } else {
        // URL untuk permintaan API menggunakan ID
        $url = "https://api-gw.sports.naver.com/schedule/games/{$live}";

        // Inisialisasi cURL
        $ch = curl_init($url);

        // Set opsi cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'authority: api-gw.sports.naver.com',
            'accept: application/json, text/plain, */*',
            'accept-language: en-US,en;q=0.9',
            'cache-control: no-cache',
            'origin: https://m.sports.naver.com',
            'pragma: no-cache',
            'referer: https://m.sports.naver.com/game/',
            'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Linux"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-site',
            'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36',
        ]);

        // Eksekusi permintaan cURL dan ambil hasilnya
        $response = curl_exec($ch);

        // Cek jika ada error
        if (curl_errno($ch)) {
            curl_close($ch);
            return 'ID tidak sesuai format'; // Menganggap ID tidak valid jika ada error cURL
        }

        // Tutup sesi cURL
        curl_close($ch);
        $data = json_decode($response, true);
        $live = $data['result']['game']['liveList'][0]['liveId'];
        return $live;
        // Tampilkan hasil cURL untuk debugging
        // echo '<pre>';
        // var_dump($data['result']['game']['liveList'][0]['liveId']);
        // echo '</pre>';
        // die(); // Berhenti di sini untuk melihat hasil
    }
}

$liveId = getLiveId();
// Pastikan ID tersedia sebelum melanjutkan
if ($liveId) {
    $data = fetchLiveData($liveId);

    // Tampilkan hasil
    // echo "<pre>";
    // print_r($data['media']);
    // echo "</pre>";
} else {
    echo "ID live tidak ditemukan di URL.";
}

$title = 'Naver';
require_once '../templates/header.php';
?>
<div class="container mx-auto p-4">
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

            // Inisialisasi JW Player
            jwplayer("player").setup({
                playlist: [{
                    title: "Live Stream",
                    sources: [{
                        default: false,
                        file: hlsUrl
                    }]
                }],
                width: "100%",
                height: "100%",
                aspectratio: "16:9",
                autostart: true,
                logo: {
                    file: "/logo.svg", // Path ke file logo SVG
                    position: "bottom-left", // Posisi awal logo
                    hide: false, // Logo akan selalu ditampilkan
                    margin: 12
                },
                sharing: {},
                generateSEOMetadata: true,
                autostart: "viewable",
                aboutlink: "https://t.me/+2kIytkfXzms4Mjg1",
                abouttext: "Join Telegram"
            });

            
            //set logo
                        // Variabel untuk menyimpan posisi terakhir logo
            let lastPosition = "jw-logo-bottom-left"; // Atur posisi awal sesuai dengan posisi logo

            // Event listener untuk pemutaran dimulai
            jwplayer("player").on('play', function() {
                // Fungsi untuk mengubah posisi logo
                const changeLogoPosition = () => {
                    const jwLogo = document.querySelector('.jw-logo'); // Menargetkan elemen logo JW Player

                    if (jwLogo) {
                        // Hapus semua kelas posisi logo sebelumnya
                        jwLogo.classList.remove('jw-logo-bottom-left', 'jw-logo-bottom-right', 'jw-logo-top-left', 'jw-logo-top-right');

                        // Pilih posisi baru yang tidak sama dengan posisi terakhir
                        const positions = ['jw-logo-bottom-left', 'jw-logo-bottom-right', 'jw-logo-top-left', 'jw-logo-top-right'];
                        let newPosition;

                        do {
                            newPosition = positions[Math.floor(Math.random() * positions.length)];
                        } while (newPosition === lastPosition); // Pastikan posisi baru tidak sama dengan posisi terakhir

                        // Tambahkan posisi baru dan perbarui lastPosition
                        jwLogo.classList.add(newPosition);
                        lastPosition = newPosition; // Simpan posisi terakhir
                    }
                };

                // Ubah posisi logo setiap 30 detik
                setInterval(changeLogoPosition, 600000); // 30000 ms = 30 detik
            });

        } else {
            console.error('Failed to get HLS URL:', streamData);
        }
    </script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<?php if (isset($_SESSION['username'])): ?>
    <script src="/assets/js/chat.js"></script>
<?php else: ?>
    <script src="/assets/js/chat-public.js"></script>
<?php endif ?>
</body>
</html>
