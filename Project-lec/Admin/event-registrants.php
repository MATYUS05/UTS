<?php
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../user/login.php");
    exit();
}

$event_id = $_GET['event_id'];

$stmt = $pdo->prepare("SELECT users.name, users.email 
                       FROM registrations 
                       JOIN users ON registrations.user_id = users.id 
                       WHERE registrations.event_id = ?");
$stmt->execute([$event_id]);
$registrants = $stmt->fetchAll();

if (isset($_POST['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=registrants.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Name', 'Email']);
    foreach ($registrants as $registrant) {
        fputcsv($output, $registrant);
    }
    fclose($output);
    exit();
}
?>

<h1>Registrants for Event</h1>
<form method="POST">
    <button type="submit" name="export">Export to CSV</button>
</form>
<table border="1">
    <tr>
        <th>Name</th>
        <th>Email</th>
    </tr>
    <?php foreach ($registrants as $registrant): ?>
        <tr>
            <td><?php echo htmlspecialchars($registrant['name']); ?></td>
            <td><?php echo htmlspecialchars($registrant['email']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
