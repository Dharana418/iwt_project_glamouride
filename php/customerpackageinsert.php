<?php
include '../php/connect.php';
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../Html/Login.php");
    exit();
}




?>