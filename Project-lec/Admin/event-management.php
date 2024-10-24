<?php
require_once '../config/database.php';
session_start();

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../user/login.php");
    exit();
}

// Fungsi untuk menangani form submit (create atau update event)
function handleEventForm($pdo, $isEdit = false, $eventId = null) {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = htmlspecialchars($_POST['location']);
    $max_participants = intval($_POST['max_participants']);
    $status = $isEdit ? $_POST['status'] : 'open'; // Status diatur hanya jika dalam mode edit
    
    // File upload handling
    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['size'] > 0) {
        $banner_image = $_FILES['banner_image']['name'];
        $target = '../uploads/' . basename($banner_image);
        move_uploaded_file($_FILES['banner_image']['tmp_name'], $target);
    } else {
        $banner_image = $isEdit ? null : ''; // Jika mode edit dan tidak ada file yang diupload, tidak update banner
    }

    if ($isEdit) {
        // Update event
        if ($banner_image) {
            $stmt = $pdo->prepare("UPDATE events SET name = ?, description = ?, date = ?, time = ?, location = ?, max_participants = ?, banner_image = ?, status = ? WHERE id = ?");
            $stmt->execute([$name, $description, $date, $time, $location, $max_participants, $banner_image, $status, $eventId]);
        } else {
            $stmt = $pdo->prepare("UPDATE events SET name = ?, description = ?, date = ?, time = ?, location = ?, max_participants = ?, status = ? WHERE id = ?");
            $stmt->execute([$name, $description, $date, $time, $location, $max_participants, $status, $eventId]);
        }
        echo "Event updated successfully.";
    } else {
        // Create new event
        $stmt = $pdo->prepare("INSERT INTO events (name, description, date, time, location, max_participants, banner_image, status) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, 'open')");
        if ($stmt->execute([$name, $description, $date, $time, $location, $max_participants, $banner_image])) {
            echo "Event created successfully.";
        } else {
            echo "Error creating event.";
        }
    }
}

// Hapus event
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $event_id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    if ($stmt->execute([$event_id])) {
        echo "Event deleted successfully. <a href='dashboard.php'>Go back to dashboard</a>";
    } else {
        echo "Error deleting event.";
    }
    exit();
}

// Jika form di-submit (untuk create atau edit)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET['id'])) {
        handleEventForm($pdo, true, $_GET['id']); // Edit event
    } else {
        handleEventForm($pdo); // Create event
    }
}

// Jika user ingin mengedit event
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch();

    if (!$event) {
        echo "Event not found!";
        exit();
    }
}
?>

<!-- Form untuk membuat atau mengedit event -->
<form method="POST" enctype="multipart/form-data">
    <label>Event Name:</label><input type="text" name="name" value="<?php echo isset($event) ? htmlspecialchars($event['name']) : ''; ?>" required><br>
    <label>Description:</label><textarea name="description"><?php echo isset($event) ? htmlspecialchars($event['description']) : ''; ?></textarea><br>
    <label>Date:</label><input type="date" name="date" value="<?php echo isset($event) ? $event['date'] : ''; ?>" required><br>
    <label>Time:</label><input type="time" name="time" value="<?php echo isset($event) ? $event['time'] : ''; ?>" required><br>
    <label>Location:</label><input type="text" name="location" value="<?php echo isset($event) ? htmlspecialchars($event['location']) : ''; ?>" required><br>
    <label>Max Participants:</label><input type="number" name="max_participants" value="<?php echo isset($event) ? $event['max_participants'] : ''; ?>" required><br>

    <!-- Jika mode edit, tampilkan opsi status -->
    <?php if (isset($event)): ?>
        <label>Status:</label>
        <select name="status">
            <option value="open" <?php echo ($event['status'] == 'open') ? 'selected' : ''; ?>>Open</option>
            <option value="closed" <?php echo ($event['status'] == 'closed') ? 'selected' : ''; ?>>Closed</option>
            <option value="canceled" <?php echo ($event['status'] == 'canceled') ? 'selected' : ''; ?>>Canceled</option>
        </select><br>
    <?php endif; ?>

    <label>Banner Image:</label><input type="file" name="banner_image"><br>
    <button type="submit"><?php echo isset($event) ? 'Update' : 'Create'; ?> Event</button>
</form>

<!-- Tampilkan tombol hapus jika sedang dalam mode edit -->
<?php if (isset($event)): ?>
    <form method="GET" action="">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?php echo $event_id; ?>">
        <button type="submit">Delete Event</button>
    </form>
<?php endif; ?>
