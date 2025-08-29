<?php
session_start();
include '../php/connect.php';
if (!isset($_SESSION['email'])) {
    header("Location: ../Html/Login.php");
    exit();
}
$sql = "
    SELECT r.id, r.user_email, r.packagename, r.distance, r.priceperkm, r.total_price, r.created_at,
           p.title AS package_title, p.image
    FROM registrations r
    LEFT JOIN packages p ON r.package_id = p.id
    ORDER BY r.created_at DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Dashboard - User Registrations</title>
    <link rel="stylesheet" href="../CSS/Managerdashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fa;
            margin: 0;
            padding: 0;
            background:url(../images/3428144_60241.jpg);
        }
        .dashboard-container {
            width: 90%;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            font-weight: bolder;
        }
        th {
            background: #000000ff;
            color: #fff;
        }
        img {
            width: 80px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>User Registered Packages</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Email</th>
                        <th>Package</th>
                        <th>Image</th>
                        <th>Distance (km)</th>
                        <th>Price per km (Rs.)</th>
                        <th>Total Price (Rs.)</th>
                        <th>Registered On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['user_email']); ?></td>
                            <td><?php echo htmlspecialchars($row['packagename']); ?></td>
                            <td>
                                <?php if (!empty($row['image'])): ?>
                                    <img src="../php/uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Package Image">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['distance']; ?></td>
                            <td><?php echo number_format($row['priceperkm'], 2); ?></td>
                            <td><?php echo number_format($row['total_price'], 2); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center; color:red;">No user has registered any package yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
