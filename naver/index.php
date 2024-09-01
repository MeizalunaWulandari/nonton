<?php
// URL untuk permintaan API
$url = 'https://api-gw.sports.naver.com/cms/templates/mobile_sports_home?includeThumbnail=true';

// Inisialisasi cURL
$ch = curl_init($url);

// Set opsi cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: api-gw.sports.naver.com',
    'accept: application/json, text/plain, */*',
    'accept-language: en-US,en;q=0.7',
    'origin: https://m.sports.naver.com',
    'referer: https://m.sports.naver.com/',
    'sec-ch-ua: "Brave";v="111", "Not(A:Brand";v="8", "Chromium";v="111"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Linux"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-site',
    'sec-gpc: 1',
    'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36'
]);

// Eksekusi permintaan cURL dan ambil hasilnya
$response = curl_exec($ch);

// Cek jika ada error
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

// Tutup sesi cURL
curl_close($ch);

// Dekode hasil JSON
$data = json_decode($response, true);

// Tampilkan hasil
// echo "<pre>";
// var_dump($data['result']);
// echo "</pre>";
// die();
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="no-referrer" />
    <title>Micin Project | Naver</title>
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

	  <main class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">


<?php
function convertKSTtoWIB($kstTime) {
    $dateTime = new DateTime($kstTime, new DateTimeZone('Asia/Seoul'));
    $dateTime->setTimezone(new DateTimeZone('Asia/Jakarta'));
    return $dateTime->format('H:i');
}

foreach ($data['result']['templates'][0]['json']['liveBoxList'] as $liveBox):
    if ($liveBox['isOnAirTv'] == 'Y' || $liveBox['isOnAirTv'] == 'N'):
        if ($liveBox['thumbnailImage'] != NULL) {
            $thumbnailUrl = str_replace('image_1080', 'image_360', $liveBox['thumbnailImage']);
            preg_match('/live\/(\d+)\//', $liveBox['thumbnailImage'], $matches);
            $liveId = $matches[1] ?? null;
        } else {
            $thumbnailUrl = "https://sports-phinf.pstatic.net/20201019_37/1603088725798tmep6_PNG/09_etc.png";
            $liveId = $liveBox['gameId'];
        }

        // Determine the broadcast status
        $statusMessage = 'Belum Tayang'; // Default message
        if ($liveBox['statusCode'] === 'STARTED') {
            $statusMessage = 'Sedang Tayang';
        } elseif ($liveBox['statusCode'] === 'BEFORE') {
            $statusMessage = 'Segera Tayang';
        }
        ?>
        <a href="live.php?live=<?= $liveId ?>" class="block w-full p-2 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                
                <img src="<?php echo htmlspecialchars($thumbnailUrl); ?>" alt="<?php echo htmlspecialchars($liveBox['title']); ?>" class="w-full h-auto mb-4 rounded-lg">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?php echo htmlspecialchars($liveBox['title']); ?></h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">
                        Jadwal: <?php echo convertKSTtoWIB($liveBox['gameTimeHHmm']); ?> WIB<br>
                        Status: <span class="status"><?php echo htmlspecialchars($statusMessage); ?></span><br>
                        Kategori: <?= htmlspecialchars(ucfirst($liveBox['superCategoryId'])) ?>
                    </p>
        </a>
    <?php endif; ?>
<?php endforeach; ?>

        <!-- Tambahkan lebih banyak card di sini jika diperlukan -->
    </div>
</main>


<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>