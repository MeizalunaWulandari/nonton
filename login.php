<?php 
$title = 'Login';
require_once 'templates/header.php';
require_once 'lib/islogin.php';
?>

<main class="container mx-auto px-4 mt-10 py-6 flex items-center justify-center ">
    <div class="w-full max-w-md p-6 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 min-h-[350px] flex flex-col justify-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 text-center">Login</h2>
        <?php
            // Ambil parameter dari URL dan sanitasi
            $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : null;
            $error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : null;
            ?>

            <div style="height: 24px;"> <!-- Tinggi tetap -->
                <?php if ($message): ?>
                    <div class="text-green-500 text-center">
                        <?php echo $message; ?>
                    </div>
                <?php elseif ($error): ?>
                    <div class="text-red-500 text-center">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
            </div>


        <form method="POST" action="/lib/login.php" class="flex-1 flex flex-col justify-center">
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-400 mb-2" for="username">Username:</label>
                <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                       type="text" id="username" name="username" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-400 mb-2" for="password">Password:</label>
                <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                       type="password" id="password" name="password" required>
            </div>
            <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" type="submit">
                Login
            </button>
        </form>

        <!-- Tambahkan link Forgot Password dan Register di sini -->
        <div class="mt-6 text-right">
            <a href="/register.php" class="text-blue-600 hover:underline dark:text-blue-500">Register</a>
            <span class="">|</span>
            <!-- <a href="forgot-password.php" class="text-blue-600 hover:underline dark:text-blue-500">Forgot password</a> -->
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>
