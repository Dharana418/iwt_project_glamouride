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
$id = (int) $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM packages WHERE id = ? AND created_by = ?");
$stmt->bind_param("is", $id, $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();

if (!$package) {
    die("Package not found or you don’t have permission.");
}
if (isset($_POST['update'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $image = $package['image']; 
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = "../php/uploads/";
        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            if (!empty($package['image']) && file_exists($uploadDir . $package['image'])) {
                unlink($uploadDir . $package['image']);
            }
            $image = $fileName;
        }
    }
    $update_stmt = $conn->prepare("UPDATE packages SET title = ?, description = ?, price = ?, image = ? WHERE id = ? AND created_by = ?");
    $update_stmt->bind_param("ssdsis", $title, $description, $price, $image, $id, $_SESSION['email']);

    if ($update_stmt->execute()) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: 'Package has been updated successfully.',
            confirmButtonColor: '#28a745'
        }).then(() => {
            window.location.href='displapackages.php';
        });
        </script>";
        exit();
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Failed to update package. Please try again.',
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
  <title>Edit Package</title>
  <link rel="stylesheet" href="../CSS/customerbuynow.css">
  <style>
    .edit-container {
        width: 400px;
        margin: 40px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: #f9f9f9;
    }
    .edit-container h1 {
        text-align: center;
        margin-bottom: 20px;
    }
    .edit-container input, .edit-container textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
    }
    .edit-container button {
        width: 100%;
        padding: 10px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        font-weight: bold;
    }
    .current-image {
        display: block;
        margin: 10px 0;
        max-width: 100%;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
  </style>
</head>
<body>
<div class="edit-container">
    <h1>Edit Package</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Package Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($package['title']); ?>" required>

        <label>Description:</label>
        <textarea name="description" rows="4" required><?php echo htmlspecialchars($package['description']); ?></textarea>

        <label>Price per 1KM:</label>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($package['price']); ?>" required>

        <label>Current Image:</label>
        <?php if (!empty($package['image'])): ?>
            <img src="../php/uploads/<?php echo htmlspecialchars($package['image']); ?>" class="current-image">
        <?php else: ?>
            <p style="color:red;">No image uploaded</p>
        <?php endif; ?>

        <label>Upload New Image (optional):</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit" name="update">Update Package</button>
    </form>
</div>
</body>
</html>
