<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $todo_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM todo_lists WHERE id = ? AND user_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $todo_id, $user_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Error deleting to-do list.";
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>
