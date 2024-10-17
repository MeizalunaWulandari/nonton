async function getFingerprint() {
    const fingerprint = {};

    // Basic fingerprinting information
    fingerprint.user_agent = navigator.userAgent;
    fingerprint.language = navigator.language;
    fingerprint.pixel_ratio = window.devicePixelRatio;
    fingerprint.hardware_concurrency = navigator.hardwareConcurrency;
    fingerprint.resolution = [screen.width, screen.height];
    fingerprint.available_resolution = [screen.availWidth, screen.availHeight];
    fingerprint.timezone_offset = new Date().getTimezoneOffset();
    fingerprint.session_storage = !!window.sessionStorage;
    fingerprint.local_storage = !!window.localStorage;
    fingerprint.indexed_db = !!window.indexedDB;
    fingerprint.open_database = !!window.openDatabase;
    fingerprint.navigator_platform = navigator.platform;
    fingerprint.navigator_oscpu = navigator.oscpu || 'unknown';
    fingerprint.do_not_track = navigator.doNotTrack;
    fingerprint.touch_support = navigator.maxTouchPoints;
    fingerprint.cookie_enabled = navigator.cookieEnabled;

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
    fingerprint.fonts = detectedFonts.join(',');

    if (window.DeviceOrientationEvent) {
        await new Promise(resolve => {
            window.addEventListener('deviceorientation', (event) => {
                fingerprint.device_orientation = `${event.alpha || 0},${event.beta || 0},${event.gamma || 0}`;
                resolve();
            }, { once: true });
        });
    }

    if (navigator.connection) {
        fingerprint.connection_type = navigator.connection.effectiveType;
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
        fingerprint.public_ip = publicIP;
    } catch (error) {
        console.error("Error retrieving IP address:", error);
    }

    if (window.performance) {
        const timing = window.performance.timing;
        fingerprint.navigation_start = timing.navigationStart;
        fingerprint.dom_complete = timing.domComplete;
    }

    const cssFeatures = {
        'flexbox': window.getComputedStyle(document.documentElement).display === 'flex',
        'grid': window.getComputedStyle(document.documentElement).display === 'grid'
    };
    fingerprint.css_features = JSON.stringify(cssFeatures);

    function getWebGLFingerprint() {
        const canvas = document.createElement('canvas');
        const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
        if (!gl) return 'WebGL not supported';
        const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
        return debugInfo ? gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL) + " " + gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL) : 'WebGL info not available';
    }
    fingerprint.webgl = getWebGLFingerprint();

    // Check P2P support
    async function checkP2PSupport() {
        return new Promise((resolve) => {
            const rtc = new RTCPeerConnection({ iceServers: [{ urls: 'stun:stun.l.google.com:19302' }] });
            rtc.createDataChannel('');
            rtc.onicecandidate = (event) => {
                if (event.candidate) {
                    resolve("direct");
                } else {
                    resolve("turn_need");
                }
            };
            rtc.createOffer().then((offer) => rtc.setLocalDescription(offer));
        });
    }

    try {
        const p2pSupport = await checkP2PSupport();
        fingerprint.p2p_support = p2pSupport;
    } catch (error) {
        console.error("Error checking P2P support:", error);
    }

    console.log("Comprehensive Fingerprint:", fingerprint);
    return fingerprint;
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
    return hash;
}

// Fungsi untuk mengirim data ke backend
async function sendDataToBackend() {
    const fingerprint = await getFingerprint();
    const hash = await getPermanentDataHash();

    const dataToSend = {
        permanent_data_hash: hash,
        comprehensive_fingerprint: fingerprint
    };
    console.log(dataToSend);
    // Kirim data ke backend menggunakan fetch
    try {
        const response = await fetch('/lib/fingerprint.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dataToSend)
        });

        if (response.ok) {
            const responseData = await response.json();
            console.log('Response from backend:', responseData);
                if (responseData.status == "success") {
                    Swal.fire({
                        title: 'Selamat Datang!',
                        text: 'Terima kasih telah mengunjungi kami.',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Join Telegram',
                        cancelButtonText: 'Tutup',
                        preConfirm: () => {
                            // Ganti URL di bawah ini dengan link grup Telegram Anda
                            window.open('https://t.me/+BNTHvuqimcc2ODY1', '_blank');
                        }
                    });
                }

        } else {
            console.error('Error sending data to backend:', response.statusText);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Jalankan fungsi untuk mendapatkan hash data permanen
getPermanentDataHash();

// Jalankan fungsi untuk mengirim data
sendDataToBackend();