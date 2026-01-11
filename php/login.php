<?php
session_start();
include 'db.php';

$Email = $_POST['email'];
$Password = md5($_POST['pass']);

if(!empty($Email) && !empty($Password)){
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$Email}' AND password = '{$Password}'");
    if(mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_assoc($sql);
        if($row){
            // Lưu thông tin vào session
            $_SESSION['unique_id'] = $row['unique_id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['otp'] = $row['otp'];

            // Trả về JSON chứa thông tin thành công
            echo json_encode(array(
                'status' => 'success',
                'unique_id' => $row['unique_id']
            ));
        }
    }
    else{
        // Trả về thông báo lỗi
        echo json_encode(array('status' => 'error', 'message' => 'Email or Password is Incorrect'));
    }
}
else{
    // Trả về thông báo lỗi nếu thiếu thông tin
    echo json_encode(array('status' => 'error', 'message' => 'All Fields Are Required'));
}
?>
