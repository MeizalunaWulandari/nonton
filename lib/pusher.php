<?php
require '../vendor/autoload.php'; // Pastikan composer autoload di-load

use Pusher\Pusher;
require 'loadenv.php'; // Pastikan composer autoload di-load

// Debug output
// echo "PUSHER_APP_ID: " . getenv('PUSHER_APP_ID') . "\n";
// echo "PUSHER_APP_KEY: " . getenv('PUSHER_APP_KEY') . "\n";
// echo "PUSHER_APP_SECRET: " . getenv('PUSHER_APP_SECRET') . "\n";
// echo "PUSHER_CLUSTER: " . getenv('PUSHER_CLUSTER') . "\n";

$options = [
    'cluster' => getenv('PUSHER_CLUSTER'),
    'useTLS' => true
];

$pusher = new Pusher(
    getenv('PUSHER_APP_KEY'),
    getenv('PUSHER_APP_SECRET'),
    getenv('PUSHER_APP_ID'),
    $options
);
?>
