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
$product = $result->fetch_assoc();

if (!$product) {
    die("Package not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buy Now</title>
    <link rel="stylesheet" href="../CSS/customerbuynow.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="buy-container">
    <h1>Buy Now</h1>

    <form id="buyForm" action="" method="POST" class="buy-form">
        <div class="product-card">
            <img src="../php/uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                 alt="<?php echo htmlspecialchars($product['title']); ?>">

            <h2><?php echo htmlspecialchars($product['title']); ?></h2>
            <p class="price">Price per 1 km: Rs. <?php echo number_format($product['price'], 2); ?></p>

            <label>Distance you expect to travel:</label>
            <input type="number" id="distance" name="distance" required min="1">

            <p class="total-price">Total Price: <span id="total-price"></span></p>
            <button type="button" id="calculate-btn">Calculate</button>
        </div>

        <button type="submit" name="buy" id="submitBtn" class="buy-btn">Submit</button>
    </form>
</div>

<script>
    var pricePerKm = <?php echo $product['price']; ?>;
    document.getElementById('calculate-btn').addEventListener('click', function() {
        var distance = document.getElementById('distance').value;
        if(distance && distance > 0){
            var total = distance * pricePerKm;
            document.getElementById('total-price').textContent = "Rs. " + total.toFixed(2);
        } else {
            document.getElementById('total-price').textContent = "Enter a valid distance!";
        }
    });
    document.getElementById('buyForm').addEventListener('submit', function(e){
        e.preventDefault();
        var distance = document.getElementById('distance').value;
        if(distance && distance > 0){
            Swal.fire({
                title: 'Confirm Order',
                text: `Are you sure you want to order this package for ${distance} km?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, order it!'
            }).then((result) => {
                if(result.isConfirmed){
                    this.submit();
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Distance',
                text: 'Please enter a valid distance before submitting.'
            });
        }
    });
</script>
</body>
</html>

<?php
if (isset($_POST['buy'])) {
    $distance = $_POST['distance'];
    $total_price = $distance * $product['price'];
    $user_email = $_SESSION['email'];
    $package_id = $product['id'];
    $packagename = $product['title'];
    $priceperkm = $product['price'];
    $check_stmt = $conn->prepare("SELECT * FROM registrations WHERE user_email = ? AND package_id = ?");
    $check_stmt->bind_param("si", $user_email, $package_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'Already Ordered!',
            text: 'You have already ordered this package.',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location.href='../Html/MyOrders.php';
        });
        </script>";
    } else {
        $insert_stmt = $conn->prepare("INSERT INTO registrations 
            (user_email, package_id, packagename, distance, priceperkm, total_price) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("sisidd", $user_email, $package_id, $packagename, $distance, $priceperkm, $total_price);

        if ($insert_stmt->execute()) {
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Order Successful!',
                text: 'Your order has been placed successfully.',
                confirmButtonColor: '#28a745'
            }).then(() => {
                window.location.href='../Html/MyOrders.php';
            });
            </script>";
        } else {
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Could not place order. Please try again later.',
                confirmButtonColor: '#d33'
            });
            </script>";
        }
    }
}
?>
