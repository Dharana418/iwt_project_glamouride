<?php
include '../php/connect.php';
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../Html/Login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid package ID.");
}

$sql = "SELECT * FROM packages WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Package not found.");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buy Now</title>
    <link rel="stylesheet" href="../CSS/customerbuynow.css">
</head>
<body>
    <div class="buy-container">
        <h1>Buy Now</h1>

        <form action="../php/customerpackageinsert.php" method="POST" class="buy-form">
            <input type="hidden" name="package_id" value="<?php echo htmlspecialchars($product['id']); ?>">
            <input type="hidden" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">

            <div class="product-card">
                <img src="../php/uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['title']); ?>">

                <h2><?php echo htmlspecialchars($product['title']); ?></h2>
                <p class="price">Price per 1km: Rs. <?php echo number_format($product['price'], 2); ?></p>

                <label>Distance you expect to travel:</label>
                <input type="number" id="distance" name="distance" required>

                <p class="total-price">Total Price: <span id="total-price"></span></p>
                <button type="button" id="calculate-btn">Calculate</button>
            </div>

            <button type="submit" class="buy-btn" name="submit">Buy Now</button>
        </form>
    </div>

    <script>
        var pricePerKm = <?php echo $product['price']; ?>;
    </script>
    <script src="../JS/customerpricecalculate.js"></script>
</body>
</html>
