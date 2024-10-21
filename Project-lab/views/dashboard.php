<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

include '../config/database.php';

$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT id, title FROM todos WHERE user_id = ?");
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

<h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
<h2>Your To-Do Lists</h2>
<ul>
    <?php foreach ($todos as $todo): ?>
    <li><a href="todo.php?id=<?php echo $todo['id']; ?>"><?php echo htmlspecialchars($todo['title'], ENT_QUOTES); ?></a></li>
    <?php endforeach; ?>
</ul>
<a href="todo.php">Create New To-Do List</a>
<a href="profile.php">View Profile</a>
<a href="../logout.php">Logout</a>
