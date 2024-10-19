<?php
session_start();
require '../config/database.php';

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $eventId = $_GET['id'];
    $userId = $_SESSION['user_id'];

    // Hapus registrasi pengguna dari event
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
