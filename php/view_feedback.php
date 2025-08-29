<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../Html/Login.php");
    exit();
}

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT * FROM feedback WHERE user_email = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Feedback</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; }
        .container { width: 60%; margin: 30px auto; background: white; padding: 20px; border-radius: 10px; }
        .feedback { padding: 15px; margin: 10px 0; border-bottom: 1px solid #ddd; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>
<div class="container">
    <h2>My Feedback</h2>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="feedback">
            <p><strong><?= $row['message'] ?></strong></p>
            <small>Posted on: <?= $row['created_at'] ?></small><br>
            <a href="edit_feedback.php?id=<?= $row['id'] ?>">Edit</a> | 
            <a href="delete_feedback.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>
