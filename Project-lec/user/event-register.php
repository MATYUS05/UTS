<?php
session_start();
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $eventId = $_GET['id'];
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM registrants WHERE user_id = ? AND event_id = ?");
    $stmt->execute([$userId, $eventId]);
    $alreadyRegistered = $stmt->fetch();

    if (!$alreadyRegistered) {
        $stmt = $pdo->prepare("INSERT INTO registrants (user_id, event_id) VALUES (?, ?)");
        $stmt->execute([$userId, $eventId]);

        echo "Successfully registered for the event!";
    } else {
        echo "You are already registered for this event.";
    }

    header("Location: events.php");
    exit();
} else {
    echo "Invalid request.";
    exit();
}
?>
