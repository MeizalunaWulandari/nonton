
<?php
$token = "https://www.vidio.com/live/875/tokens?type=dash";
$server = "https://etslive-v3-vidio-com-tokenized.akamaized.net/drm/dash/";
$dash_url = getDash($token);
$regex_hdntl = '/\?hdntl=[^"]+/';
$regex_kid = '/cenc:default_KID="([^"]*)"/';
	
	if ($dash_url) {
		echo $dash_url;
		$mpd =  getMpd($dash_url);
		// echo $mpd;
	}

	if ($mpd) {
		$hmac = regexSearch($regex_hdntl,$mpd);
		echo $hmac;
	}

	if ($hmac) {
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
				$new_url = $server.$id."_stream.mpd".$hmac;
				echo $new_url;
				if (getMpd($new_url)) {
					$new_mpd = getMpd($new_url);
					$data = regexSearch($regex_kid,$new_mpd);
					echo "==============================".$data. "==============================";
					echo $new_mpd;
				}else{
					echo "ID Invalid/Kesalahan Server";
				}

			}else{

				echo $dash_url;
			}
	}



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
$data =  json_decode($response, true);

	if (isset($data['dash_url'])) {
		return $data['dash_url'];
	}else{
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
        // Set header untuk plain text
        header('Content-Type: text/plain');
        // Tampilkan hasil langsung tanpa menyimpan ke file
        return $response;

        

    }

    // Tutup sesi cURL
    curl_close($ch);
}

function regexSearch($regex,$mpd)
{
	$text = $mpd;
	$search = $regex;

	preg_match($search, $text, $matches);

	// Menampilkan hasil
		// var_dump ($matches);
	if (!empty($matches[0])) {
		// die();
	    return $matches[0];
	} else {
	    return $search. ' Regex Not Found';
	}
}


?>