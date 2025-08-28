


<?php
include 'connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "DELETE FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: display.php?msg=deleted");
        exit();
    } else {
        die("Error deleting record: " . mysqli_error($conn));
    }
}
?>
