<?php 
    require_once __DIR__ . '/../lib/config.php';
    require_once __DIR__ . '/../lib/session.php';
    if ($_SERVER['PHP_SELF'] == '/login.php') {
        $active = '';
    }
    
    // Fungsi untuk memulai sesi pengguna jika token valid
    function checkAndStartSessionFromToken() {
        // Periksa jika sesi sudah ada
        if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
            return; // Sesi sudah ada, tidak perlu tindakan lebih lanjut
        }

        // Jika sesi tidak ada, periksa token di cookie
        if (isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];

            try {
                $pdo = getDbConnection();

                // Verifikasi token di database
                $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token = :token");
                $stmt->execute(['token' => $token]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    // Token valid, mulai sesi
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['color'] = $user['color'];

                    $user_id = $_SESSION['user_id'];
                    $color = $_SESSION['color'];
                    // Anda dapat mengatur lebih banyak data sesi jika diperlukan
                }

            } catch (Exception $e) {
                // Tangani kesalahan koneksi atau query jika perlu
                error_log("An error occurred: " . $e->getMessage());
            }
        }
    }

    // Panggil fungsi untuk memeriksa dan memulai sesi dari token
    checkAndStartSessionFromToken();

    // Halaman dapat diteruskan di bawah ini

?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="no-referrer" />
    <meta name="keywords" content="MicinProject, Micin Project, micinproject.my.id, bandung.my.id, nonton micinproject, naver, Naver MicinProject">
    <meta name="description" content="MicinProject">
    <meta property="og:title" content="Micin Project | <?= $title ?>">
    <meta property="og:description" content="MicinProject">
    <meta property="og:image" content="<?= $thumb ?? '/logo.svg' ?>">
    <meta property="og:url" content="https://stream.micinproject.de">
    <meta property="og:type" content="website">
    <link rel="canonical" href="https://stream.micinproject.de">
    <title>Micin Project | <?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@1.6.5/dist/flowbite.min.css" rel="stylesheet">
    <link href="/style.css" rel="stylesheet">

        <script>
        // Jika ada pesan, tampilkan alert
        window.onload = function() {
            <?php if ($message): ?>
                alert("<?php echo addslashes($message); ?>");
            <?php endif; ?>
        };
    </script>

</head>
<body class="bg-gray-50 dark:bg-gray-900 dark:border-gray-800 text-white">

<nav class="border-gray-200 bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-2">
    <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Micin Project</span>
    </a>
    <button data-collapse-toggle="navbar-solid-bg" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-solid-bg" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
    <div class="hidden w-full md:block md:w-auto" id="navbar-solid-bg">
      <ul class="flex flex-col font-medium mt-4 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-transparent dark:bg-gray-800 md:dark:bg-transparent dark:border-gray-700">
        <li>
          <a href="/" class="block py-2 px-3 md:p-0 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:dark:text-red-500 dark:bg-blue-600 md:dark:bg-transparent" aria-current="page">Home</a>
        </li>
        <li>
          <a href="/player.php" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent" aria-current="page">Video Player</a>
        </li>
        <li>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 1): ?>
            <!-- Jika pengguna sudah login, tampilkan link Logout -->
            <a href="/addchannel.php" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Custom Channel</a>
        <?php endif ?>
        </li>
        <li>
          <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Jika pengguna sudah login, tampilkan link Logout -->
            <a href="/logout.php" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Logout</a>
        <?php else: ?>
            <!-- Jika pengguna belum login, tampilkan link Login -->
            <a href="/login.php" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Login</a>
        <?php endif ?>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- NAVBAR END -->

