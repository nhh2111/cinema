<?php
include("php/db.php");

// Kiểm tra phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tinh_trang = isset($_POST['tinh_trang']) ? $_POST['tinh_trang'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $movie_type = isset($_POST['movie_type']) ? $_POST['movie_type'] : '';
    $duration = isset($_POST['duration']) ? $_POST['duration'] : '';
    $youtube_link = isset($_POST['youtube_link']) ? $_POST['youtube_link'] : '';
    $php_link = isset($_POST['php_link']) ? $_POST['php_link'] : '';
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $image = '';

    // Xử lý upload file nếu có
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "./Images/"; // Thư mục lưu ảnh
        $fileName = basename($_FILES['image']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $targetFile;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không thể tải ảnh lên.']);
            exit;
        }
    } elseif ($id > 0) {
        // Lấy ảnh cũ nếu không upload ảnh mới
        $sql = "SELECT image FROM movies WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $image = $row['image'];
        }
    }

    // Kiểm tra thêm mới hay cập nhật
    if ($id > 0) {
        // Cập nhật thông tin phim
        $sql = "UPDATE movies 
        SET tinh_trang = '$tinh_trang',
            name = '$name', 
            movie_type = '$movie_type', 
            duration = '$duration', 
            image = '$image', 
            youtube_link = '$youtube_link', 
            php_link = '$php_link' 
        WHERE id = $id";
        $message = "Cập nhật thành công!";
    } else {
        // Thêm mới phim
        $sql = "INSERT INTO movies (tinh_trang, name, movie_type, duration, image, youtube_link, php_link) 
        VALUES ('$tinh_trang','$name', '$movie_type', '$duration', '$image', '$youtube_link', '$php_link')";

        $message = "Thêm mới thành công!";
    }

    // Thực thi truy vấn
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'message' => $message]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi: ' . mysqli_error($conn)]);
    }
}

// Đóng kết nối
mysqli_close($conn);
?>