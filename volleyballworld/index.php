<?php
// URL yang akan diminta
//$data['metadata'][13]['content']
// serverLoadedFeeds[4].feed.entry[0].extensions.wscThumbnailUrl

if (isset($_GET['group']) && !empty($_GET['group'])) {
    $url = "https://tv.volleyballworld.com/competition-groups/".$_GET['group']."?_data=routes%2F%24";
    $offset = 2;
    $hasImage = true;
    $class = "block w-full p-2 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700";

}else {
    $url = 'https://tv.volleyballworld.com/?_data=routes%2F_index';
    $hasImage = false;
    $offset = 1;
    $class = "block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700";
}

// Inisialisasi cURL
$ch = curl_init();

// Set opsi-opsi cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Agar hasil tidak dicetak langsung, tapi dikembalikan
curl_setopt($ch, CURLOPT_ENCODING, ''); // Menyediakan encoding untuk kompresi
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: */*',
    'Accept-Language: en-US,en;q=0.9',
    'Cache-Control: no-cache',
    'Connection: keep-alive',
    'Pragma: no-cache',
    'Referer: https://tv.volleyballworld.com/',
    'Sec-Fetch-Dest: empty',
    'Sec-Fetch-Mode: cors',
    'Sec-Fetch-Site: same-origin',
    'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36',
    'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Linux"'
]);

// Eksekusi cURL dan ambil hasilnya
$response = curl_exec($ch);
// Cek apakah terjadi kesalahan
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    // Cetak hasil jika tidak ada kesalahan
    // echo $response;
}

// Tutup sesi cURL
curl_close($ch);

$data = json_decode($response, true);
// echo "<pre>";
// var_dump ($data['serverLoadedFeeds']);
// die();

    $title = $data['metadata'][0]['title'] ?? 'VBTV';
    require_once '../templates/header.php';

 ?>
	  <main class="container mx-auto px-4 py-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach (array_slice($data['serverLoadedFeeds'], $offset) as $list): ?>
            <?php if (!empty($list['feed']) && !empty($list['feed']['entry'])): ?>
                <a href="/volleyballworld/explore.php?query=<?= htmlspecialchars($list['feed']['id']) ?>" class="<?= $class ?>">
                    <!-- serverLoadedFeeds[8].feed.entry[0].media_group[0].media_item[0].src -->
                    <?php if ($hasImage == true): ?>
                        <img src="<?= $list['feed']['entry'][0]['media_group'][0]['media_item'][0]['src'] ?>" alt="<?= $list['title'] ?>" class="w-full h-auto mb-4 rounded-lg">
                    <?php endif ?>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?= htmlspecialchars($list['feed']['title']) ?></h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">
                        <?= count(explode(' ', $list['feed']['entry'][0]['extensions']['description'])) > 15 ? implode(' ', array_slice(explode(' ', $list['feed']['entry'][0]['extensions']['description']), 0, 10)) . '...' : htmlspecialchars($list['feed']['entry'][0]['extensions']['description']) ?>
                    </p>
                </a>
            <?php endif; ?>
        <?php endforeach ?>

        <!-- Tambahkan lebih banyak card di sini jika diperlukan -->
    </div>
</main>


<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

</body>
</html>