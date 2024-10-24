<?php
// Menampilkan semua error untuk debugging
error_reporting(E_ALL); 
ini_set('display_errors', 1); 

// Memulai sesi
session_start();
require_once '../config/database.php';

// Memastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Mendapatkan data pengguna dan event yang telah mereka daftarkan
$sql = "SELECT u.id, u.username, u.email, 
               GROUP_CONCAT(e.name SEPARATOR ', ') AS registered_events
        FROM users u
        LEFT JOIN event_registrations er ON u.id = er.user_id
        LEFT JOIN events e ON er.event_id = e.id
        WHERE u.role = 'user'
        GROUP BY u.id";

$result = $conn->query($sql);

// Menghapus pengguna jika `delete_id` diberikan
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    // Persiapan query DELETE
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $delete_id);
    
    // Eksekusi query dan redirect
    if ($stmt->execute()) {
        header("Location: user_management.php");
        exit;
    } else {
        echo "Error deleting user.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Sidebar -->
    <div class="flex flex-col w-64 h-screen bg-gray-800 text-white fixed">
        <div class="profile-container flex items-center justify-center h-16 border-b border-gray-700">
            <div class="profile-circle bg-gray-600 w-10 h-10 rounded-full flex items-center justify-center">
                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
            </div>
            <span class="ml-3"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>

        <nav class="mt-5">
            <a href="../dashboard/admin_dashboard.php" class="block py-2.5 px-4 hover:bg-gray-700">Dashboard</a>
            <a href="../content/event_management.php" class="block py-2.5 px-4 hover:bg-gray-700">Event Management</a>
            <a href="../content/user_management.php" class="block py-2.5 px-4 bg-gray-900">User Management</a>
        </nav>

        <div class="mt-auto border-t border-gray-700">
            <a href="../auth/logout.php" class="block py-2.5 px-4 hover:bg-gray-700">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <h1 class="text-3xl font-bold mb-6">User Management</h1>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Username</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Registered Events</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php if ($result->num_rows > 0): ?>
                        <!-- Loop untuk menampilkan pengguna -->
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?php echo htmlspecialchars($row['username']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($row['registered_events'] ?: 'No events'); ?></td>
                                <td class="px-4 py-2">
                                    <a href="?delete_id=<?php echo $row['id']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center">No users available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
