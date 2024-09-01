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
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="no-referrer" />
    <title>Micin Project | Naver</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@1.6.5/dist/flowbite.min.css" rel="stylesheet">
    <link href="/style.css" rel="stylesheet">
</head>
<body class="bg-gray-50 dark:bg-gray-900 dark:border-gray-800 text-white">

<nav class="border-gray-200 bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Micin Project</span>
    </a>
    <button data-collapse-toggle="navbar-solid-bg" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-solid-bg" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
    <div class="hidden w-full md:block md:w-auto" id="navbar-solid-bg">
      <ul class="flex flex-col font-medium mt-4 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-transparent dark:bg-gray-800 md:dark:bg-transparent dark:border-gray-700">
        <li>
          <a href="/" class="block py-2 px-3 md:p-0 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:dark:text-blue-500 dark:bg-blue-600 md:dark:bg-transparent" aria-current="page">Home</a>
        </li>
        <li>
          <a href="" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Login</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- NAVBAR END -->

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
        <div class="col-span-12 md:col-span-3 bg-gray-800 p-2 rounded-md">
            <h2 class="text-xl font-bold mb-4">Live Chat</h2>
            <div class="h-96 bg-gray-700 rounded-md p-4 overflow-y-auto">
                <!-- Example Chat Content -->
                <div class="mb-4">
                    <p><strong>Admin:</strong> Hi, everyone! The live chat is under development.</p>
                </div>
                <!-- More chat messages go here -->
            </div>
            <div class="mt-4">
                <input type="text" class="w-full p-2 bg-gray-600 rounded-md" placeholder="Type your message...">
            </div>
        </div>
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

</body>
</html>
