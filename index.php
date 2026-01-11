<?php
session_start();
include_once './php/db.php';
include_once './php/header.php';

// Lấy thông tin người dùng từ session
$unique_id = $_SESSION['unique_id'] ?? null;

if ($unique_id) {
    // Thực hiện truy vấn SQL trực tiếp
    $query = "SELECT * FROM users WHERE unique_id = '$unique_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['verification_status'] = $row['verification_status'];

        if ($row['verification_status'] !== 'Verified') {
            header("Location: verify.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cinema.css">
</head>

<body style="margin-top:100px">
    <div class="pagetong">

        <div class="anhbia">
            <img style="width: 100%; height: 500px" src="/cinemaWeb1/Images/anhtop1.jpg">
        </div>

        <?php
        include_once 'phim.php';
        ?>
        <?php
        include_once 'php/footer.php';
        ?>

    </div>

</body>

</html>
