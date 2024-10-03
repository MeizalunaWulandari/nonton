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
$url = 'https://www.plus.fifa.com/flux-capacitor/api/v1/videoasset?catalog='.$_GET['id'];

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
<?php

// Set URL dan data
$url = 'https://www.plus.fifa.com/flux-capacitor/api/v1/streaming/session';
$data = json_encode([
    'videoAssetId' => $data[0]['id'],
    'autoPlay' => false
]);

// Inisialisasi cURL
$ch = curl_init($url);

// Set opsi cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: www.plus.fifa.com',
    'accept: application/json',
    'accept-language: en',
    'baggage: sentry-environment=production,sentry-release=FIFA-website%402.6.96,sentry-public_key=9111f8675be742b59a99225e084336e9,sentry-trace_id=cb86bd72dd264e56b8052dd20b2a06db',
    'cache-control: no-cache',
    'content-type: application/json; charset=UTF-8',
    'cookie: OptanonAlertBoxClosed=2024-09-14T11:49:45.775Z; eupubconsent-v2=CQE688AQE688AAcABBENBGFsAP_gAEPgAChQKaNV_G__bWlr8X73aftkeY1P9_h77sQxBhfJE-4FzLvW_JwXx2ExNA36tqIKmRIAu3bBIQNlGJDUTVCgaogVryDMak2coTNKJ6BkiFMRO2dYCF5vm4tj-QKY5vr991dx2B-t7dr83dzyz4VHn3a5_2a0WJCdA5-tDfv9bROb-9IOd_x8v4v8_F_rE2_eT1l_tevp7D9-cts7_XW-9_fff_9Ln_-uB_-_wU1AJMNCogDLIkJCDQMIIEAKgrCAigQAAAAkDRAQAmDAp2BgEusJEAIAUAAwQAgABRkACAAASABCIAIACgQAAQCBQABgAQDAQAMDAAGACwEAgABAdAxTAggUCwASMyIhTAhCASCAlsqEEgCBBXCEIs8CiAREwUAAAJABWAAICwWBxJICViQQJcQbQAAEACAQQAVCKTswBBAGbLUXiybRlaYFo-YLntMAyQAA.f_wACHwAAAAA; AMCVS_2F2827E253DAF0E10A490D4E%40AdobeOrg=1; _sp_ses.214f=*; s_cc=true; fs_fliu=true; OptanonConsent=isGpcEnabled=0&datestamp=Thu+Oct+03+2024+21%3A16%3A26+GMT%2B0800+(Central+Indonesia+Time)&version=202311.1.0&browserGpcFlag=0&isIABGlobal=false&consentId=182dd905-9a27-42bb-a21f-c660fc251bf4&interactionCount=1&landingPath=NotLandingPage&groups=2%3A1%2C3%3A1%2C1%3A1%2C4%3A1%2CV2STACK42%3A1&hosts=H68%3A1%2CH39%3A1%2CH3%3A1%2CH98%3A1%2CH113%3A1%2CH96%3A1%2CH99%3A1%2CH1%3A1%2CH51%3A1%2CH36%3A1%2CH81%3A1%2CH94%3A1%2CH84%3A1%2CH87%3A1%2CH88%3A1%2CH70%3A1%2CH37%3A1%2CH89%3A1%2CH90%3A1%2CH48%3A1%2CH91%3A1%2CH71%3A1%2CH49%3A1%2CH69%3A1%2CH52%3A1%2CH43%3A1%2CH127%3A1%2CH5%3A1%2CH9%3A1&genVendors=&geolocation=%3B&AwaitingReconsent=false; s_sq=fifaprod%3D%2526c.%2526a.%2526activitymap.%2526page%253Dplusfifa%25253AZimbabwe%252520v%252520Zambia%252520%25257C%252520Semi-finals%252520%25257C%252520CAF%252520U-20%252520Africa%252520Cup%252520of%252520Nations%2525202025%252520%25257C%252520COSAFA%252520Qualifier%252520%2526link%253DWatch%252520free%2526region%253Dproduct-details%2526pageIDType%253D1%2526.activitymap%2526.a%2526.c%2526pid%253Dplusfifa%25253AZimbabwe%252520v%252520Zambia%252520%25257C%252520Semi-finals%252520%25257C%252520CAF%252520U-20%252520Africa%252520Cup%252520of%252520Nations%2525202025%252520%25257C%252520COSAFA%252520Qualifier%252520%2526pidt%253D1%2526oid%253Dfunctioncn%252528%252529%25257B%25257D%2526oidt%253D2%2526ot%253DSUBMIT; AMCV_2F2827E253DAF0E10A490D4E%40AdobeOrg=179643557%7CMCMID%7C46178554743917953206106102384194783263%7CMCOPTOUT-1727969215s%7CNONE%7CvVersion%7C5.5.0%7CMCIDTS%7C20000; _sp_id.214f=2f049be5-7442-46b2-9c87-ee65bd78b240.1726314542.2.1727962024.1726322566.a4333d75-458a-4e16-a6b6-5df97092bf90.dc8bfee2-986c-4c93-828d-a7c7cefa6f43.05ad85af-d295-45f3-adc9-1220d910a0b2.1727957539772.127',
    'origin: https://www.plus.fifa.com',
    'pragma: no-cache',
    'referer: https://www.plus.fifa.com/en/player/256d466c-872c-450b-99c8-a57e8c739ab3?catalogId=f2a5b05d-2920-4829-92cd-b192f8e72750&entryPoint=CTA',
    'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Linux"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'sentry-trace: cb86bd72dd264e56b8052dd20b2a06db-99623b3dea301fab',
    'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36',
    'x-chili-accept-language: en',
    'x-chili-accept-stream: mpd/cenc+h264;q=0.9, mpd/clear+h264;q=0.7, mp4/;q=0.1',
    'x-chili-accept-stream-mode: multi/codec-compatibility;q=0.8, mono/strict;q=0.7',
    'x-chili-authenticated: false',
    'x-chili-avod-compatibility: free,free-ads',
    'x-chili-device-id: 244c3aa1-371f-4e21-b495-82b091bf1021',
    'x-chili-device-profile: WEB',
    'x-chili-device-store: CHILI',
    'x-chili-event-compatibility: true',
    'x-chili-manifest-properties: subtitles',
    'x-chili-streaming-proto: https',
    'x-chili-user-country: ID'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// Eksekusi cURL
