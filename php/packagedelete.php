<?php
session_start();
include '../php/connect.php';
if (!isset($_SESSION['email'])) {
    header("Location: ../Html/Login.php");
    exit();
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid package ID.");
}

$id = (int) $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();

if (!$package) {
    die("Package not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Package</title>
    <link rel="stylesheet" href="../CSS/customerbuynow.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="buy-container">
    <h1>Delete Package</h1>

    <div class="product-card">
        <img src="../php/uploads/<?php echo htmlspecialchars($package['image']); ?>" 
             alt="<?php echo htmlspecialchars($package['title']); ?>">
        <h2><?php echo htmlspecialchars($package['title']); ?></h2>
        <p class="price">Price per 1 km: Rs. <?php echo number_format($package['price'], 2); ?></p>

        <form id="deleteForm" method="POST">
            <button type="submit" name="delete" class="buy-btn" style="background:#d33;">Delete Package</button>
        </form>
    </div>
</div>

<script>
document.getElementById('deleteForm').addEventListener('submit', function(e){
    e.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "This package will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if(result.isConfirmed){
            this.submit();
        }
    });
});
</script>
</body>
</html>

<?php
if (isset($_POST['delete'])) {
    $del_stmt = $conn->prepare("DELETE FROM packages WHERE id = ?");
    $del_stmt->bind_param("i", $id);

    if ($del_stmt->execute()) {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'The package has been deleted successfully.',
            confirmButtonColor: '#28a745'
        }).then(() => {
            window.location.href='../Html/AdminPackages.php';
        });
        </script>";
    } else {
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Could not delete the package. Please try again later.',
            confirmButtonColor: '#d33'
        });
        </script>";
    }
}
?>
