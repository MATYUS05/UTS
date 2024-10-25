<?php
session_start();
require_once '../config/database.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Project-lec/auth/login.php');
    exit;
}

// Ambil data statistik
$stmt = $pdo->prepare("SELECT COUNT(*) as total_events FROM events");
$stmt->execute();
$total_events = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) as total_registrations FROM registrations");
$stmt->execute();
$total_registrations = $stmt->fetchColumn();

// Ambil semua data event
$stmt = $pdo->prepare("SELECT * FROM events");
$stmt->execute();
$events = $stmt->fetchAll();

// Ambil data user
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll();

// Fungsi untuk menghapus event
if (isset($_GET['delete_event_id'])) {
    $event_id = $_GET['delete_event_id'];

    // Hapus event dan semua registrasi terkait
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = :id");
    $stmt->execute(['id' => $event_id]);

    // Redirect ke halaman dashboard
    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GatherCraft Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

    <!-- Sidebar for Admin -->
    <div class="flex min-h-screen relative">
        <div id="sidebar" class="bg-blue-900 text-white w-64 fixed h-full overflow-y-auto z-30">
            <div class="p-5">
                <h1 class="text-3xl font-bold mb-10 text-center">Admin Dashboard</h1>
                <nav class="space-y-4">
                    <a href="#dashboard" class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200">Dashboard</a>
                    <a href="#event-management" class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200">Event Management</a>
                    <a href="#view-registrations" class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200">View Event Registrations</a>
                    <a href="#user-management" class="block text-lg py-3 px-4 rounded hover:bg-blue-800 transition duration-200">User Management</a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 w-full ml-64 p-8">
            <!-- Dashboard Section -->
            <div id="dashboard" class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">Dashboard</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-2">Total Events</h4>
                        <p class="text-gray-600"><?php echo $total_events; ?> Events Available</p>
                    </div>
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-2">Total Registrants</h4>
                        <p class="text-gray-600"><?php echo $total_registrations; ?> Total Registrants</p>
                    </div>
                </div>
            </div>

            <!-- Event Management Section -->
            <div id="event-management" class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">Event Management</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Create New Event -->
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-4">Create New Event</h4>
                        <form action="create_event.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label class="block text-gray-700">Event Name</label>
                                <input type="text" name="event_name" required class="w-full border-2 border-gray-300 rounded-md">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700">Date</label>
                                <input type="date" name="event_date" required class="w-full border-2 border-gray-300 rounded-md">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700">Location</label>
                                <input type="text" name="location" required class="w-full border-2 border-gray-300 rounded-md">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700">Maximum Participants</label>
                                <input type="number" name="max_participants" required class="w-full border-2 border-gray-300 rounded-md">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700">Upload Image</label>
                                <input type="file" name="event_image" class="w-full">
                            </div>
                            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Create Event</button>
                        </form>
                    </div>
                    
                    <!-- Loop through events -->
                    <?php foreach ($events as $event): ?>
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($event['event_name']); ?></h4>
                        <p class="text-gray-600 mb-4">Date: <?php echo htmlspecialchars($event['event_date']); ?></p>
                        <p class="text-gray-600 mb-4">Location: <?php echo htmlspecialchars($event['location']); ?></p>
                        <p class="text-gray-600 mb-4">Max Participants: <?php echo htmlspecialchars($event['max_participants']); ?></p>
                        <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="text-blue-600 hover:underline">Edit</a>
                        <a href="?delete_event_id=<?php echo $event['id']; ?>" class="text-red-600 hover:underline ml-4" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- View Event Registrations Section -->
            <div id="view-registrations" class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">View Event Registrations</h3>
                <p>See who has registered for each event and export the list of registrants.</p>
                <td class="border border-gray-300 px-4 py-2 flex space-x-2">
                    <a href="export_registrants.php?event_id=<?php echo $event['id']; ?>" class="bg-green-600 text-white py-1 px-2 rounded">Export Registrants to CSV</a>
                </td>   
             </div>

            <!-- User Management Section -->
            <div id="user-management" class="mb-12">
                <h3 class="text-2xl font-semibold mb-6">User Management</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Loop through users -->
                    <?php foreach ($users as $user): ?>
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h4 class="text-lg font-semibold mb-2"><?php echo htmlspecialchars($user['username']); ?></h4>
                        <p class="text-gray-600 mb-4">Email: <?php echo htmlspecialchars($user['email']); ?></p>
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this user?');">Delete User</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
