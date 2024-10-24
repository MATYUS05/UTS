<?php
session_start();
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 


$stmt = $pdo->prepare("SELECT * FROM events");
$stmt->execute();
$events = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Events</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Available Events</h1>
    <ul>
        <?php foreach ($events as $event): ?>
            <li>
                <a href="event-details.php?id=<?php echo $event['id']; ?>">
                    <?php echo htmlspecialchars($event['name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="profile.php">Back to Profile</a>
</body>
</html>
