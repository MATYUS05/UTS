<?php
session_start();
require_once '../config/database.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$userId = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $eventId = $_GET['id'];

    // Hapus dari registrants
    $delete_sql = "DELETE FROM registrants WHERE user_id = ? AND event_id = ?";
    $delete_stmt = $pdo->prepare($delete_sql);
    
    if ($delete_stmt->execute([$userId, $eventId])) {
        // Kirimkan respons sukses
        echo json_encode(['success' => true, 'message' => 'You have successfully canceled your registration for the event.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to cancel registration.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No event ID provided.']);
}
?>
