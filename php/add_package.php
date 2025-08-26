<?php
session_start();
include 'connect.php';
if(isset($_POST['add-package'])){
    $title=$_POST['title'];
    $description=$_POST['description'];
    $price=$_POST['price'];
    $createdby=$_SESSION['email'];

    $packageCheck = "SELECT * FROM packages WHERE title='$title' AND description='$description' AND price='$price'";
    $checkResult = mysqli_query($conn, $packageCheck);
    if(mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('This package is already registered!');</script>";
        exit;
    }
    $folder = '../php/uploads/';
    $image_file = $_FILES['image']['name']; 
    $file = $_FILES['image']['tmp_name'];     
    $target_file = $folder . basename($image_file);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($imageFileType != "jpg" && $imageFileType != "png" && 
        $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed');</script>";
        exit;
    }
    if ($_FILES['image']['size'] > 1048576) {
        echo "<script>alert('Image is too large. Upload less than 1 MB');</script>";
        exit;
    }
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }
    if (move_uploaded_file($file, $target_file)) {
        $sql = "INSERT INTO packages (title, description, price, image, created_by) 
                VALUES ('$title', '$description', '$price', '$image_file', '$createdby')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                alert('Package added successfully');
                window.location.href = '../Html/ManagerDashboard.php';
            </script>";
        } else {
            echo "<script>alert('Database Error: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Error uploading image');</script>";
    }
}
?>
