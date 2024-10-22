<?php
require 'config/database.php';

// Ambil daftar event untuk ditampilkan di homepage
$stmt = $pdo->prepare("SELECT * FROM events");
$stmt->execute();
$events = $stmt->fetchAll();
?>
<!-- Header -->
<!DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        // JavaScript untuk menampilkan/menghilangkan menu mobile
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
</head>
<body class="min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a class="text-xl font-bold text-blue-500" href="#">Event Management</a>
            <div class="hidden md:flex">
                <a href="login.html" class="text-gray-700 hover:text-blue-500 mx-2">Login</a>
                <a href="signup.html" class="text-gray-700 hover:text-blue-500 mx-2">Register</a>
            </div>
            <!-- Hamburger Icon -->
            <button class="md:hidden text-gray-700 focus:outline-none" onclick="toggleMenu()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden">
            <a href="login.html" class="block text-gray-700 hover:text-blue-500 py-2 px-4">Login</a>
            <a href="signup.html" class="block text-gray-700 hover:text-blue-500 py-2 px-4">Register</a>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mx-auto px-4 py-10">
        <main class="flex-grow text-center">
            <h1 class="text-4xl font-bold text-center text-blue-500 mb-6">Welcome to the Event Management System</h1>
            <h2 class="text-2xl font-semibold mb-4 text-center">Upcoming Events</h2>
            
            <!-- Gambar yang dipusatkan -->
            <div class="flex justify-center">
                <img class="rounded-xl h-auto max-w-full sm:max-w-md" src="Image/login.webp" alt="Login Image">
            </div>

            <!-- Daftar Event -->
            <ul class="space-y-3 mt-6">
                <?php foreach ($events as $event): ?>
                    <li class="bg-white shadow p-4 rounded-md">
                        <a href="user/event-details.php?id=<?php echo $event['id']; ?>" class="text-blue-500 hover:underline">
                            <?php echo htmlspecialchars($event['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </main>           
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4">
        <p>&copy; 2024 Event Management System</p>
    </footer>
</body>
</html>
