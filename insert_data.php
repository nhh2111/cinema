<?php
// Include file db.php để kết nối cơ sở dữ liệu
include('php/db.php');

// Lấy dữ liệu từ yêu cầu POST
$data = json_decode(file_get_contents('php://input'), true);

$ma_hoa_don =  $data['ma_hoa_don'];
$phim =  $data['phim'];
$rap_chieu =  $data['rap_chieu'];
$ghe_da_dat =  $data['ghe_da_dat'];
$tongTien =  $data['tongTien'];
$unique_id =  $data['unique_id'];

// Tạo câu truy vấn SQL
$sql = "INSERT INTO thongtinvedadat (ma_hoa_don, phim, rap_chieu, ghe_da_dat, tongTien, unique_id) 
        VALUES ('$ma_hoa_don', '$phim', '$rap_chieu', '$ghe_da_dat', '$tongTien', '$unique_id')";

// Thực thi câu truy vấn
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $conn->error]);
}

// Đóng kết nối
$conn->close();
?>
