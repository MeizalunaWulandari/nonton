<?php
// Inisialisasi cURL
$ch = curl_init();

// Setel URL dan opsi-opsi cURL
curl_setopt($ch, CURLOPT_URL, "https://kisskh.co/api/DramaList/List?page=1&type=0&sub=0&pageSize=4&country=0&status=0&order=2");
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
    'referer: https://kisskh.co/Explore',
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

// Periksa apakah ada kesalahan saat cURL dieksekusi
if(curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    // Decode JSON response
    $data = json_decode($response, true);
    
    // Tampilkan hasil
    // echo "<pre>";
    // var_dump($data);
    // die();
}

// Tutup cURL
curl_close($ch);
?>
<?php 
    $title = "kisskh";
    require_once '../templates/header.php';

 ?>
<!-- SEARCH BAR -->
<div class="container mx-auto px-4 pt-6 ">
    <form class="max-w-screen-xl mx-auto relative" action="explore.php">
        <input type="text" name="search" placeholder="Cari..." class="block w-full px-4 py-2 pr-20 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-800 dark:border-gray-700 dark:focus:ring-blue-600">
        <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-4 text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded-r-lg">
            Cari
        </button>
    </form>
</div>
<!-- END SEARCH BAR -->



    <!-- CARDS -->
      <main class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <a href="explore.php?cat=kdrama" class="block w-full p-2 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">K-Drama</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Streaming drama pilihan dari Korea Selatan</p>
            </a>
            <a href="explore.php?cat=cdrama" class="block w-full p-2 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">C-Drama</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Streaming drama pilihan dari China</p>
            </a>
            <a href="explore.php?cat=hollywood" class="block w-full p-2 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Hollywood</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Streaming drama dari dari Negeri Pam Sam</p>
            </a>
            <a href="explore.php?cat=animasi" class="block w-full p-2 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Kartun</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Streaming animasi pilihan </p>
            </a>
        <!-- Tambahkan lebih banyak card di sini jika diperlukan -->
        </div>
            <hr class="my-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($data['data'] as $list):?>
            <a href="play.php?id=<?= $list['id'] ?>" class="block w-full p-2 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <img src="<?= $list['thumbnail']; ?>" alt="<?= $list['title'] ?>" class="w-full h-auto mb-4 rounded-lg">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?= $list['title'] ?></h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Episodes: <?= $list['episodesCount']; ?></p>
                <p class="font-normal text-gray-700 dark:text-gray-400">Notes: <?= $list['label']; ?></p>
            </a>
            <?php endforeach; ?>
        <!-- Tambahkan lebih banyak card di sini jika diperlukan -->
        </div>
    </main>
<!-- CARD END -->

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>