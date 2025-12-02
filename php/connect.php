<?php
$host = "sql103.infinityfree.com"; 
$user = "if0_345678";              
$pass = "Ppgqk22j7LHZ";            
$db   = "if0_345678_users";         

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
