<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title'], ENT_QUOTES);
    $user_id = $_SESSION['user_id'];

    $stmt = $mysqli->prepare("INSERT INTO todos (user_id, title) VALUES (?, ?)");
    $stmt->bind_param('is', $user_id, $title);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$stmt = $mysqli->prepare("SELECT id, title FROM todos WHERE user_id = ?");
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($todo_id, $title);
$todos = [];

while ($stmt->fetch()) {
    $todos[] = ['id' => $todo_id, 'title' => $title];
}

$stmt->close();
$mysqli->close();
?>

<h2>Create New To-Do List</h2>
<form method="POST" action="todo.php">
    <input type="text" name="title" placeholder="List Title" required>
    <button type="submit">Create</button>
</form>

<h2>Your To-Do Lists</h2>
<ul>
    <?php foreach ($todos as $todo): ?>
    <li><?php echo htmlspecialchars($todo['title'], ENT_QUOTES); ?> 
        <a href="delete_todo.php?id=<?php echo $todo['id']; ?>">Delete</a>
    </li>
    <?php endforeach; ?>
</ul>
