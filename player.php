<?php 
    
    if (isset($_GET['manifest'])) {
        $manifest = $_GET['manifest'];
    }else{
        $manifest = '';
    }
    if (isset($_GET['drm'])) {
        $drm = $_GET['drm'];
    }else{
        $drm = '';
    }

?>


<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="no-referrer" />
    <title>Streaming Template</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@1.6.5/dist/flowbite.min.css" rel="stylesheet">
    <link href="/style.css" rel="stylesheet">
</head>
<body class="bg-gray-50 dark:bg-gray-900 dark:border-gray-800 text-white">

<nav class="border-gray-200 bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
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
          <a href="#" class="block py-2 px-3 md:p-0 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:dark:text-blue-500 dark:bg-blue-600 md:dark:bg-transparent" aria-current="page">Home</a>
        </li>
        <li>
          <a href="#" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Jadwal</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- NAVBAR END -->


<!-- END SEARCH BAR -->
<div class="container mx-auto px-4 pt-6">
    <form class="max-w-screen-xl mx-auto space-y-4" action="" method="GET" id="video-form">
        <!-- Input Field -->
        <input type="text" name="manifest" placeholder="Video Link HLS/DASH" class="block w-full px-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-800 dark:border-gray-700 dark:focus:ring-blue-600" value="<?= $manifest ?>">
        <input type="text" name="drm" placeholder="ClearKey kid:key" class="block w-full px-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-800 dark:border-gray-700 dark:focus:ring-blue-600" value="<?= $drm ?>">

        <!-- Submit Button -->
        <button type="submit" class="w-full py-2 text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded-lg">
            Play
        </button>
    </form>
</div>


<!-- END SEARCH BAR -->



<div class="container mx-auto p-4" id="video-container" style="display: none;"> 
    <!-- Main Video Section -->
    <div class="col-span-12 md:col-span-7 bg-gray-800 p-2 rounded-md max-w-xl mx-auto">
        <div class="relative aspect-w-16 aspect-h-9" id="player"></div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/flowbite@1.6.5/dist/flowbite.min.js"></script>
<script src="//ssl.p.jwpcdn.com/player/v/8.21.0/jwplayer.js"></script>
<script> jwplayer.key = 'XSuP4qMl+9tK17QNb+4+th2Pm9AWgMO/cYH8CI0HGGr7bdjo';</script>
<script src="../navbar.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ambil parameter dari URL
    const params = new URLSearchParams(window.location.search);
    const manifestUrl = params.get('manifest');
    const drmParam = params.get('drm');

    if (!manifestUrl) {
        console.error('Video manifest URL is required.');
        return;
    }

    // Konfigurasi dasar untuk JW Player
    const playerConfig = {
        playlist: [{
            file: manifestUrl
        }],
        width: "100%",
        height: "100%",
        aspectratio: "16:9",
        autostart: true,
        logo: {
            file: "/logo.svg",
            position: "top-right",
            hide: false
        },
        sharing: {},
        aboutlink: "https://nonton.micinproject.my.id",
        abouttext: "Micin Project"
    };

    // Menambahkan DRM jika ada parameter DRM
    if (drmParam) {
        // Parse drmParam menjadi keyId dan key
        const [keyId, key] = drmParam.split(':');
        if (keyId && key) {
            playerConfig.drm = {
                clearkey: {
                    keyId: keyId,
                    key: key
                }
            };
        } else {
            console.error('Invalid DRM format. Expected format is kid:key');
        }
    }

    // Inisialisasi JW Player dengan konfigurasi yang sudah diatur
    jwplayer("player").setup(playerConfig);

    // Menampilkan konten setelah video siap diputar
    jwplayer().on('ready', function() {
        document.getElementById('video-container').style.display = 'block';
    });
});

</script>

</body>
</html>
