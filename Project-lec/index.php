<?php
require 'config/database.php';

// Ambil daftar event untuk ditampilkan di homepage
$stmt = $pdo->prepare("SELECT * FROM events");
$stmt->execute();
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Welcome to the Event Management System</h1>
    <h2>Upcoming Events</h2>
    <ul>
        <?php foreach ($events as $event): ?>
            <li>
                <a href="user/event-details.php?id=<?php echo $event['id']; ?>">
                    <?php echo htmlspecialchars($event['name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="user/login.php">Login</a>
    <a href="user/register.php">Register</a>
</body>
</html>
