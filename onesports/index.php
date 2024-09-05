<?php 
    $title = "OneSports";
    require_once '../templates/header.php';

 ?><div class="container mx-auto p-4">
    <!-- Button to toggle episodes on mobile -->
    <div class="md:hidden mb-4">
        <button id="episodes-button" class="w-full p-2 bg-blue-600 text-white rounded-md">Show Channels</button>
    </div>

    <div class="grid grid-cols-12 gap-4">
        <!-- Sidebar for Episodes -->
        <div class="col-span-12 md:col-span-2 bg-gray-800 p-2 rounded-md" id="episodes-list">
            <h2 class="text-xl font-bold mb-4">Channels</h2>
            <ul class="space-y-2 overflow-y-auto" style="max-height: 70vh;">
                <?php
                // Mendapatkan ID dari URL
                $id = isset($_GET['id']) ? $_GET['id'] : 1;

                // Membaca data dari stream.json
                $json = file_get_contents('stream.json');
                $data = json_decode($json, true);

                // Menampilkan daftar episode (channels)
                foreach ($data['streams'] as $stream) {
                    $activeClass = $stream['id'] == $id ? 'bg-gray-700' : '';
                    echo '<li><a href="index.php?id=' . $stream['id'] . '" class="block p-2 ' . $activeClass . ' rounded hover:bg-gray-600">' . $stream['name'] . '</a></li>';
                }
                ?>
            </ul>
        </div>

        <!-- Main Video Section -->
        <div class="col-span-12 md:col-span-7 bg-gray-800 p-2 rounded-md">
            <h2 class="text-xl font-bold mb-4">Video Player</h2>
            <div class="relative" id="player" style="padding-top: 44.44%;"></div>
        </div>

        <!-- Live Chat Section -->
        <?php 
            require '../templates/chat.php';
        ?>
        <!-- Live Chat Section -->
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@1.6.5/dist/flowbite.min.js"></script>
<script src="//ssl.p.jwpcdn.com/player/v/8.21.0/jwplayer.js"></script>
<script> jwplayer.key = 'XSuP4qMl+9tK17QNb+4+th2Pm9AWgMO/cYH8CI0HGGr7bdjo';</script>

 <script>
    // Mendapatkan ID dari URL menggunakan PHP
    const id = <?php echo $id; ?>;

    // Mengambil data dari stream.json
    fetch('stream.json')
        .then(response => response.json())
        .then(data => {
            // Memilih stream berdasarkan ID dari URL
            const selectedStream = data.streams.find(stream => stream.id == id);

            // Konfigurasi JWPlayer
            jwplayer("player").setup({
                playlist: [{
                    file: selectedStream.url,
                    drm: {
                        clearkey: {
                            keyId: selectedStream.kid,
                            key: selectedStream.key
                        }
                    }
                }],
                width: "100%",
                aspectratio: "16:9",
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
        })
        .catch(error => console.error('Error fetching the JSON:', error));
    </script>
   <script>
    // Mengambil referensi elemen tombol dan daftar episode
    const episodesButton = document.getElementById('episodes-button');
    const episodesList = document.getElementById('episodes-list');

    // Menambahkan event listener untuk tombol "Show Channels"
    episodesButton.addEventListener('click', function() {
        // Toggle (tampilkan/sembunyikan) daftar episode
        if (episodesList.style.display === 'none' || episodesList.style.display === '') {
            episodesList.style.display = 'block';
            episodesButton.textContent = 'Hide Channels'; // Mengubah teks tombol menjadi "Hide Channels"
        } else {
            episodesList.style.display = 'none';
            episodesButton.textContent = 'Show Channels'; // Mengubah teks tombol menjadi "Show Channels"
        }
    });

    // Pastikan daftar channel disembunyikan saat pertama kali di-load
    if (window.innerWidth <= 768) {
        episodesList.style.display = 'none';
    }
</script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="/assets/js/chat.js"></script>
</body>
</html>
