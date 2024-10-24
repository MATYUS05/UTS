<?php
require_once '../config/database.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Hash password sebelum disimpan ke database
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Simpan pengguna baru ke database
    $stmt = $pdo->prepare('INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)');
    $role = 'user'; // Role default pengguna
    if ($stmt->execute([$username, $hashed_password, $email, $role])) {
        echo 'Akun berhasil dibuat! <a href="login.php">Login di sini</a>';
    } else {
        echo 'Terjadi kesalahan saat membuat akun.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Pendaftaran</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="bg-white min-h-screen flex items-center justify-center">
        <!-- Container Pendaftaran -->
        <div class="bg-gray-100 flex rounded-2xl hover:shadow-blue-300 shadow-xl max-w-3xl p-5">

            <!-- Gambar -->
            <div class="w-1/2">
                <img class="sm:block hidden rounded-xl h-full" src="../Image/login.webp" alt="Gambar Pendaftaran">
            </div>

            <!-- Form -->
            <div class="sm:w-1/2 px-16">
                <h2 class="font-bold text-2xl text-blue-600">Daftar</h2>
                <p class="text-xs mt-2">Buat akun baru untuk mengakses semua fitur yang tersedia</p>

                <form action="register.php" method="POST" class="flex flex-col gap-4">
                    <input class="p-2 mt-6 rounded-xl border" name="username" type="text" placeholder="Nama Lengkap" required>
                    <input class="p-2 rounded-xl border" name="email" type="email" placeholder="Email" required>
                    <input class="p-2 rounded-xl border" name="password" type="password" placeholder="Password" required>
                    <button class="bg-blue-600 hover:bg-blue-300 rounded-xl text-white py-2">Daftar</button>
                </form>

                <div class="mt-10 grid grid-cols-3 items-center">
                    <hr class="border-gray-500">
                    <p class="text-center">ATAU</p>
                    <hr class="border-gray-500">
                </div>

                <p class="mt-5 text-xs border-b border-gray-400 py-2">Sudah punya akun?</p>

                <div class="mt-1 text-xs flex items-center justify-between">
                    <p>Login sekarang</p>
                    <a href="login.php">
                        <button class="py-2 px-5 bg-white border rounded-xl hover:bg-blue-600 hover:text-white transition ease-in-out delay-75">Login</button>
                    </a>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
