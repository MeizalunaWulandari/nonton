<?php 
$id = $_GET['cid'];
$title = "OneSports";
require_once '../templates/header.php';
?>

<div class="container mx-auto p-4">
    <!-- Button to toggle episodes on mobile -->
    <div class="md:hidden mb-4">
        <button id="episodes-button" class="w-full p-2 bg-blue-600 text-white rounded-md">Show Channels</button>
    </div>

    <div class="grid grid-cols-12 gap-4">
        <!-- Sidebar for Episodes -->
        <div class="col-span-12 md:col-span-2 bg-gray-800 p-2 rounded-md" id="episodes-list">
            <h2 class="text-xl font-bold mb-4">Channels</h2>
            <ul id="channel-list" class="space-y-2 overflow-y-auto" style="max-height: 70vh;">
                <!-- Daftar channel akan dimuat di sini oleh JavaScript -->
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

<script>
// Fungsi membuka IndexedDB dan mendapatkan semua data channel
function getAllChannels() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('SportChannelsDB', 1);
        request.onerror = (event) => {
            console.error('Error opening IndexedDB:', event);
            reject(event);
        };

        request.onsuccess = (event) => {
            const db = event.target.result;
            const transaction = db.transaction(['channels'], 'readonly');
            const objectStore = transaction.objectStore('channels');
            const allChannels = [];

            objectStore.openCursor().onsuccess = function(event) {
                const cursor = event.target.result;
                if (cursor) {
                    allChannels.push(cursor.value);
                    cursor.continue();
                } else {
                    resolve(allChannels);
                }
            };

            objectStore.openCursor().onerror = function(event) {
                console.error('Error reading IndexedDB:', event);
                reject(event);
            };
        };
    });
}

// Menampilkan channel di sidebar
function displayChannels(channels) {
    const channelList = document.getElementById('channel-list');
    if (!channelList) {
        console.error('Channel list element not found.');
        return;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const cid = urlParams.get('cid');

    channels.forEach(channel => {
        const listItem = document.createElement('li');
        listItem.textContent = channel.title;
        listItem.style.cursor = 'pointer'; // Add a cursor style to make it clickable
        listItem.onclick = function() {
            // Handle channel item click if needed
            window.location.href = `?cid=${channel.id}`;
        };

        // Tambahkan kelas sesuai dengan status channel
        if (channel.id === cid) {
            listItem.className = 'block p-2 bg-gray-700 rounded hover:bg-gray-600'; // Kelas untuk channel yang sesuai
        } else {
            listItem.className = 'block p-2 rounded hover:bg-gray-600'; // Kelas untuk channel yang tidak sesuai
        }

        channelList.appendChild(listItem);
    });

    // Menampilkan channel yang sesuai dengan parameter cid
    const filteredChannels = channels.filter(channel => channel.id === cid);
    if (filteredChannels.length === 0) {
        console.warn('No channels found for the given cid.');
    }

    // Menampilkan video jika ada channel yang sesuai
    if (filteredChannels.length > 0) {
        const channel = filteredChannels[0]; // Mengambil channel pertama yang sesuai
        jwplayer("player").setup({
            playlist: [{
                file: channel.manifest, // Gunakan manifest URL dari IndexedDB
                drm: {
                    clearkey: {
                        keyId: "c18b6aa739be4c0b774605fcfb5d6b68", // Static KeyID
                        key: "e41c3a6f7532b2e3a828d9580124c89d" // Static Key
                    }
                }
            }],
            width: "100%",
            aspectratio: "16:9",
            logo: {
                file: "/logo.svg", // Path ke file logo SVG
                position: "top-right", // Posisi logo
                hide: false
            },
            sharing: {},
            generateSEOMetadata: true,
            autostart: "viewable",
            aboutlink: "https://nonton.micinproject.my.id",
            abouttext: "Micin Project"
        });
    }
}

// Mengambil data channel dan menginisialisasi JWPlayer
getAllChannels()
    .then(channels => {
        displayChannels(channels);
    })
    .catch(error => console.error('Error setting up JWPlayer:', error));

// Menambahkan event listener untuk tombol "Show Channels"
const episodesButton = document.getElementById('episodes-button');
const episodesList = document.getElementById('episodes-list');

if (episodesButton && episodesList) {
    episodesButton.addEventListener('click', function() {
        // Toggle (tampilkan/sembunyikan) daftar channel
        if (episodesList.style.display === 'none' || episodesList.style.display === '') {
            episodesList.style.display = 'block';
            episodesButton.textContent = 'Hide Channels';
        } else {
            episodesList.style.display = 'none';
            episodesButton.textContent = 'Show Channels';
        }
    });

    // Pastikan daftar channel disembunyikan saat pertama kali di-load jika layar kecil
    if (window.innerWidth <= 768) {
        episodesList.style.display = 'none';
    }
} else {
    console.error('Episodes button or list element not found.');
}

</script>

</body>
</html>
