<?php
include("php/db.php");

// Kiểm tra phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_combo = isset($_POST['ten_combo']) ?  $_POST['ten_combo'] : '';
    $gia_combo = isset($_POST['gia_combo']) ?  $_POST['gia_combo'] : '';
    $mota = isset($_POST['mota']) ?  $_POST['mota'] : '';
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $image = '';

    // Xử lý upload file nếu có
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "./Images/";
        $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $uniqueName = uniqid('combo_', true) . '.' . $fileExt;
        $targetFile = $targetDir . $uniqueName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $targetFile;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không thể tải ảnh lên.']);
            exit;
        }
    } elseif ($id > 0) {
        // Lấy ảnh cũ nếu không upload ảnh mới
        $sql = "SELECT image FROM combo WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $image = $row['image'];
        }
    }

    if (!$image && $id > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy ảnh cũ.']);
        exit;
    }

    // Kiểm tra thêm mới hay cập nhật
    if ($id > 0) {
        // Cập nhật combo
        $sql = "UPDATE combo 
                SET ten_combo = '$ten_combo', 
                    gia_combo = '$gia_combo', 
                    mota = '$mota', 
                    image = '$image'
                WHERE id = $id";
        $message = "Cập nhật thành công!";
    } else {
        // Thêm mới combo
        $sql = "INSERT INTO combo (ten_combo, gia_combo, mota, image) 
                VALUES ('$ten_combo', '$gia_combo', '$mota', '$image')";
        $message = "Thêm mới thành công!";
    }


    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'message' => $message]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Lỗi SQL: ' . mysqli_error($conn),
            'query' => $sql
        ]);
    }
}

// Đóng kết nối
mysqli_close($conn);
?>
