<?php
// Ambil parameter 'id' dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Inisialisasi cURL
    $ch = curl_init();

    // Setel URL dan opsi-opsi cURL
    curl_setopt($ch, CURLOPT_URL, "https://kisskh.co/api/DramaList/Drama/$id?isq=false");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'authority: kisskh.co',
        'accept: application/json, text/plain, */*',
        'accept-language: en-US,en;q=0.9',
        'cache-control: no-cache',
        'cookie: g_state={"i_p":1723724880291,"i_l":1}',
        'pragma: no-cache',
        'referer: https://kisskh.co/Drama/Fireworks-of-My-Heart?id=' . $id,
        'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Linux"',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36'
    ]);

    // Eksekusi cURL dan ambil responsnya
    $response = curl_exec($ch);

    // Tutup cURL
    curl_close($ch);

    // Tampilkan hasilnya
    $data = json_decode($response, true);
    $reversedEpisodes = array_reverse($data['episodes']);
    // echo "<pre>";
    // var_dump ($data);
    // die();
} else {
    echo "ID tidak ditemukan dalam URL";
}


// Inisialisasi cURL
$ch = curl_init();

// ID yang diambil dari URL (contoh)
if (!isset($_GET['eps'])) {
    $subId = $reversedEpisodes[0]['id'];
}else{
    $subId = $_GET['eps'];
}

// Setel URL dan opsi-opsi cURL
curl_setopt($ch, CURLOPT_URL, "https://kisskh.co/api/Sub/{$subId}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: kisskh.co',
    'accept: application/json, text/plain, */*',
    'accept-language: en-US,en;q=0.9',
    'cache-control: no-cache',
    'cookie: g_state={"i_p":1723724880291,"i_l":1}',
    'pragma: no-cache',
    'referer: https://kisskh.co/Drama/Fireworks-of-My-Heart/Episode-1?id=7628&ep=' . $subId . '&page=0&pageSize=100',
    'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Linux"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36'
]);

// Eksekusi cURL
$response = curl_exec($ch);

// Periksa jika terjadi kesalahan
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    // Tampilkan hasilnya
    // echo "<pre>";
    // var_dump(json_decode($response, true));
    // echo "</pre>";
    $subtitles =  $response;
    
}

// Tutup sesi cURL
curl_close($ch);

// Get HLS
// Inisialisasi cURL
$ch = curl_init();

// ID yang diambil dari URL (contoh)


// Setel URL dan opsi-opsi cURL
curl_setopt($ch, CURLOPT_URL, "https://kisskh.co/api/DramaList/Episode/{$subId}.png?err=false&ts=&time=");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: kisskh.co',
    'accept: application/json, text/plain, */*',
    'accept-language: en-US,en;q=0.9',
    'cache-control: no-cache',
    'cookie: g_state={"i_p":1723724880291,"i_l":1}',
    'pragma: no-cache',
    'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Linux"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36'
]);

// Eksekusi cURL
$response = curl_exec($ch);

// Periksa jika terjadi kesalahan
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    // Tampilkan hasilnya
    $streamData = $response;
    // echo "<pre>";
    // var_dump($response);
    // echo "</pre>";
    // die();
}

// Tutup sesi cURL
curl_close($ch);

    $title = $data['title'];
    require_once '../templates/header.php';
?>

<!-- END SEARCH BAR -->
<div class="container mx-auto px-4 pt-6 ">
    <form class="mx-auto relative" action="explore.php">
        <input type="text" name="search" placeholder="Cari..." class="block w-full px-4 py-2 pr-20 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-800 dark:border-gray-700 dark:focus:ring-blue-600">
        <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-4 text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded-r-lg">
            Cari
        </button>
    </form>
</div>
<!-- END SEARCH BAR -->



<div class="container mx-auto p-4">
    <!-- Button to toggle episodes on mobile -->
    <div class="md:hidden mb-4">
        <button id="episodes-button" class="w-full p-2 bg-blue-600 text-white rounded-md">Show Episodes</button>
    </div>

    <div class="grid grid-cols-12 gap-4">
        <!-- Sidebar for Episodes -->
        <div class="col-span-12 md:col-span-2 bg-gray-800 p-2 rounded-md" id="episodes-list">
            <h2 class="text-xl font-bold mb-4">Episodes</h2>
            <ul class="space-y-2 overflow-y-auto" style="max-height: 70vh;">
                <?php 
// Membalik urutan episode
$episodeNumber = 1; // Mulai dari Episode 1

foreach ($reversedEpisodes as $episode): 

    if (!isset($_GET['eps'])) {
        $_GET['eps'] = $reversedEpisodes[0]['id'];
    }
    if ($_GET['eps'] == $episode['id']) {
        $classActive = 'bg-gray-700';
    }else{
        $classActive = '';
    }

?>
    <li>
        <a href="play.php?id=<?= $data['id'] ?>&eps=<?= $episode['id'] ?>" class="block p-2 <?= $classActive ?> rounded hover:bg-gray-600">
            Episode <?= $episodeNumber ?>
        </a>
    </li>
<?php 
    $episodeNumber++; // Increment nomor episode
endforeach; 
?>

                <!-- Add more episodes as needed -->
            </ul>
        </div>

        <!-- Main Video Section -->
        <div class="col-span-12 md:col-span-7 bg-gray-800 p-2 rounded-md">
            <h2 class="text-xl font-bold mb-4"><?= $data['title'] ?></h2>
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
    const streamData = <?= $streamData ?>;
    const subtitles = <?= $subtitles ?>; // Ambil dari PHP

    if (streamData) {
        // Buat array tracks dari data subtitle
        const tracks = subtitles.map(subtitle => ({
            file: subtitle.src,
            label: subtitle.label,
            kind: "captions",
            "default": subtitle.default
        }));

        jwplayer("player").setup({
            playlist: [{
                title: "Live Stream",
                sources: [{
                    default: false,
                    // type: "hls", // Uncomment jika menggunakan HLS
                    file: streamData.Video
                }],
                tracks: tracks
            }],
            width: "100%",
            height: "100%",
            aspectratio: "16:9",
            autostart: true,
            logo: {
                file: "/logo.svg", // Path ke file logo SVG
                position: "bottom-right", // Posisi logo, bisa 'top-right', 'top-left', 'bottom-right', atau 'bottom-left'
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

    // Toggle episodes list on mobile
    document.getElementById('episodes-button').addEventListener('click', () => {
        const episodesList = document.getElementById('episodes-list');
        episodesList.style.display = episodesList.style.display === 'none' ? 'block' : 'none';
    });
</script>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<?php if (isset($_SESSION['username'])): ?>
    <script src="/assets/js/chat.js"></script>
<?php else: ?>
    <script src="/assets/js/chat-public.js"></script>
<?php endif ?>
</body>
</html>
