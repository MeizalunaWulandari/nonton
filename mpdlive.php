<pre><?php

$id=$_GET['id'];
$data=curlget("https://www.vidio.com/drm/v1/cdata?live_id=$id&is_preview=false");
echo htmlspecialchars($data);
$mpd=antara($data,'"drm_stream_dash_url":"','"');
$wv='https://license.vidio.com/ri/licenseManager.do?pallycon-customdata-v2='.antara($data,'"widevine":"','"');
echo "\n======================\n";
$data2=curlget($mpd);
//echo htmlspecialchars($data2);
$belah=explode('<cenc:pssh>',$data2);
$pssh='AAA'.antara($belah[2],'AAA','</cenc:pssh>');

echo $mpd;
echo "\n======================\n";
echo $pssh;
echo "\n======================\n";
echo $wv;
echo "\n======================\n";
$datakey=curlpost('https://keysdb.net/wv','{"license_url":"'.$wv.'","headers":"","pssh":"'.$pssh.'","buildInfo":"","proxy":"","force":false}');

if (ada($datakey,'Cached Key')){$key=antara($datakey,"<li style=\"font-family: 'Courier'\">Key: ",'</li>');}
else {$key=antara($datakey,"<li style=\"font-family: 'Courier'\">",'</li>');}
//<li style="font-family: 'Courier'">
echo "KEY: $key\n";
$b64=base64_encode($key);
$link=$mpd."&ck=".$b64;
echo "<a href=$link>$link</a> <br>";
$shaka = "https://shaka-player-demo.appspot.com/demo/#audiolang=id-ID;textlang=id-ID;uilang=id-ID;asset=".$mpd.";license=".$wv.";panel=CUSTOM%20CONTENT;build=uncompiled";
echo "<a href=$shaka>$shaka</a> <br>";


