<?php
include '../php/connect.php';
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../Html/Login.php");
    exit();
}

$userEmail = $_SESSION['email'];
$sql = "SELECT p.title AS package_name, r.total_distance, r.total_price, r.registered_at
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
  <h1>Welcome, <?php echo htmlspecialchars($userEmail); ?>!</h1>
  <p>Here are your previously ordered packages:</p>

  <table border="1" cellpadding="8" cellspacing="0">
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
              echo "<td>" . htmlspecialchars($row['total_distance']) . " km</td>";
              echo "<td>Rs. " . htmlspecialchars($row['total_price']) . "</td>";
              echo "<td>" . htmlspecialchars($row['registered_at']) . "</td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='4'>No orders found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</body>
</html>
