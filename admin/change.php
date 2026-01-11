<?php
include("../php/db.php");
//Thêm, sửa thông tin rạp chiếu
if (isset($_POST['name']) && isset($_POST['location'])) {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        // Update
        $sql = "UPDATE `cinema` SET `name` = '$name', `location` = '$location' WHERE `id` = $id";
        $message = "Cập nhật thành công!";
    } else {
        // Insert
        $sql = "INSERT INTO `cinema` (`name`, `location`) VALUES ('$name', '$location')";
        $message = "Thêm mới thành công!";
    }

    if (mysqli_query($conn, $sql)) {
        echo $message;
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>
