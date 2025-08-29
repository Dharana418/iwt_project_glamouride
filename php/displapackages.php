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
  <style>
.btn {
    display:inline-flex;      
    border: none;               
    border-radius: 5px;          
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    padding: 8px;
    text-decoration: none;
    font-weight: bolder;
    margin: 5px;                
}

.btn-danger {
    background-color: #dc3545;
    color: white;
    padding: 8px;
    text-decoration: none;
    font-weight: bolder;
    margin: 5px;
}
  </style>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>GlamourRide</title>
  <link rel="stylesheet" href="../CSS/displapackages.css" />
</head>
<body>
<header class="header">
    <div class="site-logo">
      <img src="../images/logo.webp" alt="GlamourRide Logo">
    </div>
    <h1>GlamourRide</h1>
    <ul class="horizontal-list">
      <li><a href="../php/displapackages.php">Bookings</a></li>
      <li><a href="../Html/Login.php">Logout</a></li>
    </ul>
</header>

<table class="content-table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Package Name</th>
      <th>Package Description</th>
      <th>Package Price per 1KM</th>
      <th>Image</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
<?php
$sql = "SELECT * FROM packages WHERE created_by = '".$_SESSION['email']."'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)){

        echo "<tr>";
        echo "<td>".$row['id']."</td>";
        echo "<td>".$row['title']."</td>";
        echo "<td>".$row['description']."</td>";
        echo "<td>".$row['price']."</td>";

        if (!empty($row['image'])) {
    echo "<td><img src='../php/uploads/".$row['image']."' class='package-image' width='100'></td>";
} else {
    echo "<td><span style='color:red;'>No image</span></td>";
}

        echo "<td>
                <a class='btn btn-primary' href='vehicleeditpackage.php?id=".$row['id']."'>Edit</a> | 
                <a class='btn btn-danger' href='vehicledeletepackage.php?id=".$row['id']."' onclick=\"return confirm('Are you sure you want to delete this package?');\">Delete</a>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='no-packages'>No packages found</td></tr>";
}
?>
  </tbody>
</table>

</body>
</html>
