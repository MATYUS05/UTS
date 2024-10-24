<?php
session_start();
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 

if (isset($_GET['id'])) {
    $eventId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$eventId]);
    $event = $stmt->fetch();
} else {
    echo "Event not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Details</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($event['name']); ?></h1>
    <p>Date: <?php echo htmlspecialchars($event['date']); ?></p>
    <p>Location: <?php echo htmlspecialchars($event['location']); ?></p>
    <p>Description: <?php echo htmlspecialchars($event['description']); ?></p>
    <p>Max Participants: <?php echo htmlspecialchars($event['max_participants']); ?></p>
    <a href="event-register.php?id=<?php echo $event['id']; ?>">Register for Event</a>
    <a href="events.php">Back to Events</a>
</body>
</html>
