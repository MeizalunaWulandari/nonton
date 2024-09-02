<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fingerprint and Hash Display</title>
    <style>
        #fingerprint, #hash, #console {
            white-space: pre-wrap; /* Preserve white space formatting */
            font-family: monospace; /* Use a monospace font */
            background-color: #f4f4f4; /* Light grey background */
            padding: 10px;
            border: 1px solid #ddd;
            margin: 10px 0;
            max-height: 300px; /* Limit the height */
            overflow-y: auto; /* Add scrollbar if needed */
        }
    </style>
</head>
<body>
    <h1>Fingerprint and Hash Data</h1>
    <h2>Fingerprint Data</h2>
    <pre id="fingerprint"></pre>
    <h2>Permanent Data Hash</h2>
    <pre id="hash"></pre>
    <h2>Console Output</h2>
    <pre id="console"></pre>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', async () => {
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

                // Update the fingerprint data in the HTML
                document.getElementById('fingerprint').textContent = JSON.stringify(fingerprint, null, 2);
                return fingerprint;
            }

            // Function to generate SHA-256 hash of a string
            async function sha256(message) {
                const encoder = new TextEncoder();
                const data = encoder.encode(message);
                const hashBuffer = await crypto.subtle.digest('SHA-256', data);
                const hashArray = Array.from(new Uint8Array(hashBuffer));
                const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
                return hashHex;
            }

            // Function to get permanent data and create its hash
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

                // Combine the permanent data into one string
                const combinedData = permanentData.map(item => `${item.key}:${JSON.stringify(item.value)}`).join('|');

                // Create hash from combined data
                const hash = await sha256(combinedData);

                // Update the hash data in the HTML
                document.getElementById('hash').textContent = hash;
                return hash;
            }

            // Run both functions to display data
            await getFingerprint();
            await getPermanentDataHash();
        });
    </script>
</body>
</html>
