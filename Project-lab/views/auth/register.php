<?php
include '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $checkEmail = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->bind_param('s', $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        echo "Email sudah digunakan, silakan gunakan email lain.";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $username, $email, $password);
        
        if ($stmt->execute()) {
            header("Location: login.php");
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $checkEmail->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
                <h2 class="text-center">Register</h2>
                <form method="POST" action="register.php">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </form>
                <div class="text-center">
                    <a href="login.php">Already have an account? Login</a>
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
