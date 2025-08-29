<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../Html/Login.php");
    exit();
}

$id = $_GET['id'];
$email = $_SESSION['email'];

$stmt = $conn->prepare("DELETE FROM feedback WHERE id = ? AND user_email = ?");
$stmt->bind_param("is", $id, $email);
$stmt->execute();

header("Location: view_feedback.php");
exit();
