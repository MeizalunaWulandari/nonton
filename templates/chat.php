<?php
// Get the current URL path
$url_path = $_SERVER['REQUEST_URI'];

// Extract the channel from the URL
// Assuming URL structure is /{channel}/index.php
$path_parts = explode('/', trim($url_path, '/'));
$channel = $path_parts[count($path_parts) - 2]; // Get the second last part

?>

<!-- Chat Section -->
<div class="col-span-12 md:col-span-3 bg-gray-800 p-2 rounded-md">
    <h2 class="text-xl font-bold mb-2">Live Chat</h2>
    <div class="h-96 bg-gray-700 rounded-md p-2 overflow-y-auto flex flex-col-reverse" id="chat-box">
        <!-- Example Chat Content -->
        <!-- Messages will be dynamically added here -->
    </div>
    <div class="mt-2 flex">
        <input type="hidden" value="<?= $user_id ?? '' ?>" name="" id="username">
        <input type="hidden" value="<?php echo htmlspecialchars($channel); ?>" name="channel_id" id="channel_id">
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['username'])): ?>
            <input type="text" id="message-input" class="flex-1 p-2 bg-gray-600 rounded-l-md" placeholder="Type your message...">
            <button id="send-button" class="p-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600">Send</button>
        <?php endif ?>
    </div>
</div>
