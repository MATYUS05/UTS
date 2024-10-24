<?php
require_once '../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        // Debug log untuk memeriksa nilai email dan status pengguna
        error_log('Email: ' . $email);
        error_log('User found: ' . ($user ? 'Yes' : 'No'));

        if ($user && password_verify($password, $user['password'])) {
            // Set session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Debug log untuk memeriksa peran pengguna
            error_log('Redirecting to dashboard for user role: ' . $user['role']);
            
            // Redirect berdasarkan role
            if ($user['role'] === 'admin') {
                header('Location: ../Admin/admin_dashboard.php');
            } else {
                header('Location: ../user/user_events_dashboard.php');
            }
            exit();
        } else {
            $_SESSION['error'] = 'Email atau password salah!';
            header('Location: login.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Terjadi kesalahan: ' . $e->getMessage();
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="bg-white min-h-screen flex items-center justify-center">
        <div class="bg-gray-100 flex rounded-2xl hover:shadow-blue-300 shadow-xl max-w-3xl p-5">
            <!-- Form -->
            <div class="sm:w-1/2 px-16">
                <h2 class="font-bold text-2xl text-blue-600">Login</h2>
                <p class="text-xs mt-2">Silakan login untuk melanjutkan</p>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4">
                        <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4">
                        <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="flex flex-col gap-4">
                    <input class="p-2 mt-6 rounded-xl border" name="email" type="email" placeholder="Email" required>
                    <input class="p-2 rounded-xl border" name="password" type="password" placeholder="Password" required>
                    <button class="bg-blue-600 hover:bg-blue-300 rounded-xl text-white py-2">Login</button>
                </form>
                
                <div class="mt-10 grid grid-cols-3 items-center">
                    <hr class="border-gray-500">
                    <p class="text-center">ATAU</p>
                    <hr class="border-gray-500">
                </div>
                
                <p class="mt-5 text-xs border-b border-gray-400 py-2">Belum punya akun?</p>
                
                <div class="mt-1 text-xs flex items-center justify-between">
                    <p>Daftar sekarang</p>
                    <a href="signup.php">
                        <button class="py-2 px-5 bg-white border rounded-xl hover:bg-blue-600 hover:text-white transition ease-in-out delay-75">Daftar</button>
                    </a>
                </div>
            </div>
            
            <!-- Gambar -->
            <div class="w-1/2">
                <img class="sm:block hidden rounded-xl h-full" src="../Image/login.webp" alt="Gambar Login">
            </div>
        </div>
    </div>
</body>
</html>
