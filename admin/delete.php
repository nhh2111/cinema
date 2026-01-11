<?php
include("../php/db.php");
//Xóa rạp
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM `cinema` WHERE `id` = $id";

    if (mysqli_query($conn, $sql)) {
        echo "Xóa thành công!";
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>
