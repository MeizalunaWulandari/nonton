document.addEventListener('DOMContentLoaded', () => {
    // Ambil parameter 'user' dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const user = urlParams.get('user');

    if (user) {
        // Kirim request ke endpoint API untuk mendapatkan URL HLS
        fetch(`https://chaturbate.com/get_edge_hls_url_ajax/?user=${encodeURIComponent(user)}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'authority': 'chaturbate.com',
                'accept': '*/*',
                'accept-language': 'en-US,en;q=0.9',
                'cache-control': 'no-cache',
                'origin': 'https://chaturbate.com',
                'pragma': 'no-cache',
                'sec-ch-ua': '" Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
                'sec-ch-ua-mobile': '?0',
                'sec-ch-ua-platform': '"Linux"',
                'sec-fetch-dest': 'empty',
                'sec-fetch-mode': 'cors',
                'sec-fetch-site': 'same-origin',
                'user-agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36',
                'x-requested-with': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                'room_slug': user,
                'bandwidth': 'high'
            }).toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const hlsUrl = data.url;
                const playerElement = document.getElementById('player');
                
                // Inisialisasi JW Player dengan URL HLS
                jwplayer(playerElement).setup({
                    playlist: [{
                        title: user,
                        sources: [{
                            default: false,
                            type: "hls",
                            file: hlsUrl
                        }]
                    }],
                    width: "100%",
                    height: "100%",
                    aspectratio: "16:9",
                    autostart: true,
                    logo: {
                        file: '',
                        link: '',
                        position: 'top-right'
                    },
                    cast: {},
                    sharing: {}
                });
            } else {
                console.error('Failed to get HLS URL:', data);
            }
        })
        .catch(error => console.error('Error fetching HLS URL:', error));
    } else {
        console.error('No user parameter provided.');
    }
});
