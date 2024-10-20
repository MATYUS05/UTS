<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_lab";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Protect from SQL Injection
function sanitize_input($data) {
    global $conn;
    $data = htmlspecialchars($data); // Prevent XSS
    return $conn->real_escape_string($data);
}
?>
