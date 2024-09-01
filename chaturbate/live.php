<?php
session_start();

// Username dan password yang benar
$correct_username = 'coolgate424';
$correct_password = '1sampai8';

// Inisialisasi percobaan login jika belum ada
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Batasi hingga 3 kali percobaan login
$max_attempts = 3;

// Reset login attempts saat halaman di-refresh
if ($_SESSION['login_attempts'] >= $max_attempts) {
    // Reset percobaan login agar form login muncul kembali setelah refresh
    $_SESSION['login_attempts'] = 0;
    echo "<h1>Upaya login diblokir, hubungi admin untuk membuat akun.</h1><h2>Jika hanya kesalahan silahkan refresh untuk menapilkan form.";
    exit();
}

// Cek jika user belum login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Jika login via HTTP Basic Authentication gagal
    if (
        !isset($_SERVER['PHP_AUTH_USER']) ||
        $_SERVER['PHP_AUTH_USER'] !== $correct_username ||
        $_SERVER['PHP_AUTH_PW'] !== $correct_password
    ) {
        // Tambahkan jumlah percobaan login yang salah
        $_SESSION['login_attempts']++;

        // Tampilkan pop-up login lagi
        header('WWW-Authenticate: Basic realm="My Project"');
        header('HTTP/1.0 401 Unauthorized');
        echo "Unauthorized. You have " . ($max_attempts - $_SESSION['login_attempts']) . " attempt(s) left.";
        exit();
    } else {
        // Reset percobaan login saat berhasil login
        $_SESSION['login_attempts'] = 0;
        $_SESSION['logged_in'] = true;
    }
}

// Konten setelah login
// echo "Welcome, you are logged in!";



// Ambil parameter 'user' dari URL
if (!isset($_GET['user']) || empty($_GET['user'])) {
    // Jika tidak ada, redirect ke index.php
    header('Location: index.php');
    exit(); // Pastikan untuk menghentikan eksekusi script setelah redirect
}
$user = $_GET['user'];
// Inisialisasi sesi cURL
$ch = curl_init();

// Set opsi cURL
curl_setopt($ch, CURLOPT_URL, 'https://chaturbate.com/get_edge_hls_url_ajax/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: chaturbate.com',
    'accept: */*',
    'accept-language: en-US,en;q=0.9',
    'cache-control: no-cache',
    'content-type: multipart/form-data; boundary=----WebKitFormBoundaryki0AEPPQCkn5LKRM',
    'origin: https://chaturbate.com',
    'pragma: no-cache',
    'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Linux"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36',
    'x-requested-with: XMLHttpRequest',
]);

// Bangun data POST yang dinamis
$postFields = "------WebKitFormBoundaryki0AEPPQCkn5LKRM\r\n" .
              "Content-Disposition: form-data; name=\"room_slug\"\r\n\r\n" .
              "$user\r\n" .
              "------WebKitFormBoundaryki0AEPPQCkn5LKRM\r\n" .
              "Content-Disposition: form-data; name=\"bandwidth\"\r\n\r\n" .
              "high\r\n" .
              "------WebKitFormBoundaryki0AEPPQCkn5LKRM--";

// Set data POST
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

// Eksekusi cURL
$response = curl_exec($ch);

// Cek jika ada kesalahan
if(curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch) . "<br>";
}

// Tutup sesi cURL
curl_close($ch);
$data = json_decode($response, true);

// Tampilkan hasil
// echo "<pre>";
// var_dump($response);
// die();
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="no-referrer" />
    <title>Micin Project | <?= ucfirst($_GET['user']) ?></title>
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
        const streamData = <?php echo json_encode($data); ?>;

        // console.log(streamData);

        // Jika data berhasil diambil, inisialisasi JW Player
        if (streamData.success) {
            const hlsUrl = streamData.url;


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
                aboutlink: "https://nonton.micinproject.my.id",
                abouttext: "Micin Project"
            });
        } else {
            console.error('Failed to get HLS URL:', streamData);
        }
    </script>
</body>
</html>
