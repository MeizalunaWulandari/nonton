<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Redirect ke halaman login dengan pesan
header("Location: /login.php?message=" . urlencode("You have been logged out."));
exit();
