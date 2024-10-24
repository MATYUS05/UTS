<?php
require_once '../config/database.php'; // Koneksi ke database

$error = ""; // Variable to hold error messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari user berdasarkan username
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Memverifikasi password yang diinput dengan hash di database
    if ($user && password_verify($password, $user['password'])) {
        // Jika password benar, login berhasil
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect ke halaman sesuai peran
        if ($user['role'] === 'admin') {
            header('Location: Admin/admin_dashboard.php');
        } else {
            // Redirect for regular users to user dashboard
            header('Location: user/user_events_dashboard.php'); 
        }
        exit;
    } else {
        // Jika username atau password salah, set error message
        $error = 'Invalid username or password';
    }
}
?>


    <!DOCTYPE html>
    <html lang="en">    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Screen</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
        <div class="bg-white min-h-screen flex items-center justify-center">
            <div class="bg-gray-100 flex rounded-2xl hover:shadow-blue-300 shadow-xl max-w-3xl p-5">

                <div class="sm:w-1/2 px-16">
                    <h2 class="font-bold text-2xl text-blue-600">Sign In</h2>
                    <p class="text-xs mt-2">Jika anda sudah punya akun, tinggal langsung login</p>

                    <form action="../user/user_events_dashboard.php" method="POST" class="flex flex-col gap-4">
                        <input class="p-2 mt-6 rounded-xl border" type="text" name="username" placeholder="Username" required>
                        <input class="p-2 rounded-xl border" type="password" name="password" placeholder="Password" required>
                        
                        <!-- Warning message for invalid login -->
                        <?php if ($error): ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo htmlspecialchars($error); ?></p>
                        <?php endif; ?>
                        
                        <button type="submit" class="bg-blue-600 hover:bg-blue-300 rounded-xl text-white py-2">Login</button>
                    </form>

                    <div class="mt-10 grid grid-cols-3 items-center">
                        <hr class="border-gray-500">
                        <p class="text-center">OR</p>
                        <hr class="border-gray-500">
                    </div>

                    <p class="mt-2 text-xs border-b border-gray-400 py-2">Forgot Your Password?</p>

                    <div class="mt-2 text-xs flex items-center justify-between">
                        <p>Belum punya akun?</p>
                        <a href="signup.php">
                            <button class="py-2 px-5 bg-white border rounded-xl hover:bg-blue-600 hover:text-white transition ease-in-out delay-75">Register</button>
                        </a>
                    </div>
                </div>

                <div class="w-1/2">
                    <img class="sm:block hidden rounded-xl h-full" src="../Image/login.webp" alt="#">
                </div>
            </div>
        </div>
    </body>
    </html>