$response = curl_exec($ch);

// Cek error
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

// Tutup cURL
curl_close($ch);

// Decode response JSON
$responseData = json_decode($response, true);

// Ambil access_token dan asset_id


// Tampilkan hasil

$sessionId = $responseData['id'];
$sessionLocation = $responseData['location'];
// echo $sessionId;
echo "<pre>";
// echo $sessionLocation;
?>


<?php
// Inisialisasi session ID
//$sessionId = 'Zmx1eF9jYXBhY2l0b3I6ZWVkOTNmYWYtODhhNi00OWFiLTkxNGEtMWU1OWJiZDZiYTU3';

// URL endpoint
$url = 'https://www.plus.fifa.com/flux-capacitor/api/v1/streaming/urls';

// Inisialisasi cURL
$ch = curl_init($url);

// Menyiapkan data header
$headers = [
    'authority: www.plus.fifa.com',
    'accept: application/json',
    'accept-language: en',
    'baggage: sentry-environment=production,sentry-release=FIFA-website%402.6.96,sentry-public_key=9111f8675be742b59a99225e084336e9,sentry-trace_id=cb86bd72dd264e56b8052dd20b2a06db',
    'cache-control: no-cache',
    'cookie: OptanonAlertBoxClosed=2024-09-14T11:49:45.775Z; eupubconsent-v2=CQE688AQE688AAcABBENBGFsAP_gAEPgAChQKaNV_G__bWlr8X73aftkeY1P9_h77sQxBhfJE-4FzLvW_JwXx2ExNA36tqIKmRIAu3bBIQNlGJDUTVCgaogVryDMak2coTNKJ6BkiFMRO2dYCF5vm4tj-QKY5vr991dx2B-t7dr83dzyz4VHn3a5_2a0WJCdA5-tDfv9bROb-9IOd_x8v4v8_F_rE2_eT1l_tevp7D9-cts7_XW-9_fff_9Ln_-uB_-_wU1AJMNCogDLIkJCDQMIIEAKgrCAigQAAAAkDRAQAmDAp2BgEusJEAIAUAAwQAgABRkACAAASABCIAIACgQAAQCBQABgAQDAQAMDAAGACwEAgABAdAxTAggUCwASMyIhTAhCASCAlsqEEgCBBXCEIs8CiAREwUAAAJABWAAICwWBxJICViQQJcQbQAAEACAQQAVCKTswBBAGbLUXiybRlaYFo-YLntMAyQAA.f_wACHwAAAAA; AMCVS_2F2827E253DAF0E10A490D4E%40AdobeOrg=1; _sp_ses.214f=*; s_cc=true; fs_fliu=true; OptanonConsent=isGpcEnabled=0&datestamp=Thu+Oct+03+2024+21%3A16%3A26+GMT%2B0800+(Central+Indonesia+Time)&version=202311.1.0&browserGpcFlag=0&isIABGlobal=false&consentId=182dd905-9a27-42bb-a21f-c660fc251bf4&interactionCount=1&landingPath=NotLandingPage&groups=2%3A1%2C3%3A1%2C1%3A1%2C4%3A1%2CV2STACK42%3A1&hosts=H68%3A1%2CH39%3A1%2CH3%3A1%2CH98%3A1%2CH113%3A1%2CH96%3A1%2CH99%3A1%2CH1%3A1%2CH51%3A1%2CH36%3A1%2CH81%3A1%2CH94%3A1%2CH84%3A1%2CH87%3A1%2CH88%3A1%2CH70%3A1%2CH37%3A1%2CH89%3A1%2CH90%3A1%2CH48%3A1%2CH91%3A1%2CH71%3A1%2CH49%3A1%2CH69%3A1%2CH52%3A1%2CH43%3A1%2CH127%3A1%2CH5%3A1%2CH9%3A1&genVendors=&geolocation=%3B&AwaitingReconsent=false; s_sq=fifaprod%3D%2526c.%2526a.%2526activitymap.%2526page%253Dplusfifa%25253AZimbabwe%252520v%252520Zambia%252520%25257C%252520Semi-finals%252520%25257C%252520CAF%252520U-20%252520Africa%252520Cup%252520of%252520Nations%2525202025%252520%25257C%252520COSAFA%252520Qualifier%252520%2526link%253DWatch%252520free%2526region%253Dproduct-details%2526pageIDType%253D1%2526.activitymap%2526.a%2526.c%2526pid%253Dplusfifa%25253AZimbabwe%252520v%252520Zambia%252520%25257C%252520Semi-finals%252520%25257C%252520CAF%252520U-20%252520Africa%252520Cup%252520of%252520Nations%2525202025%252520%25257C%252520COSAFA%252520Qualifier%252520%2526pidt%253D1%2526oid%253Dfunctioncn%252528%252529%25257B%25257D%2526oidt%253D2%2526ot%253DSUBMIT; AMCV_2F2827E253DAF0E10A490D4E%40AdobeOrg=179643557%7CMCMID%7C46178554743917953206106102384194783263%7CMCOPTOUT-1727969215s%7CNONE%7CvVersion%7C5.5.0%7CMCIDTS%7C20000; _sp_id.214f=2f049be5-7442-46b2-9c87-ee65bd78b240.1726314542.2.1727962026.1726322566.a4333d75-458a-4e16-a6b6-5df97092bf90.dc8bfee2-986c-4c93-828d-a7c7cefa6f43.05ad85af-d295-45f3-adc9-1220d910a0b2.1727957539772.128',
    'pragma: no-cache',
    'referer: https://www.plus.fifa.com/en/player/256d466c-872c-450b-99c8-a57e8c739ab3?catalogId=f2a5b05d-2920-4829-92cd-b192f8e72750&entryPoint=CTA',
    'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Linux"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'sentry-trace: cb86bd72dd264e56b8052dd20b2a06db-99623b3dea301fab',
    'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36',
    'x-chili-accept-language: en',
    'x-chili-authenticated: false',
    'x-chili-avod-compatibility: free,free-ads',
    'x-chili-device-id: 244c3aa1-371f-4e21-b495-82b091bf1021',
    'x-chili-device-profile: WEB',
    'x-chili-device-store: CHILI',
    'x-chili-event-compatibility: true',
    'x-chili-streaming-session: ' . $sessionId,
    'x-chili-user-country: ID'
];

