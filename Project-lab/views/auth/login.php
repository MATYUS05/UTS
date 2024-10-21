<?php
session_start();
include '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: ../dashboard.php");
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found.";
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #121212; 
        color: #ffffff; 
    }
    .container {
        margin-top: 50px;
    }
    .card {
        padding: 20px;
        border-radius: 10px;
        background-color: #1e1e1e; 
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.7);
        color: #ffffff; 
    }
    .form-group label {
        font-weight: bold;
        color: #ffffff; 
    }
    .form-control {
        background-color: #2a2a2a; 
        border: 1px solid #444444; 
        color: #ffffff; 
    }
    .form-control::placeholder {
        color: #aaaaaa; 
    }
    .btn-primary {
        background-color: #1a73e8; 
        border: none;
    }
    .btn-primary:hover {
        background-color: #135ba1; 
    }
    .text-center a {
        color: #1a73e8;
    }
    .text-center a:hover {
        color: #135ba1; 
    }
</style>

</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <h2 class="text-center">Login</h2>
                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
                <div class="text-center">
                    <a href="register.php">Don't have an account? Register</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

