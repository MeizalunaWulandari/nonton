<?php

// GET TOKEN

// URL API yang ingin diakses
$url = 'https://www.plus.fifa.com/entertainment/api/v1/showcases/12959509-fd03-47a5-8f0d-53708908881b/child?orderBy=EDITORIAL';

// Headers yang akan dikirimkan
$headers = [
    'authority: www.plus.fifa.com',
    'accept: application/json',
    'accept-language: en',
    'authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJFUzI1NiJ9.eyJjaF9sYXN0X25hbWUiOiIiLCJzdWIiOiJiZjcwOTIwZC03MjA3LTQzOWEtYmE3ZS00NTc0ZDQwMThjMmIiLCJjaF91c2VybmFtZSI6ImNvb2xnYXRlNDI0QGdtYWlsLmNvbSIsImlzcyI6ImI1NzQzMmQyNjM5YzNhODEzZTg4OGU2NGIzODU4YzcyYzk1OTBjNmUiLCJjaF9maXJzdF9uYW1lIjoiWnVjY2hpbmkiLCJjaF9pc190ZXN0X3VzZXIiOmZhbHNlLCJjaF9na190b2tlbiI6ImE4MDAzY2M0LTkzMmMtMzhmMS1iOWQ0LWE3ODEwYjdmMGRhYiIsImNoX3JlZ2lzdHJhdGlvbl9kYXRlIjoiMjAyNC0wOS0xNFQwNzozNTo1OS43NzE2NTJaIiwiY2xpZW50X2lkIjoiMTdkZWY3OTgtNDRiNS00ODcwLTgyNmMtODZkNzg1ZmQ5Y2VmIiwiY2hfc3RyZWFtaW5nX2NhcGFiaWxpdHkiOnRydWUsIm5iZiI6MTcyNzk1NzYyOCwiY2hfcmVnaXN0cmF0aW9uX2NvdW50cnkiOiJJRCIsImNoX2RldmljZV9pZCI6IjI0NGMzYWExLTM3MWYtNGUyMS1iNDk1LTgyYjA5MWJmMTAyMSIsInNjb3BlIjoidHYuY2hpbGkuY29tbW9uLkNVU1RPTUVSIiwiZXhwIjoxNzI3OTU4ODI4LCJpYXQiOjE3Mjc5NTc2MjgsImp0aSI6ImE4MDAzY2M0LTkzMmMtMzhmMS1iOWQ0LWE3ODEwYjdmMGRhYiJ9.ZAXd1uJoaDsTdCPILFVEH0Mf2Hm8vU8_0-DAnwNsd2niRPxdq3OZjpF2QjQdpcXX1-hfBNCvMcq8vMs7f3pgYw',
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
    echo '<pre>';
    var_dump(json_decode($response, true));
    echo '</pre>';
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
    <title>Simple Cards with Inline Styles</title>
</head>
<body>
    <h1>Daftar Card</h1>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <?php foreach ($data as $list): ?>
            <div style="border: 1px solid #ddd; padding: 15px; width: 200px; box-shadow: 2px 2px 5px rgba(0,0,0,0.1); border-radius: 5px;">
                <h2 style="font-size: 18px;"><?= htmlspecialchars($list['standardTitle']) ?></h2>
                <p style="font-size: 14px;"><?= htmlspecialchars($list['description']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
