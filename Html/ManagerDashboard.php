<?php
session_start();
    if(!isset($_SESSION['email'])) {
        header("Location: ../Html/Login.php");
        exit();
    }
    include '../php/connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GlamourRide - Packages</title>
<link rel="stylesheet" href="../CSS/Managerdashboard.css">
<script src="../JS/ManagerDashboard.js"></script>
</head>
<body>
<header>
    <h1>GlamourRide</h1>
    <nav>
        <a href="../php/displapackages.php">Bookings</a>
        <a href="../php/customerregistredpackages.php">Registered Analytics</a>
        <a href="../Html/Login.php">Logout</a>
    </nav>
</header>

<div class="container">
    <form action="../php/add_package.php" method="POST" enctype="multipart/form-data">
        <label for="title">Package Name:</label><br>
        <input type="text" name="title" id="title" placeholder="Package Title" required><br>

        <label for="description">Package Description:</label><br>
        <textarea name="description" id="description" placeholder="Description" required></textarea><br>

        <label for="price">Package Price:</label><br>
        <input type="number" name="price" id="price" step="0.01" placeholder="Price" required><br>

        <label for="image">Upload Image:</label><br>
        <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)" required><br>

        <img src="" alt="Preview" id="imagePreview" style="max-width:200px; display:none;"><br>

        <button type="submit" name="add-package">Add Package</button>
    </form>
</div>
