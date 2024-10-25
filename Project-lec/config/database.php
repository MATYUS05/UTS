<?php
// Konfigurasi database
$host = 'localhost';      // Host database
$dbname = 'event'; // Ganti dengan nama database Anda
$username = 'root';       // Username database (default: root)
$password = '';          // Password database (default kosong untuk XAMPP)
$charset = 'utf8mb4';    // Character set

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// Opsi PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Error reporting
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Set default fetch mode
    PDO::ATTR_EMULATE_PREPARES   => false,                   // Gunakan prepared statements asli
];

try {
    // Buat koneksi PDO
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // Jika koneksi gagal
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
