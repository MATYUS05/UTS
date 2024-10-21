<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

include '../config/database.php';

$user_id = $_SESSION['user_id'];
$stmt = $mysqli->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();
$mysqli->close();
?>

<h2>Profile</h2>
<p>Username: <?php echo htmlspecialchars($username, ENT_QUOTES); ?></p>
<p>Email: <?php echo htmlspecialchars($email, ENT_QUOTES); ?></p>
<a href="edit_profile.php">Edit Profile</a>
<a href="dashboard.php">Back to Dashboard</a>
