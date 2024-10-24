<?php
require_once '../config/database.php';
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

<!DOCTYPE html>
<html>
<head>
    <title>Registrants</title>
</head>
<body>
    <h1>Registrants</h1>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Registration Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registrants as $registrant): ?>
            <tr>
                <td><?= $registrant['username']; ?></td>
                <td><?= $registrant['email']; ?></td>
                <td><?= $registrant['registration_date']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

