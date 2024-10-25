<?php
session_start();
require_once '../config/database.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Project-lec/auth/login.php');
    exit;
}

// Ambil ID event dari URL
$event_id = $_GET['id'] ?? null;

if (!$event_id) {
    echo "Event ID is missing!";
    exit;
}

// Ambil data event berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Event not found!";
    exit;
}

// Jika form disubmit, perbarui data event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];
    $max_participants = $_POST['max_participants'];
    $image = $_FILES['event_image']['name'] ? $_FILES['event_image']['name'] : $event['image'];
    $target = "../uploads/" . basename($image);

    // Update event di database
    $stmt = $pdo->prepare("UPDATE events SET event_name = ?, event_date = ?, location = ?, max_participants = ?, image = ? WHERE id = ?");
    $stmt->execute([$event_name, $event_date, $location, $max_participants, $image, $event_id]);

    // Upload gambar jika ada gambar baru
    if ($_FILES['event_image']['name']) {
        if (move_uploaded_file($_FILES['event_image']['tmp_name'], $target)) {
            echo "Event updated successfully!";
        } else {
            echo "Failed to upload image.";
        }
    }

    // Redirect kembali ke dashboard admin setelah update
    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Event</h2>
        <form action="edit_event.php?id=<?php echo $event_id; ?>" method="POST" enctype="multipart/form-data">
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Event Name:</label>
                <input type="text" name="event_name" value="<?php echo htmlspecialchars($event['event_name']); ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Event Date:</label>
                <input type="date" name="event_date" value="<?php echo htmlspecialchars($event['event_date']); ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Location:</label>
                <input type="text" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Max Participants:</label>
                <input type="number" name="max_participants" value="<?php echo htmlspecialchars($event['max_participants']); ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Event Image:</label>
                <input type="file" name="event_image" class="w-full px-4 py-2 border rounded-lg">
                <p class="text-gray-600 mt-2">Current Image: <?php echo htmlspecialchars($event['image']); ?></p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">Update Event</button>
            </div>
        </form>
    </div>
</body>
</html>
