<?php
session_start();
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Your Profile</h1>
    <p>Name: <?php echo htmlspecialchars($user['name']); ?></p>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <a href="profile-edit.php">Edit Profile</a>
    <a href="events.php">View Available Events</a>
    <a href="logout.php">Logout</a>
</body>
</html>