// Mengatur opsi cURL
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_ENCODING, ''); // Untuk mendukung kompresi
// curl_setopt($ch, CURLOPT_POST, true);

// Eksekusi permintaan dan ambil respons
$response = curl_exec($ch);

// Tutup cURL
curl_close($ch);

// Mengurai respons JSON
$urlRes = json_decode($response, true);

// Tampilkan hasil
// echo '<pre>';
// var_dump($urlRes);
// echo '</pre>';

?>
<?php
$url = $urlRes[0]['url']; // Ganti dengan URL yang diinginkan

// Inisialisasi sesi cURL
$ch = curl_init();

// Atur URL target
curl_setopt($ch, CURLOPT_URL, $url);

// Atur opsi agar hasil cURL dikembalikan sebagai string, bukan langsung dicetak
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Eksekusi cURL dan ambil responsnya
$response = curl_exec($ch);

// Tutup sesi cURL
curl_close($ch);

// Tampilkan respons
// echo '<pre>';
// var_dump($response);
// echo '</pre>';
// Load XML into SimpleXMLElement object
$xml = new SimpleXMLElement($response);

// Register namespace for cenc
$xml->registerXPathNamespace('cenc', 'urn:mpeg:cenc:2013');

// Ambil isi tag <cenc:pssh>
$psshArray = $xml->xpath('//cenc:pssh');

