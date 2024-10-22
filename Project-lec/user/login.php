<?php
session_start();
require '../config/database.php'; // Pastikan path ini benar untuk file konfigurasi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    // Persiapkan statement untuk memeriksa email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verifikasi password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        header("Location: profile.php"); // Redirect ke halaman profile
        exit();
    } else {
        echo "Invalid email or password!";
    }
}
?>
