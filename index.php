<?php 
    $title = 'Home';
    require_once 'templates/header.php';

 ?>
	  <main class="container mx-auto px-4 py-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <a href="fifa/index.php" class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">FIFA</h5>
            <p class="font-normal text-gray-700 dark:text-gray-400">Gunakan chrome extension "MPD Player"</p>
            <p class="font-normal text-gray-700 dark:text-red-400">Experimental.</p>
        </a>
        <a href="naver/index.php" class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Naver</h5>
            <p class="font-normal text-gray-700 dark:text-gray-400">Konten dalam bahasa Korea, tidak disertakan penerjemah bawaan</p>
        </a>
        <a href="volleyballworld/index.php" class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Volleyball World TV</h5>
            <p class="font-normal text-gray-700 dark:text-gray-400">VPN Mungkin diperlukan</p>
        </a>
        <a href="eurosport/index.php" class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">EUROSPORT</h5>
            <p class="font-normal text-gray-700 dark:text-gray-400">EUROSPORT is under maintenance.</p>
        </a>
        <a href="areasports/index.php" class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">AREA Sports</h5>
            <p class="font-normal text-gray-700 dark:text-gray-400">Konten tidak dalam bahasa Inggris, tidak disertakan penerjemah bawaan.</p>
        </a>
        <a href="kisskh/index.php" class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Kisskh</h5>
            <p class="font-normal text-gray-700 dark:text-gray-400">Subtitle indonesia tidak selalu tersedia.</p>
        </a>
        <a href="chaturbate/index.php" class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Chaturbate</h5>
            <p class="font-normal text-gray-700 dark:text-gray-400">Login untuk menonton</p>
        </a>
        <a href="onesports/index.php" class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">One Sports</h5>
            <p class="font-normal text-gray-700 dark:text-gray-400">Konten dalam bahasa Korea, tidak disertakan penerjemah bawaan</p>
        </a>
        <!-- Tambahkan lebih banyak card di sini jika diperlukan -->
    </div>
</main>


<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

<script type="text/javascript">

