<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$message = '';

// Fetch user profile information
$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Update user profile if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Update password if provided
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

// Fetch user's registered events
$stmt = $pdo->prepare("SELECT e.* FROM events e JOIN registrants r ON e.id = r.event_id WHERE r.user_id = ?");
$stmt->execute([$userId]);
$registeredEvents = $stmt->fetchAll();

// Fetch all events for browsing
$eventsStmt = $pdo->prepare("SELECT * FROM events");
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

                <!-- Message -->
                <?php if ($message): ?>
                    <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>

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
                <ul>
                    <?php if (count($registeredEvents) > 0): ?>
                        <?php foreach ($registeredEvents as $event): ?>
                            <li class="bg-white shadow-md rounded-lg p-4 mb-4">
                                <h4 class="text-lg font-semibold"><?php echo htmlspecialchars($event['name']); ?></h4>
                                <p>Date: <?php echo htmlspecialchars($event['date']); ?></p>
                                <p>Location: <?php echo htmlspecialchars($event['lokasi']); ?></p>
                                <a href="event-unregister.php?id=<?php echo $event['id']; ?>" class="text-red-500">Cancel Registration</a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-600">You have not registered for any events yet.</p>
                    <?php endif; ?>
                </ul>
            </div>

                            
             <!-- Event Browsing Section -->
                <div id="event-browsing" class="mb-12 pt-16 -mt-16">
                    <h3 class="text-xl md:text-2xl font-semibold mb-6"> Event Browsing</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        <!-- Menampilkan event yang terdaftar -->
                        <?php foreach ($events as $event): ?>
                            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                                <img src="../Image/<?php echo htmlspecialchars($event['image']); ?>" alt="Event Image" class="w-full h-48 object-cover">
                                <div class="p-4 md:p-6">
                                    <h4 class="text-lg md:text-xl font-semibold mb-2"><?php echo htmlspecialchars($event['name']); ?></h4>
                                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($event['description']); ?></p>
                                    <p class="text-gray-500 text-sm mb-2">Date: <?php echo htmlspecialchars($event['date']); ?></p>
                                    <p class="text-gray-500 text-sm mb-4">Location: <?php echo htmlspecialchars($event['lokasi']); ?></p>
                                    <form method="POST" action="event-detail.php?id=<?php echo $event['id']; ?>">
                                        <button type="submit" class="text-blue-600 hover:text-blue-800">View Details</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Contoh Kartu Event -->
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                            <img src="../Image/music.jpg" alt="Event Image" class="w-full h-48 object-cover">
                            <div class="p-4 md:p-6">
                                <h4 class="text-lg md:text-xl font-semibold mb-2">Music Festival 2024</h4>
                                <p class="text-gray-600 mb-4">Join us for an unforgettable evening of live music, food, and fun!</p>
                                <p class="text-gray-500 text-sm mb-2">Date: October 30, 2024</p>
                                <p class="text-gray-500 text-sm mb-4">Location: Central Park</p>
                                <a href="#" class="text-blue-600 hover:text-blue-800">View Details</a>
                            </div>
                        </div>

                        <!-- Kartu Event Tambahan -->
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                            <img src="../Image/th.jpeg" alt="Event Image" class="w-full h-48 object-cover">
                            <div class="p-4 md:p-6">
                                <h4 class="text-lg md:text-xl font-semibold mb-2">Art Exhibition 2024</h4>
                                <p class="text-gray-600 mb-4">Explore contemporary artworks from various artists.</p>
                                <p class="text-gray-500 text-sm mb-2">Date: November 15, 2024</p>
                                <p class="text-gray-500 text-sm mb-4">Location: Art Gallery, Downtown</p>
                                <a href="#" class="text-blue-600 hover:text-blue-800">View Details</a>
                            </div>
                        </div>

                    </div>
                </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('.scroll-link');

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);
                    
                    if (targetSection) {
                        targetSection.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
