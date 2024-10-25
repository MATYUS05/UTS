<?php
session_start();
require_once '../config/database.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Project-lec/auth/login.php');
    exit;
}

// Get event ID from the request
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
    
    // Fetch registrants with user data using JOIN
    $stmt = $pdo->prepare("
        SELECT 
            r.user_id,
            r.event_id,
            r.registration_date,
            u.username as user_name,
            u.email
        FROM registrations r
        INNER JOIN users u ON r.user_id = u.id
        WHERE r.event_id = :event_id
    ");
    $stmt->execute(['event_id' => $event_id]);
    $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Set headers for the CSV file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="registrants.csv"');
    
    // Open output stream
    $output = fopen('php://output', 'w');
    
    // Add column headers to CSV with desired order
    fputcsv($output, [
        'User ID',
        'Event ID',
        'Registration Date',
        'Username',
        'Email'
    ]);
    
    // Add each registration to CSV maintaining the same order as headers
    foreach ($registrations as $registration) {
        fputcsv($output, [
            $registration['user_id'],
            $registration['event_id'],
            $registration['registration_date'],
            $registration['user_name'],
            $registration['email']
        ]);
    }
    
    fclose($output);
    exit;
} else {
    // Redirect if event ID is not provided
    header('Location: admin_dashboard.php');
    exit;
}