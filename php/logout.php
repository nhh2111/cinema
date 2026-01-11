<?php
session_start();
include 'db.php';

if (isset($_SESSION['unique_id'])) {
    // Lấy logout_id từ GET
    if (isset($_GET['logout_id'])) {
        $logout_id = mysqli_real_escape_string($conn, $_GET['logout_id']);
        // Hủy session và chuyển hướng về index.php
        session_unset();
        session_destroy();

        header("location: ../index.php");
        exit;
    } else {
        // Nếu không có logout_id, chuyển về index.php
        header("location: ../index.php");
        exit;
    }
} else {
    // Nếu không đăng nhập, chuyển về login.php
    header("location: ../login.php");
    exit;
}
?>
