<?php
session_start();
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['new_task'])) {
        $_SESSION['tasks'][] = $_POST['new_task'];
    } elseif (isset($_POST['delete_task'])) {
        unset($_SESSION['tasks'][$_POST['delete_task']]);
        $_SESSION['tasks'] = array_values($_SESSION['tasks']);
    } elseif (isset($_POST['update_task'])) {
        $_SESSION['tasks'][$_POST['task_id']] = $_POST['updated_task'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Hero Academia To-do App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.html">
            <img src="https://m.media-amazon.com/images/S/pv-target-images/a99cf6b62bb5f9255eb7c04062a7fb2109746decfb2fb206f949cf5dd48df2e8.jpg" alt="My Hero Academia">
        </a>
        <h1>My Hero Academia To-do App</h1>
    </header>
    <main>
        <form method="POST">
            <input type="text" name="new_task" placeholder="New Task">
            <button type="submit">Add Task</button>
        </form>
        <ul>
            <?php foreach ($_SESSION['tasks'] as $index => $task) : ?>
                <li>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="delete_task" value="<?= $index ?>">
                        <button type="submit">Delete</button>
                    </form>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="task_id" value="<?= $index ?>">
                        <input type="text" name="updated_task" value="<?= htmlspecialchars($task) ?>">
                        <button type="submit" name="update_task">Update</button>
                    </form>
                    <?= htmlspecialchars($task) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>
