<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['email'])) {
    header("Location: ../Html/Login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['email'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO feedback (user_email, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $message);
        $stmt->execute();
        $success = "Feedback submitted successfully!";
    } else {
        $error = "Please enter your feedback!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Feedback</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; }
        .container { width: 50%; margin: 50px auto; background: white; padding: 20px; border-radius: 10px; }
        textarea { width: 100%; height: 100px; margin: 10px 0; }
        button { padding: 10px 20px; background: black; color: white; border: none; cursor: pointer; }
        button:hover { background: darkgreen; }
        .msg { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
<div class="container">
    <h2>Give Your Feedback</h2>
    <?php if (isset($success)) echo "<p class='msg'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <label>Your Feedback:</label>
        <textarea name="message"></textarea>
        <button type="submit">Submit</button>
    </form>
    <br>
    <a href="view_feedback.php">View My Feedback</a>
</div>
</body>
</html>
