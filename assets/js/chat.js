document.addEventListener('DOMContentLoaded', function() {
    const sendButton = document.getElementById('send-button');
    const messageInput = document.getElementById('message-input');
    const chatBox = document.getElementById('chat-box');
    const username = document.getElementById('username').value;

    // Ambil channel name dari URL
    const currentUrl = window.location.pathname;
    const urlParts = currentUrl.split('/');
    const channelName = urlParts[1] || 'default_channel';

    // Initialize Pusher
    const pusher = new Pusher('cef39a43597912601a8b', {
        cluster: 'ap1'
    });

    const pusherChannel = pusher.subscribe(channelName);

    // Tambah pesan baru di bawah (bukan di atas)
    pusherChannel.bind('new-message', function(data) {
        const newMessage = document.createElement('div');
        newMessage.classList.add('mb-2');
        newMessage.innerHTML = `<p class="text-sm font-light"><strong class="${data.color}">${data.username}: </strong>${data.message}</p>`;
        chatBox.insertBefore(newMessage, chatBox.firstChild);  // Tambah pesan baru di atas, tetapi dalam konteks flex-col-reverse, pesan baru akan tetap di bawah
        chatBox.scrollTop = chatBox.scrollHeight; // Scroll ke bawah
    });

    // Function to load old messages
    function loadOldMessages() {
        fetch(`/lib/fetch_messages.php?channel=${channelName}`)
            .then(response => response.json())
            .then(messages => {
                messages.forEach(message => {
                    const oldMessage = document.createElement('div');
                    oldMessage.classList.add('mb-2');
                    oldMessage.innerHTML = `<p class="text-sm font-light"><strong class="${message.color}">${message.username}: </strong>${message.message}</p>`;
                    chatBox.appendChild(oldMessage); // Tambah pesan lama di bawah
                });
                chatBox.scrollTop = chatBox.scrollHeight; // Scroll ke bawah setelah memuat pesan lama
            })
            .catch(error => {
                console.error('Error loading messages:', error);
            });
    }

    // Load old messages when page loads
    loadOldMessages();

    // Function to send message
    function sendMessage() {
        const message = messageInput.value.trim();
        if (message === '') {
            alert('Please enter a message.');
            return;
        }

        fetch('/lib/send_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                username: username,
                message: message,
                channel: channelName
            })
        })
        .then(response => response.text())
        .then(text => {
            console.log('Server response:', text);
            return JSON.parse(text);
        })
        .then(data => {
            if (data.status === 'success') {
                messageInput.value = '';
                chatBox.scrollTop = chatBox.scrollHeight; // Scroll ke bawah setelah mengirim pesan
            } else {
                alert('Error sending message: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });
    }

    // Event listener for Send button
    sendButton.addEventListener('click', sendMessage);

    // Event listener for Enter key
    messageInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent default action of Enter key
            sendMessage();
        }
    });
});
