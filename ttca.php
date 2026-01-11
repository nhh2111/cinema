<?php
session_start();
include("php/header.php");
include("php/db.php");

// Lấy unique_id của người dùng đã đăng nhập
$unique_id = $_SESSION['unique_id'] ?? null;

if (!$unique_id) {
    echo "<div style='margin-top:100px; color:red;'>Người dùng chưa đăng nhập</div>";
    exit;
}

$name = $phone = $email = $image = "";

// Xử lý khi biểu mẫu được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Xử lý ảnh
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = 'Images/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    } else {
        $image = $_POST['current_image'] ?? "";
    }

    // Tách họ và tên
    $lname = explode(" ", $name)[0];
    $fname = explode(" ", $name, 2)[1] ?? "";

    // Cập nhật thông tin người dùng
    $update_sql = "UPDATE users SET lname = '$lname', fname = '$fname', phone = '$phone', email = '$email'";
    if (!empty($image)) {
        $update_sql .= ", image = '$image'";
    }
    $update_sql .= " WHERE unique_id = $unique_id";

    if ($conn->query($update_sql) === TRUE) {
        echo "<div style='color: green;'>Cập nhật thông tin thành công!</div>";
    } else {
        echo "<div style='color: red;'>Lỗi khi cập nhật thông tin: " . $conn->error . "</div>";
    }
} else {
    $sql = "SELECT * FROM users WHERE unique_id = $unique_id";
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        $name = $row['lname'] . ' ' . $row['fname'];
        $phone = $row['phone'];
        $email = $row['email'];
        $image = $row['image'];
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TTND - Thông Tin Người Dùng</title>
    <link rel="stylesheet" href="./css/ttca.css">
</head>

<body style="margin-top:100px">
    <div class="info">
        <ul>
            <li><div style="color:white" id="btn-user-info">Thông tin người dùng</div></li>
            <li><div style="color:white" id="btn-history">Lịch sử đặt vé</div></li>
        </ul>
    </div>

    <div id="user-info-section" class="section active">
        <form method="post" action="" enctype="multipart/form-data">
            <div class="form-container">
                <div class="image-section">
                    <?php if (!empty($image)): ?>
                        <img src="<?php echo $image ?>" alt="Ảnh đại diện"
                            style="width: 150px; height: 200px; margin-bottom: 10px;"><br>
                    <?php endif; ?>
                    <label class="custom-upload" for="image">Chọn ảnh mới</label>
                    <input type="file" name="image" id="image" class="file-upload" accept="image/*">
                    <input type="hidden" name="current_image" value="<?php echo $image; ?>">
                </div>

                <div class="input-section">
                    <label>Họ Tên:</label><br>
                    <input type="text" name="name" placeholder="Họ Tên" required value="<?php echo $name ?>"><br><br>
                    <label>Số điện thoại:</label><br>
                    <input type="tel" name="phone" placeholder="Số điện thoại" required pattern="[0-9]{10}"
                        value="<?php echo $phone ?>"><br><br>
                    <label>Email:</label><br>
                    <input type="email" name="email" placeholder="abc@gmail.com" required
                        value="<?php echo $email ?>"><br><br>
                </div>

                <div class="submit-section">
                    <input type="submit" value="Cập nhật" class="btn-update">
                </div>
            </div>
        </form>
    </div>

    <!-- Lịch sử đặt vé -->
    <div id="history-section" class="section">
        <h2>Lịch sử đặt vé</h2>
        <table border="1" style="width: 80%; text-align: left;margin:auto">
            <tr>
                <th>Mã hóa đơn</th>
                <th>Phim</th>
                <th>Rạp chiếu</th>
                <th>Ghế đã đặt</th>
                <th>Tổng tiền đã thanh toán</th>
            </tr>
            <?php
            include('php/db.php');
            $unique_id = isset($_SESSION['unique_id']) ? $_SESSION['unique_id'] : null;
            if (!$unique_id) {
                exit;
            }
            $sql = "SELECT * FROM thongtinvedadat WHERE unique_id = '$unique_id'";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($result)) {
                ?>
                <tr>
                    <td><?php echo $row['ma_hoa_don']; ?></td>
                    <td><?php echo $row['phim']; ?></td>
                    <td><?php echo $row['rap_chieu']; ?></td>
                    <td><?php echo $row['ghe_da_dat']; ?></td>
                    <td><?php echo $row['tongTien']; ?></td>

                </tr>
            <?php } ?>
        </table>
    </div>

    <script>
        const userInfoSection = document.getElementById('user-info-section');
        const historySection = document.getElementById('history-section');
        const btnUserInfo = document.getElementById('btn-user-info');
        const btnHistory = document.getElementById('btn-history');

        btnUserInfo.addEventListener('click', () => {
            userInfoSection.classList.add('active');
            historySection.classList.remove('active');
        });

        btnHistory.addEventListener('click', () => {
            historySection.classList.add('active');
            userInfoSection.classList.remove('active');
        });
    </script>
</body>

</html>