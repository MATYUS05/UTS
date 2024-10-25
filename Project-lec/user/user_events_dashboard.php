<?php
session_start();
require_once '../config/database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$message = '';

// Ambil informasi profil pengguna
$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Update profil pengguna jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register_event_id'])) {
        // Pendaftaran acara
        $eventId = $_POST['register_event_id'];

        // Pastikan pengguna belum terdaftar untuk acara ini
        $check_sql = "SELECT * FROM registrants WHERE user_id = ? AND event_id = ?";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([$userId, $eventId]);

        if ($check_stmt->rowCount() === 0) {
            // Jika belum terdaftar, tambahkan ke registrants
            $insert_sql = "INSERT INTO registrants (user_id, event_id) VALUES (?, ?)";
            $insert_stmt = $pdo->prepare($insert_sql);
            $insert_stmt->execute([$userId, $eventId]);
            $message = 'Successfully registered for the event!';
        } else {
            $message = 'You are already registered for this event.';
        }
    } else {
        // Update profil pengguna
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Update password jika diisi
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->execute([$name, $email, $hashed_password, $userId]);
        } else {
            $update_sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->execute([$name, $email, $userId]);
        }
        $message = 'Profile updated successfully';
    }
}

// Ambil event yang didaftarkan pengguna
$stmt = $pdo->prepare("SELECT e.* FROM events e JOIN registrants r ON e.id = r.event_id WHERE r.user_id = ?");
$stmt->execute([$userId]);
$registeredEvents = $stmt->fetchAll();

// Ambil semua event terbaru untuk ditampilkan
$eventsStmt = $pdo->prepare("SELECT * FROM events ORDER BY event_date DESC");
$eventsStmt->execute();
$events = $eventsStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GatherCraft Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen relative">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-blue-900 text-white w-64 fixed h-full overflow-y-auto z-30 transition-transform duration-300 ease-in-out transform -translate-x-full md:translate-x-0">
            <div class="p-5">
                <h1 class="text-3xl font-bold mb-10 text-center">GatherCraft</h1>
                <nav class="space-y-4">
                    <a href="#profile" class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200 scroll-link" data-section="profile">Profile</a>
                    <a href="#event-browsing" class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200 scroll-link" data-section="event-browsing">Event Browsing</a>
                    <a href="#registered-events" class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200 scroll-link" data-section="registered-events">Registered Events</a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 w-full md:ml-64 p-4 md:p-8 mt-16 md:mt-0">
            <h2 class="text-2xl md:text-3xl font-semibold">User Dashboard</h2>

            <!-- Profile Section -->
            <div id="profile" class="mb-12 pt-16 -mt-16">
                <h3 class="text-xl md:text-2xl font-semibold mb-6">Profile</h3>

                
                <!-- Profile Update Form -->
                <form method="POST" action="">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">Username</label>
                        <input type="text" class="border rounded-md p-2 w-full" id="name" name="name" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700">Email</label>
                        <input type="email" class="border rounded-md p-2 w-full" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700">Password (Leave blank to keep current password)</label>
                        <input type="password" class="border rounded-md p-2 w-full" id="password" name="password">
                    </div>
                    <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Update Profile</button>
                </form>
            </div>

                            <!-- Registered Events Section -->
                    <div id="registered-events" class="mb-12 pt-16 -mt-16">
                        <h3 class="text-xl md:text-2xl font-semibold mb-6">Registered Events</h3>
                        <ul id="registered-events-list">
                            <?php if (count($registeredEvents) > 0): ?>
                                <?php foreach ($registeredEvents as $event): ?>
                                    <li class="bg-white shadow-md rounded-lg p-4 mb-4" id="event-<?php echo $event['id']; ?>">
                                        <h4 class="text-lg font-semibold"><?php echo htmlspecialchars($event['event_name']); ?></h4>
                                        <p>Date: <?php echo htmlspecialchars($event['event_date']); ?></p>
                                        <p>Location: <?php echo htmlspecialchars($event['location']); ?></p>
                                        <button class="text-red-500 cancel-registration" data-id="<?php echo $event['id']; ?>">Cancel Registration</button>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                    <p class="text-gray-600">You have not registered for any events yet. Check out our <a href="event-browsing.php" class="text-blue-600">available events</a>!</p>
                            <?php endif; ?>
                            
                        </ul>
                    </div>

                    <script>
                        // Menggunakan event delegation untuk menangani click event pada tombol cancel
                        document.getElementById('registered-events-list').addEventListener('click', function(e) {
                            if (e.target.classList.contains('cancel-registration')) {
                                const eventId = e.target.getAttribute('data-id');

                                // AJAX request untuk membatalkan pendaftaran
                                fetch('event-unregister.php?id=' + eventId, {
                                    method: 'GET',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Hapus elemen acara dari daftar
                                        const eventElement = document.getElementById('event-' + eventId);
                                        eventElement.remove();
                                    } else {
                                        alert(data.message || 'Failed to cancel registration. Please try again.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred. Please try again.');
                                });
                            }
                        });
                    </script>
                    

            <!-- Event Browsing Section -->
            <div id="event-browsing" class="mb-12 pt-16 -mt-16">
                <h3 class="text-xl md:text-2xl font-semibold mb-6">Event Browsing</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($events as $event): ?>
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                            <?php if (!empty($event['image'])): ?>
                                <img src="../uploads/<?php echo htmlspecialchars($event['image']); ?>" alt="Event Image" class="w-full h-48 object-cover">
                            <?php else: ?>
                                <img src="../path/to/default-image.jpg" alt="Default Event Image" class="w-full h-48 object-cover">
                            <?php endif; ?>
                            <div class="p-4 md:p-6">
                                <h4 class="text-lg md:text-xl font-semibold mb-2"><?php echo htmlspecialchars($event['event_name']); ?></h4>
                                <p class="text-gray-600 mb-4">Join us for an unforgettable experience!</p>
                                <p class="text-gray-500 text-sm mb-2">Date: <?php echo htmlspecialchars($event['event_date']); ?></p>
                                <p class="text-gray-500 text-sm mb-4">Location: <?php echo htmlspecialchars($event['location']); ?></p>
                                <form method="POST" action="">
                                    <input type="hidden" name="register_event_id" value="<?php echo $event['id']; ?>">
                                    <button type="submit" class="bg-green-500 text-white p-2 rounded-md">Register</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script untuk scroll ke bagian tertentu
        document.querySelectorAll('.scroll-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const sectionId = this.getAttribute('data-section');
                document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>
