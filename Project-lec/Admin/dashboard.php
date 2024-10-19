<?php
require '../config/database.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../user/login.php");
    exit();
}

$stmt = $pdo->query("SELECT e.id, e.name, COUNT(r.id) AS registrants_count
                     FROM events e
                     LEFT JOIN registrations r ON e.id = r.event_id
                     GROUP BY e.id");
$events = $stmt->fetchAll();
?>

<h1>Admin Dashboard</h1>
<table border="1">
    <tr>
        <th>Event Name</th>
        <th>Registrants</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($events as $event): ?>
        <tr>
            <td><?php echo htmlspecialchars($event['name']); ?></td>
            <td><?php echo $event['registrants_count']; ?></td>
            <td>
                <a href="event-edit.php?id=<?php echo $event['id']; ?>">Edit</a>
                <a href="event-delete.php?id=<?php echo $event['id']; ?>">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="event-create.php">Create New Event</a>
