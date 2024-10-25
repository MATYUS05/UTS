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
</head>
<body>
    <h2>Edit Event</h2>
    <form action="edit_event.php?id=<?php echo $event_id; ?>" method="POST" enctype="multipart/form-data">
        <label>Event Name:</label>
        <input type="text" name="event_name" value="<?php echo htmlspecialchars($event['event_name']); ?>" required><br>

        <label>Event Date:</label>
        <input type="date" name="event_date" value="<?php echo htmlspecialchars($event['event_date']); ?>" required><br>

        <label>Location:</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required><br>

        <label>Max Participants:</label>
        <input type="number" name="max_participants" value="<?php echo htmlspecialchars($event['max_participants']); ?>" required><br>

        <label>Event Image:</label>
        <input type="file" name="event_image">
        <p>Current Image: <?php echo htmlspecialchars($event['image']); ?></p>
        
        <button type="submit">Update Event</button>
    </form>
</body>
</html>
