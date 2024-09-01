<?php

header("Access-Control-Allow-Origin: *"); // Mengizinkan semua origin. Sesuaikan sesuai kebutuhan.
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Menangani preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}


$token = "https://www.vidio.com/live/875/tokens?type=dash";
$server = "https://etslive-v3-vidio-com-tokenized.akamaized.net/drm/dash/";
$dash_url = getDash($token);
$regex_hdntl = '/\?hdntl=[^"]+/';
$regex_kid = '/cenc:default_KID="([^"]*)"/';

$proxies = [
    'https://srv04.micinproject.my.id/index.php',
    'https://srv05.micinproject.my.id/index.php',
    'https://zuck.micinproject.my.id/index.php',
    'https://vercel-proxy-mu-six.vercel.app/proxy',
    // Tambahkan lebih banyak proxy sesuai kebutuhan
];

$responseData = [
    'http_code' => http_response_code(), // Menambahkan kode HTTP response
    'data' => []
];

if ($dash_url) {
    $mpd = getMpd($dash_url);
}

if ($mpd) {
    $hmac = regexSearch($regex_hdntl, $mpd);
}

if ($hmac) {
    // Mengambil data JSON dari body permintaan POST
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['vidio_id'])) {
        $id = $input['vidio_id'];
        $new_url = $server . $id . "_stream.mpd" . $hmac;

        if (getMpd($new_url)) {
            $new_mpd = getMpd($new_url);
            $kid = regexSearch($regex_kid, $new_mpd);
            if (isset($kid)) {
                $key = searchKey($kid);
                // Pilih proxy secara acak dari array
                if (isset($input['proxy']) && !empty($input['proxy']) && isValidUrl($input['proxy'])) {
                	$proxy = rtrim($input['proxy'], '/');
                	$proxied = str_replace('https://etslive-v3-vidio-com-tokenized.akamaized.net', $proxy, $new_url);
                	$responseData['private_proxy'] = TRUE;
                }else {
                	$selectedProxy = $proxies[array_rand($proxies)];
                	$proxied = str_replace('https://etslive-v3-vidio-com-tokenized.akamaized.net', $selectedProxy, $new_url);
                	$responseData['private_proxy'] = FALSE;
                }

                $responseData['data'] = [
                    'vidio_id' => $id,
                    'KID' => $kid,
                    'manifest_url' => $new_url,
                    'proxied_url' => $proxied,
                    'kid' => $key['kid'],
                    'key' => $key['key']
                ];
            } else {
                $responseData['data'] = [
                    'error' => 'KID tidak ditemukan.'
                ];
            }
        } else {
            $responseData['data'] = [
                'error' => 'ID Invalid/Kesalahan Server.'
            ];
        }
    } else {
        $responseData['data'] = [
            'error' => 'Parameter vidio_id tidak ditemukan dalam body permintaan.'
        ];
    }
} else {
    $responseData['data'] = [
        'error' => 'Tidak dapat mengambil DASH URL.'
    ];
}

// Set header untuk JSON
header('Content-Type: application/json');

