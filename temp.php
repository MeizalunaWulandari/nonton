<?php
$title = 'Temporary Channel';
require_once 'templates/header.php';
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
            require_once 'templates/chat.php';
        ?>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@1.6.5/dist/flowbite.min.js"></script>
<script src="//ssl.p.jwpcdn.com/player/v/8.21.0/jwplayer.js"></script>
<script> jwplayer.key = 'XSuP4qMl+9tK17QNb+4+th2Pm9AWgMO/cYH8CI0HGGr7bdjo';</script>
<script src="../navbar.js"></script>

<script>
        // Data dari PHP
        const streamData = true;


        // Jika data berhasil diambil, inisialisasi JW Player
        if (streamData) {
            const hlsUrl = 'https://sin-prod-catalyst-2.lp-playback.studio:443/hls/video+07d9agzvqoryv2aa/index.m3u8';

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
                aboutlink: "https://t.me/+KkUdl1Sr5vU0YTZl",
                abouttext: "Join Telegram"
            });
        } else {
            console.error('Failed to get HLS URL:', streamData);
        }
    </script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="/assets/js/chat.js"></script>
</body>
</html>
