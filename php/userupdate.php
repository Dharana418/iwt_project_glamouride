<?php
include 'connect.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows == 0){
    die("No user found with this ID.");
}
$row = $result->fetch_assoc();

if(isset($_POST['update'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $birthday = $_POST['birthday'];
    $address = $_POST['address'];

   
    $profile_photo = $row['profile_photo']; 
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){
        $profile_photo = time() . "_" . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], "../php/uploads/" . $profile_photo);
    }
    $update_stmt = $conn->prepare("UPDATE users SET firstname=?, lastname=?, email=?, password=?, birthday=?, Address=?, profile_photo=? WHERE id=?");
    $update_stmt->bind_param("sssssssi", $firstname, $lastname, $email, $password, $birthday, $address, $profile_photo, $id);

    if($update_stmt->execute()){
        header("Location: display.php");
        exit();
    } else {
        echo "Error updating record: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update User</title>
<link rel="stylesheet" href="../CSS/userupdate.css"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
</head>
<body>
<div class="navbar">
    <ul>
        <li><a href="../Html/AdminAddpage.php"><span class="material-icons">person_add</span> Add a new user</a></li>
        <li><a href="../php/profile.php"><span class="material-icons profile-icon">account_circle</span> Profile</a></li>
        <li><a href="../php/display.php"><span class="material-icons">groups</span> Registered Users</a></li>
        <li><a href="../php/logout.php"><span class="material-icons">logout</span> Log out</a></li>
    </ul>
</div>
<div class="form">
    <h2>Update User Information</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="firstname">First Name:</label>
        <input type="text" name="firstname" value="<?php echo htmlspecialchars($row['firstname']); ?>" required>

        <label for="lastname">Last Name:</label>
        <input type="text" name="lastname" value="<?php echo htmlspecialchars($row['lastname']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>

        <label for="password">Password:</label>
        <input type="password" name="password" placeholder="Enter new password" required>

        <label for="birthday">Birthday:</label>
        <input type="date" name="birthday" value="<?php echo htmlspecialchars($row['birthday']); ?>" required>

        <label for="address">Address:</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($row['Address']); ?>" required>

        <label for="photo">Profile Image:</label>
        <?php if(!empty($row['profile_photo'])): ?>
            <img src="../uploads/<?php echo $row['profile_photo']; ?>" alt="Profile Image" width="100" style="display:block;margin-bottom:10px;">
        <?php endif; ?>
        <input type="file" name="photo">

        <button type="submit" name="update">Update User</button>
    </form>
</div>

</body>
</html>
