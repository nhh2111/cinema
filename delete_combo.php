<?php
include("php/db.php");

// Kiểm tra phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        $sql = "DELETE FROM combo WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            echo json_encode(['status' => 'success', 'message' => 'Xóa thành công!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không thể xóa dữ liệu: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ.']);
    }
}

// Đóng kết nối
mysqli_close($conn);
?>
