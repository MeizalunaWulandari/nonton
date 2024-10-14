<?php
// Ambil URL target dari parameter query
$targetUrl = $_GET['url'] ?? null;

if ($targetUrl) {
    // Inisialisasi cURL
    $ch = curl_init($targetUrl);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
        'Referer: ',
        'sec-ch-ua-mobile: ?0',
        'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36',
        'sec-ch-ua-platform: "Linux"',
    ]);

    // Eksekusi cURL
    $response = curl_exec($ch);

    // Cek jika ada error
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        // Set header untuk mengirimkan konten yang diterima
        header('Content-Type: application/vnd.apple.mpegurl'); // Atau sesuai dengan jenis konten yang diharapkan
        echo $response; // Tampilkan respons dari URL target
    }

    // Tutup cURL
    curl_close($ch);
} else {
    echo 'URL tidak diberikan.';
}
?>
