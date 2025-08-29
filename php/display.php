<?php
session_start();
include 'connect.php';
$admin_name = $_SESSION['email'];

$sql = "SELECT * FROM users WHERE admin_name='$admin_name'";
$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Display Users</title>
  <link rel="stylesheet" href="../CSS/Displayuser.css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
</head>
<body>
  <div class="navbar">
    <ul>
      <li><a href="../Html/AdminAddpage.php"><span class="material-icons">person_add</span>Add a new user</a></li>
      <li><a href="../php/profile.php"><span class="material-icons profile-icon">account_circle</span>Profile</a></li>
      <li><a href="../php/display.php"><span class="material-icons">groups</span>Registered Users</a></li>
      <li><a href="../php/logout.php"><span class="material-icons">logout</span>Log out</a></li>
    </ul>
  </div>
    <table class="content-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Firstname</th>
          <th>Lastname</th>
          <th>Email</th>
          <th>Password</th>
          <th>Birthday</th>
          <th>Address</th>
          <th>Profile Photo</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['firstname']}</td>
                      <td>{$row['lastname']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['password']}</td>
                      <td>{$row['birthday']}</td>
                      <td>{$row['Address']}</td>
                      <td>";
              if (!empty($row['profile_photo'])) {
                  echo "<img src='../php/uploads/{$row['profile_photo']}' width='50' height='50' border-radius='50%' alt='Profile Photo' class='profile-photo'>";
              } else {
                  echo "No photo";
              }
              echo "</td>
                    <td>
                      <a class='btn btn-primary' href='userupdate.php?id={$row['id']}'>Update</a>
                      <a class='btn btn-danger' href='userdelete.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>
                    </td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='10'>No users found</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
