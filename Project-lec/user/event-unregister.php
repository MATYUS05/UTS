<?php
session_start();
require_once '../config/database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['user_id'];

// Pastikan ID acara ada dalam parameter URL
if (isset($_GET['id'])) {
    $eventId = $_GET['id'];

    // Hapus pendaftaran dari tabel registrants
    $delete_sql = "DELETE FROM registrants WHERE user_id = ? AND event_id = ?";
    $delete_stmt = $pdo->prepare($delete_sql);
    if ($delete_stmt->execute([$userId, $eventId])) {
        echo json_encode(['success' => true, 'message' => 'Successfully unregistered from the event!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to unregister from the event.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Event ID not provided.']);
}
?>
