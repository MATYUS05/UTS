<?php
session_start();
require_once '../config/database.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Project-lec/auth/login.php');
    exit;
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];
    $max_participants = $_POST['max_participants'];
    $image = $_FILES['event_image']['name'];
    $target = "../uploads/" . basename($image);

    // Simpan event ke database
    $stmt = $pdo->prepare("INSERT INTO events (event_name, event_date, location, max_participants) VALUES (?, ?, ?, ?)");
    $stmt->execute([$event_name, $event_date, $location, $max_participants]);

    // Upload file image
    if (move_uploaded_file($_FILES['event_image']['tmp_name'], $target)) {
        echo "Event created successfully!";
    } else {
        echo "Failed to upload image.";
    }

    // Redirect kembali ke dashboard admin
    header("Location: admin_dashboard.php");
    exit;
}
?>

<!-- HTML Form untuk Menambah Event -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Event</title>
</head>
<body>
    <h2>Create New Event</h2>
    <form action="create_event.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="event_name" placeholder="Event Name" required>
        <input type="date" name="event_date" required>
        <input type="text" name="location" placeholder="Location" required>
        <input type="number" name="max_participants" placeholder="Max Participants" required>
        <input type="file" name="event_image">
        <button type="submit">Create Event</button>
    </form>
</body>
</html>
