<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeriksaan Link M3U8</title>
</head>
<body>
    <h1>Pemeriksaan Link M3U8</h1>
    <form method="post">
        <textarea name="urls" rows="10" cols="50" placeholder="Masukkan satu atau lebih URL M3U8, satu per baris..."></textarea><br>
        <input type="submit" value="Periksa Link">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $urls = explode("\n", trim($_POST["urls"]));
        echo "<h2>Hasil Pemeriksaan:</h2>";
        echo "<ul>";
        foreach ($urls as $url) {
            $url = trim($url);
            if (!empty($url)) {
                $headers = @get_headers($url);
                if ($headers && strpos($headers[0], '200') !== false) {
                    // Memeriksa header Access-Control-Allow-Origin
                    $cors = false;
                    foreach ($headers as $header) {
                        if (stripos($header, 'Access-Control-Allow-Origin') !== false) {
                            $cors = true;
                            break;
                        }
                    }
                    echo "<li>$url - <strong>Link aktif: sedang live.</strong> " . ($cors ? "- CORS diizinkan." : "- CORS tidak diizinkan.") . "</li>";
                } else {
                    echo "<li>$url - <strong>Link tidak aktif.</strong></li>";
                }
            }
        }
        echo "</ul>";
    }
    ?>
</body>
</html>
