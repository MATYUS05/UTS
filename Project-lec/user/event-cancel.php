<?php
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$event_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Hapus pendaftaran dari database
$stmt = $pdo->prepare("DELETE FROM registrations WHERE event_id = ? AND user_id = ?");
if ($stmt->execute([$event_id, $user_id])) {
    echo "Registration cancelled successfully. <a href='profile.php'>Go back to profile</a>";
} else {
    echo "Error cancelling registration.";
}
