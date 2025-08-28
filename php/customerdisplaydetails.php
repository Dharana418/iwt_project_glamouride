<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../Html/Login.php");
    exit();
}
include '../php/connect.php';

$sql = "SELECT * FROM packages";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>GlamourRide</title>
  <link rel="stylesheet" href="../CSS/customerpackagedisplay.css" />
</head>
<body>
  <header class="header">
      <div class="site-logo">
        <img src="../images/logo.webp" alt="GlamourRide Logo">
      </div>
      <h1>GlamourRide</h1>
      <ul class="horizontal-list">
        <li><a href="../php/customerreaddetails.php">Bookings</a></li>
        <li><a href="../Html/Feedback.php">Feedback</a></li>
        <li><a href="../php/logout.php">Logout</a></li>
      </ul>
  </header>

  <div class="package-container">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="package-card">
        <div class="image">
          <img src="../php/uploads/<?php echo htmlspecialchars($row['image']); ?>" 
               alt="<?php echo htmlspecialchars($row['title']); ?>">
        </div>
        <h2><?php echo htmlspecialchars($row['title']); ?></h2>
        <h4>Description:</h4>
        <p><?php echo htmlspecialchars($row['description']); ?></p>
        <p>Price per 1KM: Rs. <?php echo htmlspecialchars($row['price']); ?></p>
        <button onclick="location.href='../php/customerbuynowpage.php?id=<?php echo $row['id']; ?>'">Buy now</button>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>
