<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

include '../config/database.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES);

    // Jika password diisi, maka update password juga
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $mysqli->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
        $stmt->bind_param('sssi', $username, $email, $password, $user_id);
    } else {
        $stmt = $mysqli->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->bind_param('ssi', $username, $email, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['username'] = $username; // Update username di sesi
        header("Location: profile.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Mengambil data profil saat ini untuk ditampilkan di form
$stmt = $mysqli->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();
$mysqli->close();
?>

<h2>Edit Profile</h2>
<form method="POST" action="edit_profile.php">
    <input type="text" name="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES); ?>" required>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES); ?>" required>
    <input type="password" name="password" placeholder="New Password (optional)">
    <button type="submit">Update</button>
</form>

<a href="profile.php">Back to Profile</a>
