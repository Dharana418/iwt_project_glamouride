<?php
session_start();
include '../php/connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../Html/Login.php");
    exit();
}

$userEmail = $_SESSION['email'];
$sql = "SELECT r.id, p.title AS package_name, r.distance, r.total_price, r.created_at
        FROM registrations r
        INNER JOIN packages p ON r.package_id = p.id
        WHERE r.user_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders</title>
  <link rel="stylesheet" href="../CSS/customerdisplaydetails.css"/>
</head>
<body>
  <table class="content-table">
    <thead>
      <tr>
        <th>Package Name</th>
        <th>Total Distance</th>
        <th>Total Price</th>
        <th>Ordered At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['package_name']) . "</td>";
              echo "<td>" . htmlspecialchars($row['distance']) . " km</td>";
              echo "<td>Rs. " . htmlspecialchars($row['total_price']) . "</td>";
              echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
              echo "<td>
                      <a class='btn btn-primary' href='packageupdate.php?id={$row['id']}'>Update</a>
                      <a class='btn btn-danger' href='packagedelete.php?id={$row['id']}' onclick=\"return confirm('Are you sure?');\">Delete</a>
                    </td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='5'>No orders found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</body>
</html>