// Cek apakah hasil ditemukan
if (!empty($psshArray)) {
    $pssh = (string)$psshArray[0]; // Ambil nilai pertama dari hasil XPath
    // echo "Nilai PSSH: " . $pssh;
} else {
    echo "Tag <cenc:pssh> tidak ditemukan.";
}
?>

<?php
// Data yang akan digunakan

// URL untuk API
$url = "https://keysdb.net/api";

// Data yang akan dikirimkan dalam format JSON
$data = [
    "license_url" => $sessionLocation,
    "pssh" => $pssh,
    "cache" => false
];

// Inisialisasi curl
$ch = curl_init($url);

// Set opsi curl
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "X-API-Key: 51f23b210a4ee23e59b4c565c89c47207ab1eaf9973254052ee9c9fed1e9e846",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Eksekusi curl dan ambil respons
$response = curl_exec($ch);

// Cek apakah ada error
if(curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    // Tampilkan hasil respons
    $keyRes = json_decode($response, true);
    $key = base64_encode($keyRes['keys'][0]['key']);
}

// Tutup curl
curl_close($ch);
?>
<?php foreach ($urlRes as $url): ?>
    <?= $url['quality']  ?>: <a href="<?= $url['url'] ?>&ck=<?= $key ?>"><?= $url['url'] ?>&ck=<?= $key ?></a>
<?php endforeach ?>