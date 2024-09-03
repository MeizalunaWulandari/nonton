<?php 
$title = 'Register';
require_once 'templates/header.php';
require_once 'lib/islogin.php';
?>

<main class="container mx-auto px-4 py-6 flex items-center justify-center ">
    <div class="w-full max-w-lg p-6 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center">Register</h2>
        <?php $error = htmlspecialchars($_GET['error']) ?>        
        <div class="mb-4 text-red-500 text-center" style="height: 24px;"> <!-- Area error dengan tinggi tetap -->
            <?php if (isset($error) && !empty($error)) { ?>
                <?php echo $error; ?>
            <?php } ?>
        </div>

        <form method="POST" action="/lib/register.php">
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-400 mb-2" for="fulname">Full Name:</label>
                <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                       type="text" id="fulname" name="fullname" placeholder="Full Name" required autofocus>
            </div>

            <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-gray-700 dark:text-gray-400 mb-2" for="username">Username:</label>
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                           type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-400 mb-2" for="email">Email:</label>
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                           type="email" id="email" name="email" placeholder="example@gmail.com" required>
                </div>
            </div>

            <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-gray-700 dark:text-gray-400 mb-2" for="password">Password:</label>
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                           type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-400 mb-2" for="confirm_password">Confirm Password:</label>
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                           type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                </div>
            </div>

            <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" type="submit">
                Register
            </button>
        </form>

        <div class="mt-4 text-right">
            <a href="/login.php" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600">Login</a> | 
            <!-- <a href="forgot-password.php" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600">Forgot password</a> -->
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>
