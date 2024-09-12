<?php 

// URL yang ingin diakses
$url = 'https://zapp-5434-volleyball-tv.web.app/jw/playlists/FljcQiNy';

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

$data = json_decode($response, true);
// echo "<pre>";
// var_dump ($data);
// die();

    $title = 'VBTV';
    require_once '../templates/header.php';

 ?>
     
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
                ?>

                <a href="live.php?event=<?= $list['id'] ?>" class="block w-full p-2 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <img src="<?= $list['media_group']['0']['media_item']['0']['src'] ?>" alt="<?= $list['title'] ?>" class="w-full h-auto mb-4 rounded-lg">
                    <h5 class="mb-2 font-bold tracking-tight text-gray-900 dark:text-white"><?= $list['title'] ?></h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Jadwal: <?= $displayTime; ?></p>
                </a>

        <?php endforeach ?>

        </div>
    </main>
<!-- CARD END -->

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>