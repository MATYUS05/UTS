<?php
session_start();
include __DIR__ . "/config/database.php"; // Pastikan path ini benar 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Simpan pengguna ke database
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $password]);

    // Redirect ke halaman login setelah berhasil registrasi
    header("Location: login.php");
    exit;
}
?>
