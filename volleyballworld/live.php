<?php

    // Ambil eventId dari parameter query
    $eventId = isset($_GET['event']) ? $_GET['event'] : null;

    if ($eventId) {
        // URL API dengan eventId dinamis
        $apiUrl = 'https://zapp-5434-volleyball-tv.web.app/jw/media/' . $eventId;

        // Inisialisasi cURL
        $ch = curl_init();

        // Setel opsi cURL
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Kembalikan hasil sebagai string
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Nonaktifkan verifikasi sertifikat SSL jika perlu

        // Eksekusi permintaan cURL
        $response = curl_exec($ch);

        // Cek apakah ada kesalahan
        if (curl_errno($ch)) {
            echo 'cURL Error: ' . curl_error($ch);
        } else {
            // Tampilkan hasil dari API
            curl_close($ch);
            $data = json_decode($response, true);
            // echo "<pre>";
            // var_dump($data);
            // die();
        }

        // Tutup cURL
        curl_close($ch);
    } else {
        echo "Event ID tidak tersedia.";
    }

$thumb = $data["entry"][0]["media_group"][0]["media_item"][0]["src"];
$title = $data["title"];
require_once '../templates/header.php';
?>
     <!-- SEARCH BAR -->
<div class="container mx-auto px-4 pt-4">
    <form class="mx-auto relative" action="explore.php" method="get">
        <input type="text" name="search" placeholder="Cari..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" class="block w-full px-4 py-2 pr-20 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-800 dark:border-gray-700 dark:focus:ring-blue-600">
        <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-4 text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded-r-lg">
            Cari
        </button>
    </form>
</div>
<!-- END SEARCH BAR -->

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
<script>
    // Data dari PHP
    const streamData = <?php echo json_encode($data['entry'][0]['content']); ?>;
    // console.log(streamData);

    // Mengambil query parameter dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const eventParam = urlParams.get('event'); // Mendapatkan nilai dari parameter 'event'

    // Daftar URL HLS
     if (streamData.src.includes("manifests")) {
        console.log("manifests detected")
        hlsUrls = [ 
            `https://stream.micinproject.de/volleyballworld/proxy.php?url=${encodeURIComponent(streamData.src)}`
            ];
    }else {
        hlsUrls = [
            streamData.src, // URL utama
            `https://livecdn.euw1-0005.jwplive.com/live/sites/fM9jRrkn/media/${eventParam}/live.isml/.m3u8`, // URL alternatif dengan event
            `https://livecdn.use1-0004.jwplive.com/live/sites/fM9jRrkn/media/${eventParam}/live.isml/.m3u8` // URL alternatif dengan event
        ];
    }


    // Fungsi untuk memeriksa URL dan mengembalikan URL yang valid
    const checkHlsUrls = async (urls) => {
    for (const url of urls) {
        console.log(`Checking URL: ${url}`); // Tambahkan log ini
        const response = await fetch(url, { method: 'GET' });
        if (response.ok) {
            console.log("no geoblock detected");
            return url; // Kembalikan URL yang valid
        } 
    }

        console.log("all errors return default");
        return streamData.src; // Jika semua URL gagal
    };

    // Jika data berhasil diambil, inisialisasi JW Player
    if (streamData) {
        checkHlsUrls(hlsUrls).then(validUrl => {
            jwplayer("player").setup({
                playlist: [{
                    title: "Live Stream",
                    sources: [{
                        default: false,
                        type: "hls",
                        file: validUrl
                    }]
                }],
                width: "100%",
                height: "100%",
                aspectratio: "16:9",
                autostart: true,
                logo: {
                    file: "/logo.svg", // Path ke file logo SVG
                    position: "bottom-right", // Posisi logo
                    hide: false // Logo akan selalu ditampilkan
                },
                sharing: {},
                generateSEOMetadata: true,
                autostart: "viewable",
                aboutlink: "https://t.me/+BNTHvuqimcc2ODY1",
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

        }).catch(error => {
            console.error('Error checking HLS URLs:', error);
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
