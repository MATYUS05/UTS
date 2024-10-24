<?php
session_start();
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $eventId = $_GET['id'];
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("DELETE FROM registrants WHERE user_id = ? AND event_id = ?");
    $stmt->execute([$userId, $eventId]);

    echo "Successfully unregistered from the event!";
    header("Location: registered-events.php");
    exit();
} else {
    echo "Invalid request.";
    exit();
}
?>
