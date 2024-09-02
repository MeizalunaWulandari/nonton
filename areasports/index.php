<?php 
$title = 'AREA Sport';
require_once '../templates/header.php';

// URL tujuan
$url = 'https://mob.webtvstream.bhtelecom.ba/client/channels_cat_2';

// Inisialisasi sesi cURL
$ch = curl_init($url);

// Set opsi cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json, text/javascript, */*; q=0.01',
    'Accept-Language: en-US,en;q=0.9',
    'Cache-Control: no-cache',
    'Connection: keep-alive',
    'Origin: https://mojawebtv.bhtelecom.ba',
    'Pragma: no-cache',
    'Referer: https://mojawebtv.bhtelecom.ba/',
    'Sec-Fetch-Dest: empty',
    'Sec-Fetch-Mode: cors',
    'Sec-Fetch-Site: same-site',
    'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36',
    'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Linux"'
]);

// Eksekusi permintaan dan ambil respons
$response = curl_exec($ch);

// Periksa apakah ada kesalahan
if(curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
}

// Tutup sesi cURL
curl_close($ch);

// Dekode JSON menjadi array PHP
$data = json_decode($response, true);

// Menyaring dan menyiapkan data untuk IndexedDB
$channels = isset($data['feed']) ? $data['feed'] : [];

$list_channels = [];
foreach ($channels as $channel) {
    // Membuat identifier unik dengan menggabungkan cid dan ch
    $unique_id = $channel['cid'] . '-' . $channel['ch'];
    $list_channels[] = [
        'id' => $unique_id,  // Identifier unik
        'title' => $channel['title'],
        'cid' => $channel['cid'],
        'ch' => $channel['ch'],
        'server' => $channel['server'],
        'epgtitle' => $channel['epgtitle'],
        'epgstart' => $channel['epgstart'],
        'epgduration' => $channel['epgduration'],
        'thumbnail' => "https://mob.webtvstream.bhtelecom.ba/client/thumbs/{$channel['ch']}.jpg",
        'manifest' => "https://webtvstream.bhtelecom.ba/{$channel['server']}/{$channel['ch']}.mpd"
    ];
}

// Ubah data channels menjadi JSON untuk digunakan di frontend
$list_channels_json = json_encode($list_channels);
?>

<!-- CARD -->
<main class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="channel-container">
        <!-- Card akan ditambahkan secara dinamis menggunakan JavaScript -->
    </div>
</main>
<!-- CARD END -->

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

<script>
    // Data channels dari PHP
    const data = <?= $list_channels_json ?>;

    // Fungsi untuk membuka database IndexedDB
    function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('SportChannelsDB', 1);

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                const objectStore = db.createObjectStore('channels', { keyPath: 'id' }); // Menggunakan 'id' sebagai keyPath
                objectStore.createIndex('title', 'title', { unique: false });
                objectStore.createIndex('ch', 'ch', { unique: false });
                objectStore.createIndex('server', 'server', { unique: false });
                objectStore.createIndex('manifest', 'manifest', { unique: false });
            };

            request.onsuccess = (event) => {
                resolve(event.target.result);
            };

            request.onerror = (event) => {
                reject(event.target.error);
            };
        });
    }

    // Fungsi untuk menghapus semua data dari IndexedDB
    async function clearDB() {
        const db = await openDB();
        const transaction = db.transaction(['channels'], 'readwrite');
        const objectStore = transaction.objectStore('channels');
        objectStore.clear();

        return new Promise((resolve, reject) => {
            transaction.oncomplete = () => {
                resolve();
            };
            transaction.onerror = (event) => {
                reject(event.target.error);
            };
        });
    }

    // Fungsi untuk menyimpan data ke IndexedDB
    async function saveChannels(channels) {
        await clearDB();  // Hapus data lama
        const db = await openDB();
        const transaction = db.transaction(['channels'], 'readwrite');
        const objectStore = transaction.objectStore('channels');

        channels.forEach(channel => {
            objectStore.put(channel);
        });

        return new Promise((resolve, reject) => {
            transaction.oncomplete = () => {
                resolve();
            };
            transaction.onerror = (event) => {
                reject(event.target.error);
            };
        });
    }

    // Fungsi untuk mengambil data dari IndexedDB
    async function getChannels() {
        const db = await openDB();
        const transaction = db.transaction(['channels'], 'readonly');
        const objectStore = transaction.objectStore('channels');

        return new Promise((resolve, reject) => {
            const request = objectStore.getAll();
            request.onsuccess = (event) => {
                resolve(event.target.result);
            };
            request.onerror = (event) => {
                reject(event.target.error);
            };
        });
    }

    // Fungsi untuk menampilkan card dari data yang ada di IndexedDB
    function displayChannels(channels) {
        const container = document.getElementById('channel-container');
        container.innerHTML = '';
        channels.forEach(channel => {
            const card = `
                <a href="/areasports/live.php?cid=${channel.id}" class="block w-full p-2 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <img src="${channel.thumbnail}" alt="${channel.title}" class="w-full h-auto mb-4 rounded-lg">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">${channel.title}</h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">${channel.epgtitle}</p>
                    <p class="font-normal text-gray-500 dark:text-gray-400">Mulai: ${channel.epgstart} | Durasi: ${channel.epgduration} menit</p>
                </a>
            `;
            container.insertAdjacentHTML('beforeend', card);
        });
    }

    // Simpan data ke IndexedDB saat halaman dimuat dan tampilkan card dari IndexedDB
    document.addEventListener('DOMContentLoaded', async () => {
        await saveChannels(data);
        const channels = await getChannels();
        displayChannels(channels);
    });
</script>

</body>
</html>
