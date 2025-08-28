<?php
session_start();
include '../php/connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../Html/Login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request.");
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM registrations WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$registration = $result->fetch_assoc();

if (!$registration) {
    die("Record not found.");
}
if (isset($_POST['update'])) {
    $package_id = $_POST['package_id'];
    $total_distance = $_POST['total_distance'];
    $total_price = $_POST['total_price'];

    $update_stmt = $conn->prepare("UPDATE registrations SET package_id=?, total_distance=?, total_price=? WHERE id=?");
    $update_stmt->bind_param("iddi", $package_id, $total_distance, $total_price, $id);

    if ($update_stmt->execute()) {
        header("Location: customerreaddetails.php");
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
    <title>Update Package</title>
    <link rel="stylesheet" href="../CSS/customerupdate.css">
</head>
<body>
    <div class="update-container">
        <h1>Update Package</h1>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $registration['id']; ?>">

            <label for="package_id">Package ID:</label>
            <input type="text" name="package_id" value="<?php echo htmlspecialchars($registration['package_id']); ?>" required>

            <label for="total_distance">Total Distance:</label>
            <input type="number" name="total_distance" value="<?php echo htmlspecialchars($registration['total_distance']); ?>" required>

            <label for="total_price">Total Price:</label>
            <input type="number" step="0.01" name="total_price" value="<?php echo htmlspecialchars($registration['total_price']); ?>" required>

            <button type="submit" name="update">Update</button>
        </form>
    </div>
</body>
</html>
