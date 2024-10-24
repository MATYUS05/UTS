<?php
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../user/login.php");
    exit();
}

$event_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    echo "Event not found!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = htmlspecialchars($_POST['location']);
    $max_participants = intval($_POST['max_participants']);
    $status = $_POST['status'];

    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['size'] > 0) {
        $banner_image = $_FILES['banner_image']['name'];
        $target = '../uploads/' . basename($banner_image);
        move_uploaded_file($_FILES['banner_image']['tmp_name'], $target);

        $stmt = $pdo->prepare("UPDATE events SET name = ?, description = ?, date = ?, time = ?, location = ?, max_participants = ?, banner_image = ?, status = ? WHERE id = ?");
        $stmt->execute([$name, $description, $date, $time, $location, $max_participants, $banner_image, $status, $event_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE events SET name = ?, description = ?, date = ?, time = ?, location = ?, max_participants = ?, status = ? WHERE id = ?");
        $stmt->execute([$name, $description, $date, $time, $location, $max_participants, $status, $event_id]);
    }

    echo "Event updated successfully.";
}
?>

<form method="POST" enctype="multipart/form-data">
    <label>Event Name:</label><input type="text" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required><br>
    <label>Description:</label><textarea name="description"><?php echo htmlspecialchars($event['description']); ?></textarea><br>
    <label>Date:</label><input type="date" name="date" value="<?php echo $event['date']; ?>" required><br>
    <label>Time:</label><input type="time" name="time" value="<?php echo $event['time']; ?>" required><br>
    <label>Location:</label><input type="text" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required><br>
    <label>Max Participants:</label><input type="number" name="max_participants" value="<?php echo $event['max_participants']; ?>" required><br>
    <label>Status:</label>
    <select name="status">
        <option value="open" <?php echo ($event['status'] == 'open') ? 'selected' : ''; ?>>Open</option>
        <option value="closed" <?php echo ($event['status'] == 'closed') ? 'selected' : ''; ?>>Closed</option>
        <option value="canceled" <?php echo ($event['status'] == 'canceled') ? 'selected' : ''; ?>>Canceled</option>
    </select><br>
    <label>Banner Image:</label><input type="file" name="banner_image"><br>
    <button type="submit">Update Event</button>
</form>
