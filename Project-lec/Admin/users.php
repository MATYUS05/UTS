<?php
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../user/login.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<h1>Registered Users</h1>
<table border="1">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td>
                <a href="user-delete.php?id=<?php echo $user['id']; ?>">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
