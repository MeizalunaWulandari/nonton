document.addEventListener('DOMContentLoaded', () => {
    // Fetch stream data from stream.json
    fetch('stream.json')
        .then(response => response.json())
        .then(data => {
            // Populate channel list
            const channelList = document.getElementById('channel-list');
            data.streams.forEach(stream => {
                const listItem = document.createElement('li');
                const link = document.createElement('a');
                link.href = `index.php?id=${stream.id}`;
                link.textContent = stream.name;
                link.setAttribute('data-url', stream.url);
                link.setAttribute('data-key', stream.key);
                link.setAttribute('data-kid', stream.kid);
                listItem.appendChild(link);
                channelList.appendChild(listItem);
            });

            // Get URL parameter 'id'
            const urlParams = new URLSearchParams(window.location.search);
            const channelId = urlParams.get('id');

            if (channelId) {
                // Find the stream with the specified id
                const selectedStream = data.streams.find(stream => stream.id === channelId);
                
                if (selectedStream) {
                    // Initialize JW Player with the selected stream
                    jwplayer("player").setup({
                        playlist: [{
                            title: selectedStream.name,
                            sources: [{
                                default: false,
                                type: "dash",
                                file: selectedStream.url,
                                drm: {
                                    clearkey: {
                                        keyId: selectedStream.kid,
                                        key: selectedStream.key
                                    }
                                }
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

                    // Set active class on the selected channel
                    const links = channelList.querySelectorAll('a');
                    links.forEach(link => {
                        if (link.getAttribute('data-url') === selectedStream.url) {
                            link.classList.add('active');
                        }
                    });
                }
            } else {
                // Initialize JW Player with the first stream if no 'id' parameter is present
                jwplayer("player").setup({
                    playlist: [{
                        title: data.streams[0].name,
                        sources: [{
                            default: false,
                            type: "dash",
                            file: data.streams[0].url,
                            drm: {
                                clearkey: {
                                    keyId: data.streams[0].kid,
                                    key: data.streams[0].key
                                }
                            }
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

                // Set active class on the first channel
                const firstLink = channelList.querySelector('a');
                if (firstLink) {
                    firstLink.classList.add('active');
                }
            }
        })
        .catch(error => console.error('Error loading stream.json:', error));
});
