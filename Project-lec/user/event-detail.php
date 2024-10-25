<?php
require_once '../config/database.php';
session_start(); // Start the session to manage user login

if (!isset($_GET['id'])) {
    die('Event ID is required.');
}

$eventId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$eventId]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    die('Event not found.');
}

// Check if user is logged in
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    if ($userId) {
        $stmt = $pdo->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
        if ($stmt->execute([$userId, $eventId])) {
            echo "<p class='text-green-500'>Successfully registered for the event.</p>";
        } else {
            echo "<p class='text-red-500'>Registration failed.</p>";
        }
    } else {
        echo "<p class='text-red-500'>You must be logged in to register.</p>";
    }
}

// Handle cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel'])) {
    if ($userId) {
        $stmt = $pdo->prepare("DELETE FROM registrations WHERE user_id = ? AND event_id = ?");
        if ($stmt->execute([$userId, $eventId])) {
            echo "<p class='text-green-500'>Successfully canceled your registration.</p>";
        } else {
            echo "<p class='text-red-500'>Cancellation failed.</p>";
        }
    } else {
        echo "<p class='text-red-500'>You must be logged in to cancel your registration.</p>";
    }
}

// Check if user is already registered for the event
$registered = false;
if ($userId) {
    $stmt = $pdo->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $stmt->execute([$userId, $eventId]);
    $registered = $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}

// Render detail event
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($event['event_name']); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($event['event_name']); ?></h1>
    <p>Date: <?php echo htmlspecialchars($event['event_date']); ?></p>
    <p>Location: <?php echo htmlspecialchars($event['location']); ?></p>
    <p>Description: <?php echo nl2br(htmlspecialchars($event['description'])); ?></p>

    <form method="post" class="mt-4">
        <?php if ($registered): ?>
            <button name="cancel" class="bg-red-500 text-white px-4 py-2">Cancel Registration</button>
        <?php else: ?>
            <button name="register" class="bg-blue-500 text-white px-4 py-2">Register for Event</button>
        <?php endif; ?>
    </form>
    <!-- Back to Dashboard Button -->
    <a href="../user/user_events_dashboard.php" class="mt-4 inline-block bg-gray-300 text-black px-4 py-2 rounded">Back to Dashboard</a>
</body>
</html>
