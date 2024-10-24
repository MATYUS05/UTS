<?php
session_start();
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT e.* FROM events e JOIN registrants r ON e.id = r.event_id WHERE r.user_id = ?");
$stmt->execute([$userId]);
$registeredEvents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Your Registered Events</h1>
    <ul>
        <?php foreach ($registeredEvents as $event): ?>
            <li>
                <?php echo htmlspecialchars($event['name']); ?>
                <a href="event-unregister.php?id=<?php echo $event['id']; ?>">Cancel Registration</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="profile.php">Back to Profile</a>
</body>
</html>