// Function to get comprehensive fingerprinting information
async function getFingerprint() {
    const fingerprint = [];

    // Basic fingerprinting information
    fingerprint.push({ key: "user_agent", value: navigator.userAgent });
    fingerprint.push({ key: "language", value: navigator.language });
    fingerprint.push({ key: "pixel_ratio", value: window.devicePixelRatio });
    fingerprint.push({ key: "hardware_concurrency", value: navigator.hardwareConcurrency });
    fingerprint.push({ key: "resolution", value: [screen.width, screen.height] });
    fingerprint.push({ key: "available_resolution", value: [screen.availHeight, screen.availWidth] });
    fingerprint.push({ key: "timezone_offset", value: new Date().getTimezoneOffset() });
    fingerprint.push({ key: "session_storage", value: !!window.sessionStorage });
    fingerprint.push({ key: "local_storage", value: !!window.localStorage });
    fingerprint.push({ key: "indexed_db", value: !!window.indexedDB });
    fingerprint.push({ key: "open_database", value: !!window.openDatabase });
    fingerprint.push({ key: "navigator_platform", value: navigator.platform });
    fingerprint.push({ key: "navigator_oscpu", value: navigator.oscpu || 'unknown' });
    fingerprint.push({ key: "do_not_track", value: navigator.doNotTrack });
    fingerprint.push({ key: "touch_support", value: navigator.maxTouchPoints });
    for (let i = 0; i < navigator.plugins.length; i++) {
        fingerprint.push({ key: "navigator_plugin_" + i, value: navigator.plugins[i].name });
    }
    fingerprint.push({ key: "cookie_enabled", value: navigator.cookieEnabled });

    // Additional fingerprinting information
    const fonts = ['Arial', 'Verdana', 'Times New Roman', 'Courier New', 'Georgia'];
    const detectedFonts = fonts.map(font => {
        const testElement = document.createElement('span');
        testElement.style.fontFamily = font;
        testElement.style.visibility = 'hidden';
        document.body.appendChild(testElement);
        const fontSize = window.getComputedStyle(testElement).fontFamily;
        document.body.removeChild(testElement);
        return fontSize.includes(font) ? font : null;
    }).filter(Boolean);
    fingerprint.push({ key: "fonts", value: detectedFonts.join(',') });

    if (window.DeviceOrientationEvent) {
        await new Promise(resolve => {
            window.addEventListener('deviceorientation', (event) => {
                fingerprint.push({ key: "device_orientation", value: `${event.alpha || 0},${event.beta || 0},${event.gamma || 0}` });
                resolve();
            }, { once: true });
        });
    }

    if (navigator.connection) {
        fingerprint.push({ key: "connection_type", value: navigator.connection.effectiveType });
    }

    async function getWebRTCIPAddress() {
        return new Promise((resolve, reject) => {
            const rtc = new RTCPeerConnection({ iceServers: [{ urls: 'stun:stun.l.google.com:19302' }] });
            rtc.createDataChannel('');
            rtc.onicecandidate = (event) => {
                if (event.candidate) {
                    const ipAddress = /([0-9]{1,3}\.){3}[0-9]{1,3}/.exec(event.candidate.candidate);
                    if (ipAddress) {
                        resolve(ipAddress[0]);
                    }
                }
            };
            rtc.createOffer().then((offer) => rtc.setLocalDescription(offer));
        });
    }

    try {
        const publicIP = await getWebRTCIPAddress();
        fingerprint.push({ key: "public_ip", value: publicIP });
    } catch (error) {
        console.error("Error retrieving IP address:", error);
    }

    if (window.performance) {
        const timing = window.performance.timing;
        fingerprint.push({ key: "navigation_start", value: timing.navigationStart });
        fingerprint.push({ key: "dom_complete", value: timing.domComplete });
    }

    const cssFeatures = {
        'flexbox': window.getComputedStyle(document.documentElement).display === 'flex',
        'grid': window.getComputedStyle(document.documentElement).display === 'grid'
    };
    fingerprint.push({ key: "css_features", value: JSON.stringify(cssFeatures) });

    function getWebGLFingerprint() {
        const canvas = document.createElement('canvas');
        const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
        if (!gl) return 'WebGL not supported';
        const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
        return debugInfo ? gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL) + " " + gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL) : 'WebGL info not available';
    }
    fingerprint.push({ key: "webgl", value: getWebGLFingerprint() });

    // function getAudioFingerprint() {
    //     const context = new (window.AudioContext || window.webkitAudioContext)();
    //     const oscillator = context.createOscillator();
    //     oscillator.type = 'triangle';
    //     oscillator.frequency.setValueAtTime(10000, context.currentTime);
    //     oscillator.connect(context.destination);
    //     oscillator.start();
    //     return oscillator.type;
    // }
    // fingerprint.push({ key: "audio_fingerprint", value: getAudioFingerprint() });

    // Check P2P support
    async function checkP2PSupport() {
        return new Promise((resolve) => {
            const rtc = new RTCPeerConnection({ iceServers: [{ urls: 'stun:stun.l.google.com:19302' }] });
            rtc.createDataChannel('');
            rtc.onicecandidate = (event) => {
                if (event.candidate) {
                    // If we receive an ICE candidate, P2P can be supported
                    resolve("direct");
                } else {
                    // If no ICE candidate, P2P needs TURN server
                    resolve("turn_need");
                }
            };
            rtc.createOffer().then((offer) => rtc.setLocalDescription(offer));
        });
    }

    try {
        const p2pSupport = await checkP2PSupport();
        fingerprint.push({ key: "p2p_support", value: p2pSupport });
    } catch (error) {
        console.error("Error checking P2P support:", error);
    }

    console.log("Comprehensive Fingerprint:", fingerprint);
}

// Execute the function to collect and display all fingerprints
getFingerprint();

// Fungsi untuk menghasilkan hash SHA-256 dari string
async function sha256(message) {
    const encoder = new TextEncoder();
    const data = encoder.encode(message);
    const hashBuffer = await crypto.subtle.digest('SHA-256', data);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    return hashHex;
}

// Fungsi utama untuk mengumpulkan data permanen dan membuat hashnya
async function getPermanentDataHash() {
    const permanentData = [
        { key: "user_agent", value: navigator.userAgent },
        { key: "language", value: navigator.language },
        { key: "pixel_ratio", value: window.devicePixelRatio },
        { key: "hardware_concurrency", value: navigator.hardwareConcurrency },
        { key: "resolution", value: [screen.width, screen.height] },
        { key: "available_resolution", value: [screen.availWidth, screen.availHeight] },
        { key: "timezone_offset", value: new Date().getTimezoneOffset() },
        { key: "session_storage", value: !!window.sessionStorage },
        { key: "local_storage", value: !!window.localStorage },
        { key: "indexed_db", value: !!window.indexedDB },
        { key: "open_database", value: !!window.openDatabase },
        { key: "navigator_platform", value: navigator.platform },
        { key: "navigator_oscpu", value: navigator.oscpu || 'unknown' },
        { key: "cookie_enabled", value: navigator.cookieEnabled },
        { key: "fonts", value: ['Arial', 'Verdana', 'Times New Roman', 'Courier New', 'Georgia'].join(',') }
    ];

    // Gabungkan data permanen menjadi satu string
    const combinedData = permanentData.map(item => `${item.key}:${JSON.stringify(item.value)}`).join('|');
    
    // Buat hash dari data gabungan
    const hash = await sha256(combinedData);
    
    console.log("Permanent Data Hash:", hash);
}

// Jalankan fungsi untuk mendapatkan hash data permanen
getPermanentDataHash();

    
</script>
</body>
</html>