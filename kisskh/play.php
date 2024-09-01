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
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="no-referrer" />
    <title>Micin Project | <?= $data['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@1.6.5/dist/flowbite.min.css" rel="stylesheet">
    <link href="/style.css" rel="stylesheet">
    <style>
        /* Custom styles for responsive behavior */
        @media (max-width: 768px) {
            #episodes-list {
                display: none;
            }
            #episodes-button {
                display: block;
            }
        }
    </style>
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

<!-- END SEARCH BAR -->
<div class="container mx-auto px-4 pt-6 ">
    <form class="max-w-screen-xl mx-auto relative" action="explore.php">
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

    // Toggle episodes list on mobile
    document.getElementById('episodes-button').addEventListener('click', () => {
        const episodesList = document.getElementById('episodes-list');
        episodesList.style.display = episodesList.style.display === 'none' ? 'block' : 'none';
    });
</script>

</body>
</html>
