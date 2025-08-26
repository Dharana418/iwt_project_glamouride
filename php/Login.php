<?php
require 'connect.php';
session_start();

header('Content-Type: application/json');

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; 
    $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $email;
            if (strtolower($user['role']) === 'admin') {
                echo json_encode([
                    "status" => "success",
                    "message" => "Welcome Admin!",
                    "redirect" => "../Html/AdminAddpage.php"
                ]);
                exit();
            }

            if (strtolower($user['role']) === 'customer') {
                echo json_encode([
                    "status" => "success",
                    "message" => "Welcome Customer!",
                    "redirect" => "../php/customerdisplaydetails.php"
                ]);
                exit();
            }

            if (strtolower($user['role']) === 'manager') {
                echo json_encode([
                    "status" => "success",
                    "message" => "Welcome Manager!",
                    "redirect" => "../Html/ManagerDashboard.php"
                ]);
                exit();
            }

            echo json_encode([
                "status" => "error",
                "message" => "Unauthorized role."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid password."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No account found with that email."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Email and password required."
    ]);
}
?>
