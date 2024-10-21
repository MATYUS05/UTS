<?php
$host = 'localhost:3306';
$user = 'root'; // Username MySQL
$password = ''; // Password MySQL 
$dbname = 'todolist'; 

$mysqli = new mysqli($host, $user, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");
?>
