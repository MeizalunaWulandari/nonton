<?php 
session_start();


// Periksa apakah parameter 'query' ada di URL
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $query = $_GET['query'];

    if ($query && isset($_SESSION['feeds'][$query])) {
        $url = $_SESSION['feeds'][$query]['path'];
    }else {
        $url = 'https://zapp-5434-volleyball-tv.web.app/jw/playlists/' . $_GET['query'];
    }
}else if (isset($_GET['search']) && !empty($_GET['search'])){
    $url = 'https://zapp-5434-volleyball-tv.web.app/jw/playlists/mfEITzNA?search=' . $_GET['search'];
}else {
    // Jika tidak ada, redirect ke index.php
    header('Location: index.php');
    exit; // Hentikan eksekusi skrip setelah redirect
}

// var_dump ($url);
// die();
// URL yang ingin diakses, dengan query diambil dari URL


// Inisialisasi cURL
$ch = curl_init();

// Setel opsi cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Agar hasil dikembalikan sebagai string alih-alih langsung ditampilkan
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Nonaktifkan verifikasi sertifikat SSL

// Eksekusi permintaan cURL
$response = curl_exec($ch);

// Tutup cURL
curl_close($ch);

// Decode JSON ke array
$data = json_decode($response, true);
// if (isset($_GET['query'])) {
//     $formattedData = json_decode($response, true);
// }else if (isset($_GET['search'])){
//     $formattedData = json_decode($response, true);
// }

// Debugging: menampilkan data yang didapatkan dengan var_dump
// echo "<pre>";
// var_dump($data);
// die(); // Menghentikan eksekusi agar hanya dump yang tampil

// Jika kamu ingin melanjutkan, matikan die() di atas
$title = $data['title'] ?? 'VBTV';
require_once '../templates/header.php';

?>
     <!-- SEARCH BAR -->
<div class="container mx-auto px-4 pt-4">
    <form class="mx-auto relative" action="explore.php" method="get">
        <input type="text" name="search" placeholder="Cari..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" class="block w-full px-4 py-2 pr-20 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-800 dark:border-gray-700 dark:focus:ring-blue-600">
        <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-4 text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded-r-lg">
            Cari
        </button>
    </form>
</div>
<!-- END SEARCH BAR -->

    <!-- CARD -->
	<main class="container mx-auto px-4 py-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($data['entry'] as $list): ?>

                <?php
                    // Ambil waktu tayang dari data (UTC)
                    $scheduleTime = $list['extensions']['VCH.ScheduledStart'];

                    // Mengonversi waktu tayang menjadi objek DateTime
                    $scheduleDate = new DateTime($scheduleTime, new DateTimeZone('UTC'));
                    $scheduleDate->setTimezone(new DateTimeZone('Asia/Jakarta')); // Set timezone ke WIB (Asia/Jakarta)

                    // Waktu sekarang dalam WIB
                    $currentDate = new DateTime('now', new DateTimeZone('Asia/Jakarta'));

                    // Cek apakah tayang hari ini
                    if ($scheduleDate->format('Y-m-d') === $currentDate->format('Y-m-d')) {
                        // Jika tayang hari ini, tetap gunakan waktu tayang sesuai jadwal yang telah dikonversi ke WIB
                        $displayTime = $scheduleDate->format('H:i') . ' WIB';
                    } else {
                        // Jika bukan hari ini, tampilkan tanggal dan waktu asli dari jadwal (WIB)
                        $displayTime = $scheduleDate->format('d F Y | H:i') . ' WIB';
                    }

                    if (isset($list['extensions']['series_playlist'])) {
                        $link = "explore.php?query=" . $list['extensions']['series_playlist']; 
                    }else if(isset($list['extensions']['hubPlaylists']['playlist1'])) {
                        // $link = "explore.php?query=" . $list['extensions']['hubPlaylists']['playlist1']; 
                        $link = "index.php?group=" . $list['id'];
                    }else{
                        $link = "live.php?event=" . $list['id']; 
                    }

                ?>


                <a href="<?= $link ?>" class="block w-full p-2 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <img src="<?= $list['media_group']['0']['media_item']['0']['src'] ?>" alt="<?= $list['title'] ?>" class="w-full h-auto mb-4 rounded-lg">
                    <h5 class="mb-2 font-bold tracking-tight text-gray-900 dark:text-white"><?= $list['title'] ?></h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Jadwal: <?= $displayTime; ?></p>
                    <?php 
                            if ($list['type']['value'] == 'liveEvent-now') {
                                $textStatus = 'Sedang Tayang';
                                $class = 'font-bold text-green-400';
                            } elseif ($list['type']['value'] == 'liveEvent-future') {
                                $textStatus = 'Segera Tayang';
                                $class = 'text-gray-400';
                            } elseif ($list['type']['value'] == 'liveEvent-vod') {
                                $textStatus = 'Replay';
                                $class = 'text-gray-400';
                            } elseif ($list['type']['value'] == 'original_series') {
                                $textStatus = 'Original Documentaries';
                                $class = 'text-gray-400';
                            } elseif ($list['type']['value'] == 'highlight') {
                                $textStatus = 'Highlight';
                                $class = 'text-gray-400';
                            } else {
                                $textStatus = 'Unknown';
                                $class = 'text-gray-400';
                            }
                            ?>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Status: <span class="<?= $class ?>"><?= $textStatus ?></span></p>

                </a>

        <?php endforeach ?>

        </div>
    </main>
<!-- CARD END -->

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>