// Encode array menjadi JSON dan kirim
echo json_encode($responseData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

function getDash($url)
{
    // Inisialisasi sesi cURL untuk mendapatkan token
    $ch = curl_init($url);

    // Set opsi cURL
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'authority: www.vidio.com',
        'accept: */*',
        'accept-language: en-US,en;q=0.9',
        'cache-control: no-cache',
        'content-length: 0',
        'cookie: ahoy_visitor=2d901550-990d-4ccb-9f16-a1aad1eee649; g_state={"i_l":0}; remember_user_token=eyJfcmFpbHMiOnsibWVzc2FnZSI6Ilcxc3hOVFEzTURNeU1qaGRMQ0lrTW1Fa01UQWtTVVZFTG5KS0x6ZFNlSFYzTkRGNlMwNXViak40TGlJc0lqRTNNak01T0RNME1ESXVPVGt6TVRZeU5DSmQiLCJleHAiOiIyMDI2LTA4LTE4VDEyOjE2OjQyLjk5M1oiLCJwdXIiOiJjb29raWUucmVtZW1iZXJfdXNlcl90b2tlbiJ9fQ%3D%3D--698d874cab8f6e825e906a3f1c2539517831a42b; plenty_id=154703228; access_token=eyJhbGciOiJIUzI1NiJ9.eyJkYXRhIjp7InR5cGUiOiJhY2Nlc3NfdG9rZW4iLCJ1aWQiOjE1NDcwMzIyOH0sImV4cCI6MTcyNDA2OTgxOX0.cCQ57f2K2KEM2rYFGCN_vwFjxVgAUsvum5HyymQ2OmI; ahoy_visit=a0dfe632-5527-4860-98be-13346240d7ea; country_id=ID; shva=1; _vidio_session=d0N3OW5EbUtKRWpsc2p3Tk9FSkl2VWdyL1ZvMkNMQXdRY1ozSnl5bVdRVUxLQkRYc0JUK0EyVEVvRjFPM2lNTTU1cW1Qa09KRDc3ZXNFbm9ZYldpZ01CSzh6Rm9ZNmNzMWNvakFRZlVka0FlY09iREE5aW81bGE4R2ZRM2U2cHJJeXRSQ3Q3RVVFcWNtU2xzeFF5b1Y5cDIraTByUVJzT2VibzJ6SG42ZjBUejRwejNrUU1pOG1rU3QzRVpsYzIwWVBnN0VtRmJIRVdhZzZnVnNqMFc0QzU3UlFmVDVBcjZFck5BclA3ekloNUdCTmkvS1V1M1oreU52Vy9qUFJtU2did293eGdrRXNseW44blU5S291dUNrYWw1ajhuckRqSEs3Qi9OM3BDMTlqY3hTbFhlTmIrTVRvQ3kvOWF4S3ZzUEorUStPMTF6M2pTUEhQWHBHbWUwbXlta3h6WGF4U0VWSk1IZEpyb3RaVTdrMkFnS3hKRU15NXBPNXdNYXJLaSt4NWJvMXQ3K0M4NU1JMmJqcjhwRlphN1VZRzJxMmVoeTIvTVRZZ3Vkbz0tLVhFVlcrSlpYSkZWT25TSXlVZUVjZEE9PQ%3D%3D--a6bcbfead0b5e27eecebceb1d34eff29459b78cf',
        'origin: https://www.vidio.com',
        'pragma: no-cache',
        'referer: https://www.vidio.com/live/205-indosiar',
        'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Linux"',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36'
    ]);

    // Eksekusi cURL dan simpan responsnya
    $response = curl_exec($ch);

    // Tutup sesi cURL
    curl_close($ch);

    // Mengubah respons JSON menjadi array
    $data = json_decode($response, true);

    if (isset($data['dash_url'])) {
        return $data['dash_url'];
    } else {
        return "TIDAK ADA DASH PADA: ".$url;
    }
}

function getMpd($url)
{
    // Inisialisasi sesi cURL untuk mengakses DASH URL
    $ch = curl_init($url);

    // Set opsi cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Mengikuti redirect jika ada
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36'
    ]);

    // Eksekusi cURL dan simpan responsnya
    $response = curl_exec($ch);

    // Cek jika ada kesalahan selama eksekusi cURL
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        // Tampilkan hasil langsung tanpa menyimpan ke file
        return $response;
    }

    // Tutup sesi cURL
    curl_close($ch);
}

function regexSearch($regex, $mpd)
{
    preg_match($regex, $mpd, $matches);

    // Cek jika ada hasil yang ditemukan
    if (!empty($matches[0])) {
        // Hapus bagian "cenc:default_KID=" dari hasil jika ditemukan
        if (strpos($matches[0], 'cenc:default_KID=') === 0) {
            return substr($matches[0], strlen('cenc:default_KID="'), -1); // Menghapus prefix dan tanda kutip akhir
        }
        return $matches[0];
    } else {
        return $regex . ' Regex Not Found';
    }
}

function searchKey($kid) {
    $url = 'https://keysdb.net/search';

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $kid);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'authority: keysdb.net',
        'accept: */*',
        'accept-language: en-US,en;q=0.9',
        'cache-control: no-cache',
        'content-type: application/x-www-form-urlencoded',
        'cookie: remember_token=948495690202492928|54f94e333c93685d5fee194775991ee919feb15bccbd35d029f48b3bff0359655cd3b4cec9acc2ddfbfb6aa85c55010f131cb910690097116ffe32e373b0abc7; session=eyJfZnJlc2giOmZhbHNlLCJfdXNlcl9pZCI6Ijk0ODQ5NTY5MDIwMjQ5MjkyOCJ9.ZsckPg.g6fformejn2KR8j5Q74TKYdZ8oA',
        'origin: https://keysdb.net',
        'pragma: no-cache',
        'referer: https://keysdb.net/search',
        'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
        'sec-ch-ua-mobile: ?0',
        'sec-ch-ua-platform: "Linux"',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36',
        'x-api-key: undefined'
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        // Decode JSON response
        $data = json_decode($response, true);

        // Periksa apakah JSON berhasil di-decode
        if (json_last_error() === JSON_ERROR_NONE) {
            // Ambil nilai 'key' dari data JSON
            if (isset($data['keys'][0]['key'])) {
                // Pisahkan nilai 'key' berdasarkan tanda ':'
                $keyParts = explode(':', $data['keys'][0]['key']);
                
                // Format hasil
                $result = [
                    'kid' => $keyParts[0] ?? 'Tidak ada KID',
                    'key' => $keyParts[1] ?? 'Tidak ada key'
                ];

                // Tampilkan hasil dalam format array
                return $result;
            } else {
                echo "Key tidak ditemukan dalam respons.";
            }
        } else {
            echo "JSON Error: " . json_last_error_msg();
        }
    }

    curl_close($ch);
}
function isValidUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}
