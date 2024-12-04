<?php
session_start();
require 'db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $task = $_POST['task'];
    $due_time = $_POST['due_time'];
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, task, due_time) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $task, $due_time]);
    header("Location: dashboard.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_task'])) {
    $task_id = $_POST['task_id'];
    $new_task = $_POST['task'];
    $due_time = $_POST['due_time'];
    $stmt = $pdo->prepare("UPDATE tasks SET task = ?, due_time = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$new_task, $due_time, $task_id, $_SESSION['user_id']]);

    
    $_SESSION['success_message'] = 'Task updated successfully!';
    header("Location: dashboard.php");
    exit();
}


if (isset($_POST['toggle_task_completion'])) {
    $task_id = $_POST['task_id'];
    $completed = $_POST['completed'];
    $stmt = $pdo->prepare("UPDATE tasks SET completed = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$completed, $task_id, $_SESSION['user_id']]);
    header("Location: dashboard.php");
    exit();
}


if (isset($_GET['delete_task'])) {
    $task_id = $_GET['delete_task'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $_SESSION['user_id']]);
    header("Location: dashboard.php");
    exit();
}


$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .dashboard-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        .task-form input[type="text"], .task-form input[type="datetime-local"] {
            padding: 12px;
            margin: 8px 0;
            width: 100%;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 16px;
        }
        .task-form button {
            padding: 12px 25px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .task-form button:hover {
            background-color: #45a049;
        }
        .task-list {
            margin-top: 30px;
            text-align: left;
        }
        .task-item {
            background: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .task-item.completed {
            background-color: #d4edda;
        }
        .task-item .task-details {
            flex: 1;
        }
        .task-item button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .task-item button:hover {
            background-color: #c82333;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
        .notification {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        
        .task-update-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }
        .task-details-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .task-details-container input[type="text"],
        .task-details-container input[type="datetime-local"] {
            width: 70%;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-container">
            <h1>Your Task Dashboard</h1>

            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="notification">
                    <?php echo $_SESSION['success_message']; ?>
                </div>
                <?php unset($_SESSION['success_message']); // Clear the message ?>
            <?php endif; ?>

            <!-- Task creation form -->
            <div class="task-form">
                <form method="POST">
                    <input type="text" name="task" required placeholder="Enter task...">
                    <input type="datetime-local" name="due_time">
                    <button type="submit" name="add_task">Add Task</button>
                </form>
            </div>

            <!-- Task list -->
            <div class="task-list">
                <h2>Your Tasks</h2>
                <?php foreach ($tasks as $task): ?>
                    <div class="task-item <?php echo $task['completed'] ? 'completed' : ''; ?>">
                        <div class="task-details">
                            <!-- Task completion toggle -->
                            <form method="POST" style="display: inline-block;">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <input type="hidden" name="completed" value="<?php echo $task['completed'] ? 0 : 1; ?>">
                                <button type="submit" name="toggle_task_completion">
                                    <?php echo $task['completed'] ? 'Mark as Incomplete' : 'Mark as Complete'; ?>
                                </button>
                            </form>
                        </div>

                        <!-- Task update box -->
                        <div class="task-update-box">
                            <div class="task-details-container">
                                <!-- Task update form -->
                                <form method="POST" style="display: flex; align-items: center;">
                                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                    <input type="text" name="task" value="<?php echo htmlspecialchars($task['task']); ?>" required>
                                    <input type="datetime-local" name="due_time" value="<?php echo date('Y-m-d\TH:i', strtotime($task['due_time'])); ?>">
                                    <button type="submit" name="update_task">Update</button>
                                </form>
                            </div>
                        </div>

                        <!-- Delete task -->
                        <a href="?delete_task=<?php echo $task['id']; ?>" onclick="return confirm('Are you sure you want to delete this task?')">
                            <button>Delete</button>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            
            <div class="footer">
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
