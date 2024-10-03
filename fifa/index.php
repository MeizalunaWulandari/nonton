<?php

function makeRequest($url, $postData) {
    // Inisialisasi cURL
    $ch = curl_init($url);

    // Set opsi cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
    ]);

    // Set data untuk POST
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

    // Eksekusi permintaan dan ambil respons
    $response = curl_exec($ch);

    // Cek error
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }

    // Tutup sesi cURL
    curl_close($ch);

    // Decode JSON menjadi array
    return json_decode($response, true);
}

// URL endpoint
$url = 'https://www.plus.fifa.com/gatekeeper/api/v1/oauth2/token';

// Langkah 1: Ambil refresh_token dari file JSON
$refreshTokenData = json_decode(file_get_contents('token.json'), true);
$refreshToken = $refreshTokenData['refresh_token'] ?? null;

// Langkah 2: Data yang akan dikirim
$data = [
    'grant_type' => 'refresh_token',
    'refresh_token' => $refreshToken
];

// Langkah 3: Kirim permintaan untuk mendapatkan token baru
$responseData = makeRequest($url, $data);

// Langkah 4: Simpan respons yang diperlukan ke dalam file JSON
if ($responseData) {
    $accessToken = $responseData['access_token'] ?? null;
    $refreshToken = $responseData['refresh_token'] ?? null;

    // Buat array untuk menyimpan data token
    $tokenData = [
        'access_token' => $accessToken,
        'refresh_token' => $refreshToken,
    ];

    // Simpan data ke file token.json
    file_put_contents('token.json', json_encode($tokenData, JSON_PRETTY_PRINT));

    // Tampilkan token yang disimpan
}
?>
<?php

// GET TOKEN

// URL API yang ingin diakses
$url = 'https://www.plus.fifa.com/entertainment/api/v1/showcases/12959509-fd03-47a5-8f0d-53708908881b/child?orderBy=EDITORIAL';

// Headers yang akan dikirimkan
$headers = [
    'authority: www.plus.fifa.com',
    'accept: application/json',
    'accept-language: en',
    'authorization: Bearer '. $accessToken,
    'baggage: sentry-environment=production,sentry-release=FIFA-website%402.6.96,sentry-public_key=9111f8675be742b59a99225e084336e9,sentry-trace_id=8324d2d71c4a4ad7b3df18410956eb33',
    'cache-control: no-cache',
    'content-type: application/json; charset=UTF-8',
    'cookie: OptanonAlertBoxClosed=2024-09-14T11:49:45.775Z; eupubconsent-v2=CQE688AQE688AAcABBENBGFsAP_gAEPgAChQKaNV_G__bWlr8X73aftkeY1P9_h77sQxBhfJE-4FzLvW_JwXx2ExNA36tqIKmRIAu3bBIQNlGJDUTVCgaogVryDMak2coTNKJ6BkiFMRO2dYCF5vm4tj-QKY5vr991dx2B-t7dr83dzyz4VHn3a5_2a0WJCdA5-tDfv9bROb-9IOd_x8v4v8_F_rE2_eT1l_tevp7D9-cts7_XW-9_fff_9Ln_-uB_-_wU1AJMNCogDLIkJCDQMIIEAKgrCAigQAAAAkDRAQAmDAp2BgEusJEAIAUAAwQAgABRkACAAASABCIAIACgQAAQCBQABgAQDAQAMDAAGACwEAgABAdAxTAggUCwASMyIhTAhCASCAlsqEEgCBBXCEIs8CiAREwUAAAJABWAAICwWBxJICViQQJcQbQAAEACAQQAVCKTswBBAGbLUXiybRlaYFo-YLntMAyQAA.f_wACHwAAAAA;',
    'pragma: no-cache',
    'referer: https://www.plus.fifa.com/en/showcase/live-schedule/12959509-fd03-47a5-8f0d-53708908881b',
    'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Linux"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'sentry-trace: 8324d2d71c4a4ad7b3df18410956eb33-911a80497313c4c3',
    'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36',
    'x-chili-accept-language: en',
    'x-chili-authenticated: true',
    'x-chili-device-id: 244c3aa1-371f-4e21-b495-82b091bf1021',
    'x-chili-device-profile: WEB',
    'x-chili-device-store: CHILI',
    'x-chili-user-country: ID'
];

