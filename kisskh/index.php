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
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="no-referrer" />
    <title>Micin Project | Kisskh</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@1.6.5/dist/flowbite.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 dark:bg-gray-900 dark:border-gray-800 text-white">

	<!-- NAVBAR -->



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