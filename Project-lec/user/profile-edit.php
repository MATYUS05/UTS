<?php
session_start();
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Update informasi pengguna
    if ($password) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
        $stmt->execute([$name, $email, $password, $userId]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $email, $userId]);
    }

    echo "Profile updated successfully!";
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Edit Profile</h1>
    <form method="POST">
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <input type="password" name="password" placeholder="New Password (optional)">
        <button type="submit">Update Profile</button>
    </form>
    <a href="profile.php">Back to Profile</a>
</body>
</html>
