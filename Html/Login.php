<!DOCTYPE html>
<html lang="en">                                  
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Login.css">
</head>
<body>
    <div class="login-form">
        <h1>Login</h1>
        <form action="../php/Login.php" method="POST" id="login-form">
            <input type="email" id="email" name="email" placeholder="Enter your email" autocomplete="email" required>
            <div class="password-container">
            <input type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
            </div>
            <button type="submit" name="login-button" id="login-button">Login</button>
            <br><br>
            <p>Don't have an account? <a href="./Registration.php">Register</a></p>
        </form> 
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../JS/login.js"></script>
</body>
</html>
