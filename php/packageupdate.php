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
    $total_distance = $_POST['total_distance'];
    $total_price = $total_distance * $registration['priceperkm']; 

    $update_stmt = $conn->prepare("UPDATE registrations SET distance=?, total_price=? WHERE id=?");
    $update_stmt->bind_param("ddi", $total_distance, $total_price, $id);

    if ($update_stmt->execute()) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: 'Your order has been updated successfully.',
            confirmButtonColor: '#28a745'
        }).then(() => {
            window.location.href='customerreaddetails.php';
        });
        </script>";
        exit();
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Could not update order: " . $update_stmt->error . "',
            confirmButtonColor: '#d33'
        });
        </script>";
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
            <label for="distance">Total Distance (km):</label>
            <input type="number" name="total_distance" id="distance" value="<?php echo htmlspecialchars($registration['distance']); ?>" min="1" required>

            <label for="total_price">Total Price (Rs.):</label>
            <input type="number" name="total_price" id="total_price" value="<?php echo htmlspecialchars($registration['total_price']); ?>" readonly>

            <button type="submit" name="update">Update</button>
        </form>
    </div>

    <script>
        const distanceInput = document.getElementById('distance');
        const priceInput = document.getElementById('total_price');
        const pricePerKm = <?php echo $registration['priceperkm']; ?>;

        distanceInput.addEventListener('input', () => {
            const distance = parseFloat(distanceInput.value);
            if (!isNaN(distance) && distance > 0) {
                priceInput.value = (distance * pricePerKm).toFixed(2);
            } else {
                priceInput.value = 0;
            }
        });
    </script>
</body>
</html>
