<?php
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../user/login.php");
    exit();
}

$event_id = $_GET['id'];

// Hapus event dari database
$stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
if ($stmt->execute([$event_id])) {
    echo "Event deleted successfully. <a href='dashboard.php'>Go back to dashboard</a>";
} else {
    echo "Error deleting event.";
}
