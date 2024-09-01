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

$title = 'Video Player';
require_once 'templates/header.php';
?>

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
