<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIDIO</title>
    <link href="/style.css" rel="stylesheet">
    <script src="//ssl.p.jwpcdn.com/player/v/8.21.0/jwplayer.js"></script>
    <style>
        /* Gaya untuk kotak JSON dengan scrolling */
        #jsonContainer {
            display: none; /* Default tersembunyi, akan ditampilkan setelah respons diterima */
            background-color: #f4f4f4;
            padding: 8px;
            border: 1px solid #ddd;
            overflow-y: auto; /* Mengaktifkan scrolling vertikal */
            margin-bottom: 10px; /* Spasi bawah */
        }
        
        /* Gaya untuk tombol "Copy" */
        #copyButton {
            display: none; /* Default tersembunyi, akan ditampilkan setelah respons diterima */
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            margin-bottom: 10px; /* Spasi bawah */
        }
    </style>
    <script>
        jwplayer.key = 'XSuP4qMl+9tK17QNb+4+th2Pm9AWgMO/cYH8CI0HGGr7bdjo';

        function setupPlayer(vidio_id, proxy, submitButton) {
            let countdown = 5;

            // Ubah teks tombol submit menjadi "Loading 5" dan lakukan hitungan mundur
            submitButton.textContent = `Loading ${countdown}`;
            submitButton.disabled = true;
            const intervalId = setInterval(() => {
                countdown--;
                submitButton.textContent = `Loading ${countdown}`;

                if (countdown <= 0) {
                    clearInterval(intervalId);
                }
            }, 1000);

            fetch('https://nonton.micinproject.my.id/vidio.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ vidio_id: vidio_id, proxy: proxy })
            })
            .then(response => response.json())
            .then(data => {
                clearInterval(intervalId);
                if (data.http_code === 200) {
                    const manifestUrl = data.data.proxied_url;
                    const keyId = data.data.kid;
                    const key = data.data.key;

                    // Menyusun pemutar video dengan konfigurasi dari API
                    jwplayer("player").setup({
                        playlist: [{
                            file: manifestUrl,
                            drm: {
                                clearkey: {
                                    keyId: keyId,
                                    key: key
                                }
                            }
                        }],
                        width: "100%",
                        autostart: true,
                        aspectratio: "16:9",
                        logo: {
                            file: "/logo.svg",
                            position: "top-right",
                            hide: false
                        },
                        events: {
                            onReady: function() {
                                submitButton.textContent = "Load Video";
                                submitButton.disabled = false;
                            }
                        }
                    }).on('ready', function() {
                        submitButton.textContent = "Load Video";
                        submitButton.disabled = false;
                    });

                    // Menampilkan JSON respons dalam format yang terformat
                    const prettyJson = JSON.stringify(data, null, 4); // Indentasi 4 spasi
                    const jsonContainer = document.getElementById('jsonContainer');
                    document.getElementById('jsonResponse').textContent = prettyJson;

                    // Tampilkan kotak JSON dan tombol "Copy"
                    jsonContainer.style.display = "block";
                    document.getElementById('copyButton').style.display = "inline-block";
                } else {
                    console.error('API Error:', data);
                    submitButton.textContent = "Load Video";
                    submitButton.disabled = false;
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                submitButton.textContent = "Load Video";
                submitButton.disabled = false;
            });
        }

        // Fungsi untuk menyalin JSON ke clipboard
        function copyJson() {
            const jsonText = document.getElementById('jsonResponse').textContent;
            navigator.clipboard.writeText(jsonText)
            .then(() => {
                alert("JSON copied to clipboard!");
            })
            .catch(err => {
                console.error("Failed to copy: ", err);
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('videoForm');

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const vidio_id = document.getElementById('vidio_id').value;
                const proxy = document.getElementById('proxy').value;
                const submitButton = document.getElementById('submitButton');
                setupPlayer(vidio_id, proxy, submitButton);
            });

            // Menambahkan event listener untuk tombol "Copy"
            document.getElementById('copyButton').addEventListener('click', copyJson);
        });
    </script>
</head>
<body>
    <h3 style="color: red; display: inline;">Gunakan proxy anda sendiri untuk menghindari lag</h3>

    <form id="videoForm">
        <label for="vidio_id">Vidio ID:</label>
        <input type="text" id="vidio_id" name="vidio_id" required>
        <br>
        <label for="proxy">Proxy (optional):</label>
        <input type="url" id="proxy" name="proxy">
        <br>
        <button type="submit" id="submitButton">Load Video</button>
    </form>

    <div id="player"></div>
    <br>
    <button id="copyButton">Copy</button>

    <!-- Kotak JSON dengan scrolling -->
    <div id="jsonContainer">
        <pre id="jsonResponse"></pre>
    </div>
    <!-- Tombol "Copy" -->
</body>
</html>
