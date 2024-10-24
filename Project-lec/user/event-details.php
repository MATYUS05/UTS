<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../auth/login.php');
    exit;
}

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Query untuk mengambil data event
    $query = "SELECT id, name, image, description, date, lokasi, max_participants FROM events WHERE id = ?";
    $stmt = $conn->prepare($query);  // Menggunakan $conn sesuai config dari database.php
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        echo "Event not found.";
        exit;
    }
    $stmt->close();
} else {
    echo "Invalid event ID.";
    exit;
}

$user_id = $_SESSION['user_id'];
$registered = false;

// Pengecekan apakah user sudah terdaftar di event
$checkQuery = "SELECT * FROM registrants WHERE user_id = ? AND event_id = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("ii", $user_id, $event_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    $registered = true;
}
$checkStmt->close();

// Jika form untuk cancel registrasi dikirim
if (isset($_POST['cancel_registration'])) {
    $cancelStmt = $conn->prepare("DELETE FROM registrants WHERE user_id = ? AND event_id = ?");
    $cancelStmt->bind_param("ii", $user_id, $event_id);
    if ($cancelStmt->execute()) {
        echo "<p class='alert alert-success'>Successfully unregistered from the event!</p>";
        $registered = false; // Setelah pembatalan, ubah status terdaftar menjadi false
    } else {
        echo "<p class='alert alert-danger'>Error cancelling registration.</p>";
    }
    $cancelStmt->close();
}

// Jika form untuk registrasi dikirim
if (isset($_POST['register'])) {
    if (!$registered) {
        $registerStmt = $conn->prepare("INSERT INTO registrants (user_id, event_id) VALUES (?, ?)");
        $registerStmt->bind_param("ii", $user_id, $event_id);
        if ($registerStmt->execute()) {
            echo "<p class='alert alert-success'>Successfully registered for the event!</p>";
            $registered = true; // Setelah registrasi, ubah status terdaftar menjadi true
        } else {
            echo "<p class='alert alert-danger'>Error registering for the event.</p>";
        }
        $registerStmt->close();
    } else {
        echo "<p class='alert alert-warning'>You are already registered for this event.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['name']); ?> - Event Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../index.css">
</head>
<body>
    <div class="container mt-5">
        <div class="wrapper">
            <h2><?php echo htmlspecialchars($event['name']); ?></h2>
            <img src="../assets/<?php echo htmlspecialchars($event['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($event['name']); ?>">
            <p><strong>Description:</strong> <?php echo htmlspecialchars($event['description']); ?></p>
            <p><strong>Schedule:</strong> <?php echo htmlspecialchars($event['date']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($event['lokasi']); ?></p>
            <p><strong>Max Participants:</strong> <?php echo htmlspecialchars($event['max_participants']); ?></p>

            <!-- Tampilkan status registrasi -->
            <?php if ($registered): ?>
                <p class="alert alert-success">You are already registered for this event.</p>
                <form method="POST" action="">
                    <button type="submit" name="cancel_registration" class="btn btn-danger mt-3">Cancel Registration</button>
                </form>
            <?php else: ?>
                <form method="POST" action="">
                    <button type="submit" name="register" class="btn btn-primary mt-3">Register for Event</button>
                </form>
            <?php endif; ?>
            
            <a href="../dashboard/user_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
