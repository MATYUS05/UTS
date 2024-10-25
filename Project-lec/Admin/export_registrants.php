<?php
session_start();
require_once '../config/database.php';

// Check if user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Project-lec/auth/login.php');
    exit;
}

// Check if event ID is provided
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Fetch registrations for the specified event
    $stmt = $pdo->prepare("SELECT * FROM registrations WHERE event_id = :event_id");
    $stmt->execute(['event_id' => $event_id]);
    $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate CSV file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="registrations.csv"');
    
    $output = fopen('php://output', 'w');

    // Header row
    fputcsv($output, ['ID', 'Username', 'Email', 'Registration Date']);

    // Data rows
    foreach ($registrations as $registration) {
        fputcsv($output, $registration);
    }

    fclose($output);
    exit;
} else {
    echo "No event ID specified.";
}
?>