// Inisialisasi cURL
$ch = curl_init($url);

// Atur opsi cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Eksekusi permintaan
$response = curl_exec($ch);

// Periksa apakah ada kesalahan saat cURL dijalankan
if ($response === false) {
    echo 'Error: ' . curl_error($ch);
} else {
    // Tampilkan respons dalam format pre dan var_dump
    // echo '<pre>';
    // var_dump(json_decode($response, true));
    // echo '</pre>';
    $data = json_decode($response, true);
}

// Tutup sesi cURL
curl_close($ch);

// Hentikan eksekusi
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Micin Project | FIFA</title>
    <style>
        /* Global Style */
        body {
            font-family: Arial, sans-serif;
            background-color: #222; /* Dark mode background */
            color: #fff; /* Dark mode text */
            margin: 0;
            padding: 20px;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* Container styling for flex/grid */
        .container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); /* Ensures each card has a max width of 320px */
            gap: 10px;
            justify-content: center; /* Center the cards */
        }

        .card {
            margin: 2px;
            padding: 2px;
            max-width: 320px; /* Set max width for the card */
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            border-radius: 5px;
            background-color: #333; /* Background color for dark mode */
            display: inline-block;
            width: 100%; /* Ensure card takes up the full width of its container */
        }

        .card img {
            display: block;
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .card h2 {
            font-size: 16px;
            margin-top: 5px;
            margin-bottom: 0;
            color: #fff;
            text-align: center;
        }

        .card p {
            font-size: 12px;
            color: #aaa;
            text-align: center;
            margin: 5px 0;
        }

        /* Media query for larger screens (laptops/desktops) */
        @media (min-width: 768px) {
            .container {
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); /* 4 columns on larger screens */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php foreach ($data as $list): ?>
            <?php
            // Mengambil tanggal dan waktu
            $now = new DateTime('now', new DateTimeZone('UTC'));
            $contentStartDate = new DateTime($list["contentStartDate"], new DateTimeZone('UTC'));
            $contentEndDate = new DateTime($list["contentEndDate"], new DateTimeZone('UTC'));

            // Menentukan status berdasarkan waktu
            if ($now >= $contentStartDate && $now < $contentEndDate) {
                $status = 'Sedang tayang';
                $class = 'green';
            } elseif ($now >= $contentStartDate) {
                $status = 'segera tayang';
                $class = '';
            } elseif ($now >= $contentEndDate) {
                $status = 'live berakhir';
                $class = '';
            } else {
                $status = 'akan tayang';
                $class = '';
            }
                        
            $dateString = $list['contentStartDate']; // Misalnya '2024-10-03T12:45:00Z'

            // Membuat objek DateTime dari string dengan zona waktu UTC
            $date = new DateTime($dateString, new DateTimeZone('UTC'));

            // Mengubah zona waktu ke WIB (UTC+7)
            $date->setTimezone(new DateTimeZone('Asia/Jakarta'));

            // Mengubah format menjadi yang lebih mudah dibaca
            $readableDate = $date->format('d F Y H:i'); // Format: 20 Agustus 2024 | 20:30

            ?>
            <a href="/fifa/live.php?id=<?= $list['id'] ?>">
                <div class="card">
                    <img src="<?= htmlspecialchars($list['coverUrl']) ?>" alt="">
                    <h2><?= htmlspecialchars($list['standardTitle']) ?></h2>
                    <hr>
                    <h4 >Status: <span style="color: <?= $class ?>;"><?= $status ?></span></h4>
                    <h4 >Jadwal: <span style=""><?= $readableDate ?> | WIB</span></h4>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</body>
</html>
