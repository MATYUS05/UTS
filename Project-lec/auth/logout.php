<?php
session_start();
session_destroy(); // Destroy the session
header('Location: ../auth/login.php'); // Redirect to login page
exit;
?>
