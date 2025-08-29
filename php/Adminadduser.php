<?php
session_start();
include 'connect.php';

if (isset($_POST['add-button'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $role = $_POST['role'];
    $userEmail = $_POST['userEmail'];
    $Password = $_POST['Password'];
    $birthday = $_POST['birthday'];
    $Address = $_POST['Address'];
    $admin_name = $_SESSION['email'];

    $emailCheck = "SELECT * FROM users WHERE email='$userEmail'";
    $checkResult = mysqli_query($conn, $emailCheck);
    if(mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('This email is already registered!');</script>";
        exit;
    }

    $folder = '../php/uploads/';
    $image_file = $_FILES['photo']['name'];
    $file = $_FILES['photo']['tmp_name'];
    $target_file = $folder . basename($image_file);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed');</script>";
        exit;
    }

    if ($_FILES['photo']['size'] > 1048576) {
        echo "<script>alert('Image is too large. Upload less than 1 MB');</script>";
        exit;
    }

    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    if (move_uploaded_file($file, $target_file)) {
        $sql = "INSERT INTO users (firstname, lastname, role, email, password, birthday, Address, profile_photo, admin_name) 
                VALUES ('$fname', '$lname', '$role', '$userEmail', '$Password', '$birthday', '$Address', '$image_file', '$admin_name')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                alert('Insertion is successful');
                window.location.href = '../Html/AdminAddpage.php';
            </script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Error uploading image');</script>";
    }
}
?>