function curlget($url){
    $timestamp = time();
    $userAgent = 'Mozilla/5.0 ' . $timestamp;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//curl_setopt($ch, CURLOPT_NOBODY, true);
$headers = [
'accept: */*',
'accept-language: en-US,en;q=0.8',
'baggage: sentry-environment=production,sentry-release=fed9242121975ff9edd161252ba8e4a18045f00a,sentry-public_key=2289b56bd44c4069b1eb457dbcc9c6c9,sentry-trace_id=fa7d814c4dcb42ba9c11f66d39bc45ba',
'cookie: ahoy_visitor=658f3050-6087-43da-ac94-bdb1f7633337; _pbjs_userid_consent_data=3524755945110770; country_id=ID; luws=08c96b0e478367654da646ad_101622646; shva=1; remember_user_token=eyJfcmFpbHMiOnsibWVzc2FnZSI6Ilcxc3hNVFl4TmpVeE1EZGRMQ0lrTW1Fa01UQWtMbkJPWjFaa1owRlJkSG91ZG5oVVVtRk9kREJSTGlJc0lqRTNNVGcxT1Rrd05qZ3VNVFkwTVRJM09DSmQiLCJleHAiOiIyMDI2LTA2LTE3VDA0OjM3OjQ4LjE2NFoiLCJwdXIiOiJjb29raWUucmVtZW1iZXJfdXNlcl90b2tlbiJ9fQ%3D%3D--3cf4e6a04c524665a469232c248ccfce9d093213; _vidio=true; plenty_id=116165107; access_token=eyJhbGciOiJIUzI1NiJ9.eyJkYXRhIjp7InR5cGUiOiJhY2Nlc3NfdG9rZW4iLCJ1aWQiOjExNjE2NTEwN30sImV4cCI6MTcxODY4NTQ2OH0.7PCkayfvDBOAiSdHR26dHPystdMFvdAs_gmbPZtTuJ8; ahoy_visit=40a113c6-426d-4545-bf65-d221c6c28927; _vidio_session=MGFobTBvM0czc1ROYjJibDFCYTNBV09pSjdvQ0gvWVZ1Wk51U3JwcVdiNERacWNUbzlDcDh5cGx0Z1hURHJLTFd1VzkvZ0srN3ROQndOL1huYU12QjVQRUl3U3ZrWHVPbkdHWTd2VktIQVR0Q20rVVRJVXpDSFNrVWE3WUZmbjBzVjBiRUgvcUhxd1g0dlJYQkF3Y2s3d05KNmFzZUlrN1NCYk5FeHhBdlpVeDhzY0hPVlhvKzZRaEF4K2tCZEdNZXdYY2l5NkRrc1p3VmtxSS8yb2F6ZHhVZjE2aUIzcHZ2L05wV2F0U0xQV0p6V0svRjFOVHM5cEhsNDV3NVhXVzZQTVNiMVJWN2lReXQ0QnQwNndyaEhUWko2WXRSWG9YbG9JMkFQZjdLVGZjbk5GaCtueDd6U0RHaGJiOGRnZnpkd240eW1PUTFFWDFRZFF2TEZTMXE5ZkEyR0tDY29CUUVmT3dFZjhhNERWdncrOHdLM2xta3lObGJBdDRIZjZudmhTSTV6MHNtRnpJWG1YY1dkRTV6Ky9FUSttUnpGL2dmZlcwRC9HY3I1TjdrQ0pZaUVjZmU0L25aWDJJMXgwQm1Fckl2RUsrK3I4djlEaElTLzlKUkJxQzZqcENROUhqSTJtdkY1eGg3WExHQnBOUTJPSHBJUWg4clgyODA3SzZBckViaFZHM1lTelNoV1VEcm1mVHcyU1hKellINlc5RnI0bzBFcGJpY3B1cU9xMmpRRVluMkF1VzRnYkpIKzRhL1ZFZVNFMnE3MlAvNkp4dWFYMlVSTUtnU0Mvc0VqRTErRmxlUnIwaHJ4RmIrQWI4UTBxYXlOZnFiVzdkTHpTb1Rxai9VRDNGcnloWVFuZDlhT1NHUlFXWUwrdVpCUnBhd2lucFVGWUhCUTBLUjN2ZzRNSGxacURva0hUZ3F1OEstLXdGK09SUnJqbThiaVliSUExWjROMFE9PQ%3D%3D--f7a190b92cc6b2c6aeac641b5335af8df418bc6d',
'priority: u=1, i',
'referer: https://www.vidio.com/watch/322534-ep-07-cinta-tanpa-batas',
'sec-ch-ua: "Not/A)Brand";v="8", "Chromium";v="126", "Brave";v="126"',
'sec-ch-ua-mobile: ?0',
'sec-ch-ua-platform: "Windows"',
'sec-fetch-dest: empty',
'sec-fetch-mode: cors',
'sec-fetch-site: same-origin',
'sec-gpc: 1',
'sentry-trace: fa7d814c4dcb42ba9c11f66d39bc45ba-8e21c71bba05168a-0',
 'user-agent: ' . $userAgent
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
$data=curl_exec($ch);
curl_close($ch);
return $data;
}

function antara($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
function curlpost($url,$var){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $var);
$headers = [
'accept: application/json, text/plain, */*',
'accept-language: en-US,en;q=0.9',
'content-type: application/json',
'cookie: api_key=cdb2926a8f2245ab1be273b0f9b8e47a7184eb0f4aa33e97f6ba16ff77a266c9; remember_token=826472546001354783|419f44d15edbfb8ef751a1cc7435e240501307dd8316483af12abd03f76fa9ac1b71033cabd9cbfd6dfad22d1495de7df55644dcd2eb2e642a66a37db951ed8a; session=.eJwlzj0OwjAMQOG7ZGaIf2I7vUwVx7FgbemEuDuVWL_h6X3Knsc6n2V7H9d6lP0VZSsAyg4gzj1Sw1IwmRBJpuLAQeEm1TxijDbNaKmEoWSNTl0GTevVCPoS4tnBeYpPh4hbGYDMVr0pESxnz9EwnUMEHURFyz1ynev439xhVmwstQI1VqPy_QHBbTIy.Zm_Flg.18wJDZ3Ia8Lj_KjUdKy8qaCx-1A',
'origin: https://keysdb.net',
'priority: u=1, i',
'referer: https://keysdb.net/',
'sec-ch-ua: "Not/A)Brand";v="8", "Chromium";v="126", "Brave";v="126"',
'sec-ch-ua-mobile: ?0',
'sec-ch-ua-platform: "Windows"',
'sec-fetch-dest: empty',
'sec-fetch-mode: cors',
'sec-fetch-site: same-origin',
'sec-gpc: 1',
'x-api-key: cdb2926a8f2245ab1be273b0f9b8e47a7184eb0f4aa33e97f6ba16ff77a266c9'
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);
$data=curl_exec($ch);
curl_close($ch);
return $data;
}

function ada($haystack, $needle) {
return $needle !== '' && mb_strpos($haystack, $needle) !== false;
}
