<?php
session_start();
require_once '../config/database.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Project-lec/auth/login.php');
    exit;
}

// Ambil nama event dari parameter URL dan sanitasi
$event_name = filter_input(INPUT_GET, 'event_name', FILTER_SANITIZE_STRING);
if (!$event_name) {
    echo "Event name is missing or invalid!";
    exit;
}

try {
    // Ambil event_id berdasarkan nama event
    $stmt = $pdo->prepare("SELECT id FROM events WHERE name = ?");
    $stmt->execute([$event_name]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo "Event not found!";
        exit;
    }

    $event_id = $event['id'];

    // Ambil data pendaftar berdasarkan ID event
    $stmt = $pdo->prepare("
        SELECT u.username, u.email, r.registration_date
        FROM registrants r
        JOIN users u ON r.user_id = u.id
        WHERE r.event_id = ?
    ");
    $stmt->execute([$event_id]);
    $registrants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$registrants) {
        echo "No registrants found for this event.";
        exit;
    }

    // Set header untuk file CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=registrants_event_' . $event_id . '.csv');

    // Membuka file output dalam mode tulis
    $output = fopen('php://output', 'w');

    // Tulis header kolom ke CSV
    fputcsv($output, ['Username', 'Email', 'Registration Date']);

    // Tulis data setiap pendaftar ke CSV
    foreach ($registrants as $registrant) {
        fputcsv($output, $registrant);
    }

    // Tutup file output
    fclose($output);
} catch (PDOException $e) {
    echo "Error fetching registrants: " . $e->getMessage();
}
exit;
?>
