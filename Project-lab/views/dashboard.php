<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

include '../config/database.php';

$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT id, title FROM todo_lists WHERE user_id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($todo_id, $title);
$todos = [];

while ($stmt->fetch()) {
    $todos[] = ['id' => $todo_id, 'title' => $title];
}

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
        }
        .container {
            margin-top: 50px;
        }
        .list-group-item {
            background-color: #1e1e1e;
            border: none;
        }
        .list-group-item a {
            color: #ffffff;
        }
        .list-group-item a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <h2>Your To-Do Lists</h2>
    <ul class="list-group">
        <?php if (empty($todos)): ?>
            <li class="list-group-item text-center">No to-do lists available. <a href="create_todo.php" class="btn btn-primary">Create New To-Do List</a></li>
        <?php else: ?>
            <?php foreach ($todos as $todo): ?>
            <li class="list-group-item">
                <a href="view_tasks.php?id=<?php echo $todo['id']; ?>"><?php echo htmlspecialchars($todo['title'], ENT_QUOTES); ?></a>
                <a href="delete_todo.php?id=<?php echo $todo['id']; ?>" class="btn btn-danger btn-sm float-right" onclick="return confirm('Are you sure you want to delete this list?');">Delete</a>
            </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    <div class="mt-3">
        <a href="create_todo.php" class="btn btn-primary">Create New To-Do List</a>
        <a href="profile.php" class="btn btn-secondary">View Profile</a>
        <a href="../logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

</body>
</html>
