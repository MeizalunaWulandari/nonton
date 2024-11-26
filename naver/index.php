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
$title = 'Naver';
require_once '../templates/header.php';
?>
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
            $liveId = $matches[1] ?? $liveBox['gameId'];
        } else {
            if ($liveBox['superCategoryId'] == "general") {
                $thumbnailUrl = "https://sports-phinf.pstatic.net/20201019_37/1603088725798tmep6_PNG/09_etc.png";
            }else{
                $thumbnailUrl = "https://ssl.pstatic.net/static/sports/common/livecenter/new/bg_live_".$liveBox['superCategoryId'].".jpg";
            }
            $liveId = $liveBox['gameId'];
        }

        // Determine the broadcast status
        $statusMessage = 'Belum Tayang'; // Default message
        if ($liveBox['statusCode'] === 'STARTED') {
            $class = 'font-bold text-green-400';
            $statusMessage = 'Sedang Tayang';
        } elseif ($liveBox['statusCode'] === 'BEFORE') {
            $class = 'text-gray-400';
            $statusMessage = 'Segera Tayang';
        }
        ?>
        <a href="live.php?live=<?= $liveId ?>" class="block w-full p-2 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                
                <img src="<?php echo htmlspecialchars($thumbnailUrl); ?>" alt="<?php echo htmlspecialchars($liveBox['title']); ?>" class="w-full h-auto mb-4 rounded-lg">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?php echo htmlspecialchars($liveBox['title']); ?></h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">
                        Jadwal: <?php echo convertKSTtoWIB($liveBox['gameTimeHHmm']); ?> WIB<br>
                        Status: <span class="status <?= $class ?>"><?php echo htmlspecialchars($statusMessage); ?></span><br>
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