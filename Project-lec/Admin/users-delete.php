<?php
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../user/login.php");
    exit();
}

$user_id = $_GET['id'];

// Hapus user dari database
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
if ($stmt->execute([$user_id])) {
    echo "User deleted successfully. <a href='users.php'>Go back to users list</a>";
} else {
    echo "Error deleting user.";
}
