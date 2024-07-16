<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['new_task'])) {
        $task = [
            'task' => $_POST['new_task'],
            'status' => 'incomplete',
            'created_at' => date('Y-m-d H:i:s'),
            'completed_at' => null,
            'duration' => null
        ];
        $_SESSION['tasks'][] = $task;
    } elseif (isset($_POST['delete_task'])) {
        unset($_SESSION['tasks'][$_POST['delete_task']]);
        $_SESSION['tasks'] = array_values($_SESSION['tasks']);
    } elseif (isset($_POST['update_task'])) {
        $_SESSION['tasks'][$_POST['task_id']]['task'] = $_POST['updated_task'];
    } elseif (isset($_POST['complete_task'])) {
        $task_id = $_POST['task_id'];
        $completed_at = date('Y-m-d H:i:s');
        $_SESSION['tasks'][$task_id]['status'] = 'complete';
        $_SESSION['tasks'][$task_id]['completed_at'] = $completed_at;

        $created_at = new DateTime($_SESSION['tasks'][$task_id]['created_at']);
        $completed_at_dt = new DateTime($completed_at);
        $duration = $created_at->diff($completed_at_dt);
        $_SESSION['tasks'][$task_id]['duration'] = $duration->format('%d days %h hours %i minutes %s seconds');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Hero Academia To-do App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="bg-light py-3">
        <div class="container">
            <h1 class="text-center">My Hero Academia To-do App</h1>
        </div>
    </header>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-body">
                <h3 class="card-title text-center">PHP - Simple To Do List App</h3>
                <form method="POST" class="form-inline justify-content-center mb-3">
                    <input type="text" name="new_task" class="form-control mr-2" placeholder="New Task">
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Task</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['tasks'] as $index => $task) : ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($task['task']) ?> - Added on: <?= $task['created_at'] ?>
                                    <?php if ($task['status'] == 'complete'): ?>
                                        <br> Completed on: <?= $task['completed_at'] ?>
                                        <br> Duration: <?= $task['duration'] ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= $task['status'] ?></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="delete_task" value="<?= $index ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="task_id" value="<?= $index ?>">
                                        <input type="text" name="updated_task" value="<?= htmlspecialchars($task['task']) ?>" class="form-control d-inline-block" style="width: auto;">
                                        <button type="submit" name="update_task" class="btn btn-success btn-sm">Update</button>
                                    </form>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="task_id" value="<?= $index ?>">
                                        <button type="submit" name="complete_task" class="btn btn-warning btn-sm">Complete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
