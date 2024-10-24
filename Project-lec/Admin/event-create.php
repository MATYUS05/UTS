<?php
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../user/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = htmlspecialchars($_POST['location']);
    $max_participants = intval($_POST['max_participants']);
    
    // File upload handling
    $banner_image = $_FILES['banner_image']['name'];
    $target = '../uploads/' . basename($banner_image);
    move_uploaded_file($_FILES['banner_image']['tmp_name'], $target);

    $stmt = $pdo->prepare("INSERT INTO events (name, description, date, time, location, max_participants, banner_image, status) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, 'open')");
    if ($stmt->execute([$name, $description, $date, $time, $location, $max_participants, $banner_image])) {
        echo "Event created successfully.";
    } else {
        echo "Error creating event.";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <label>Event Name:</label><input type="text" name="name" required><br>
    <label>Description:</label><textarea name="description"></textarea><br>
    <label>Date:</label><input type="date" name="date" required><br>
    <label>Time:</label><input type="time" name="time" required><br>
    <label>Location:</label><input type="text" name="location" required><br>
    <label>Max Participants:</label><input type="number" name="max_participants" required><br>
    <label>Banner Image:</label><input type="file" name="banner_image" required><br>
    <button type="submit">Create Event</button>
</form>
