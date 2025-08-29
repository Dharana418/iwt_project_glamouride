<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../Html/Login.php");
    exit();
}

$id = $_GET['id'];
$email = $_SESSION['email'];

// Get current feedback
$stmt = $conn->prepare("SELECT * FROM feedback WHERE id = ? AND user_email = ?");
$stmt->bind_param("is", $id, $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $update = $conn->prepare("UPDATE feedback SET message = ? WHERE id = ? AND user_email = ?");
        $update->bind_param("sis", $message, $id, $email);
        $update->execute();
        header("Location: view_feedback.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Feedback</title></head>
<body>
<h2>Edit Feedback</h2>
<form method="POST">
    <textarea name="message" rows="5" cols="50"><?= $row['message'] ?></textarea><br>
    <button type="submit">Update</button>
</form>
</body>
</html>
