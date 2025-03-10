<?php
// Get the current page from URL or set default to 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Define the number of items per page
$limit = 24;

// Calculate the offset
$offset = ($page - 1) * $limit;

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, 'https://chaturbate.com/api/ts/roomlist/room-list/?limit=' . $limit . '&offset=' . $offset);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: chaturbate.com',
    'accept: */*',
    'accept-language: en-US,en;q=0.9',
    'cache-control: no-cache',
    'pragma: no-cache',
    'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Linux"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36',
]);

// Execute the cURL request
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
}

// Close cURL session
curl_close($ch);

// Decode JSON response
$data = json_decode($response, true);

// Check for JSON decode errors
if (json_last_error() !== JSON_ERROR_NONE) {
    echo 'JSON error: ' . json_last_error_msg();
}

// Ensure 'rooms' key exists
if (!isset($data['rooms'])) {
    echo 'No rooms data found.';
    $rooms = [];
} else {
    $rooms = $data['rooms'];
}

function removeHashtags($subject) {
    // Remove all HTML tags
    $text = strip_tags($subject);

    // Split the text into words
    $words = explode(' ', $text);
    
    // Filter out words that start with '#'
    $filteredWords = array_filter($words, function($word) {
        return substr($word, 0, 1) !== '#';
    });
    
    // Join the filtered words back into a string
    return implode(' ', $filteredWords);
}
    $title = "Chaturbate";
    require_once '../templates/header.php';

?>

	  <main class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

            <?php foreach ($rooms as $room): ?>
            <a href="live.php?user=<?php echo $room['username']; ?>" class="block w-full p-2 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <img src="<?php echo htmlspecialchars($room['img']); ?>" alt="Image for <?php echo htmlspecialchars($room['username']); ?>" class="w-full h-auto mb-4 rounded-lg">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?php echo htmlspecialchars($room['username']); ?></h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400"><?php echo removeHashtags($room['subject']); ?></p>
        </a>
    <?php endforeach; ?>

        <!-- Tambahkan lebih banyak card di sini jika diperlukan -->
    </div>


<div class="flex justify-center mt-6">
    <nav aria-label="Page navigation">
        <ul class="inline-flex items-center space-x-1">
            <!-- Tombol Sebelumnya -->
            <?php if ($page > 1): ?>
                <li>
                    <a href="?page=<?php echo $page - 1; ?>" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700" aria-label="Previous Page">← Previous</a>
                </li>
            <?php else: ?>
                <li>
                    <span class="px-3 py-2 text-sm font-medium text-gray-900 bg-gray-200 border border-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">← Previous</span>
                </li>
            <?php endif; ?>

            <!-- Halaman Saat Ini -->
            <li>
                <span class="px-3 py-2 text-sm font-medium text-gray-900 bg-blue-500 border border-blue-500 rounded-lg dark:bg-blue-600 dark:border-blue-600 dark:text-white">
                    <?php echo $page; ?>
                </span>
            </li>

            <!-- Tombol Selanjutnya -->
            <li>
                <a href="?page=<?php echo $page + 1; ?>" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700" aria-label="Next Page">Next →</a>
            </li>
        </ul>
    </nav>
</div>


</main>


<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>