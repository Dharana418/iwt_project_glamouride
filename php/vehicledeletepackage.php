<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../Html/Login.php");
    exit();
}

include '../php/connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid package ID.");
}

$id = (int)$_GET['id'];

// Get image file name first
$stmt = $conn->prepare("SELECT image FROM packages WHERE id = ? AND created_by = ?");
$stmt->bind_param("is", $id, $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();

if (!$package) {
    die("Package not found or you don’t have permission.");
}

// ✅ Check if any registrations exist
$check_stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM registrations WHERE package_id = ?");
$check_stmt->bind_param("i", $id);
$check_stmt->execute();
$check_result = $check_stmt->get_result()->fetch_assoc();

if ($check_result['cnt'] > 0) {
    echo "<script>
        alert('❌ Cannot delete: This package is already booked by customers.');
        window.location.href='displapackages.php';
    </script>";
    exit();
}

// ✅ If no registrations, delete package
$del_stmt = $conn->prepare("DELETE FROM packages WHERE id = ? AND created_by = ?");
$del_stmt->bind_param("is", $id, $_SESSION['email']);

if ($del_stmt->execute()) {
    // Optional: delete uploaded image file too
    if (!empty($package['image'])) {
        $filePath = "../php/uploads/" . $package['image'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    echo "<script>
    alert('✅ Package deleted successfully!');
    window.location.href='displapackages.php';
    </script>";
} else {
    echo "<script>
    alert('⚠️ Error deleting package. Please try again.');
    window.location.href='displapackages.php';
    </script>";
}
?>
