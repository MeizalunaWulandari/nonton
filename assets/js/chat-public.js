document.addEventListener('DOMContentLoaded', function() {
    const chatBox = document.getElementById('chat-box');

    // Ambil channel name dari URL
    const currentUrl = window.location.pathname;
    const urlParts = currentUrl.split('/');
    const channelName = urlParts[1] || 'default_channel';

    // Function to load old messages
    function loadMessages() {
        fetch(`/lib/fetch_messages.php?channel=${channelName}`)
            .then(response => response.json())
            .then(messages => {
                // Hapus pesan lama agar tidak duplikat
                chatBox.innerHTML = '';

                messages.forEach(message => {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('mb-2');
                    messageElement.innerHTML = `<p class="text-sm font-light"><strong class="${message.color}">${message.username}: </strong>${message.message}</p>`;
                    chatBox.appendChild(messageElement); // Tambah pesan ke dalam chatBox
                });

                // Scroll otomatis ke bawah setelah memuat pesan
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(error => {
                console.error('Error loading messages:', error);
            });
    }

    // Load messages when page loads
    loadMessages();

    // Optionally, set up polling to fetch new messages periodically
  //  setInterval(loadMessages, 5000); // Ambil pesan setiap 5 detik
});
