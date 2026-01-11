<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
include_once './db.php';

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = md5($_POST['pass']);
$cpassword = md5($_POST['cpass']);
$Role = 'user';
$verification_status = '0';

if (!empty($fname) && !empty($lname) && !empty($email) && !empty($phone) && !empty($password) && !empty($cpassword)) {

    if (strlen($phone) === 10 && ctype_digit($phone)) { 
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            // Thực hiện truy vấn trực tiếp để kiểm tra email có tồn tại hay không
            $email_check_query = "SELECT email FROM users WHERE email = '$email'";
            $result = $conn->query($email_check_query);
            
            if ($result && $result->num_rows > 0) {
                echo "Email đã tồn tại";
            } else {

                if ($password === $cpassword) {
                    $random_id = rand(time(), 10000000);
                    $otp = mt_rand(1111, 9999);

                    // Truy vấn trực tiếp để chèn người dùng mới
                    $insert_query = "INSERT INTO users (unique_id, fname, lname, email, phone, password, otp, verification_status, Role) 
                                     VALUES ('$random_id', '$fname', '$lname', '$email', '$phone', '$password', '$otp', '$verification_status', '$Role')";

                    if ($conn->query($insert_query)) {
                        $_SESSION['unique_id'] = $random_id;
                        $_SESSION['email'] = $email;
                        $_SESSION['otp'] = $otp;

                        try {
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'nghuyhoang211104@gmail.com';
                            $mail->Password = 'efcs cwnp ghjo ocuw';
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 587;

                            $receiver_email = $email;

                            $mail->setFrom('nghuyhoang211104@gmail.com', 'Nguyễn Huy Hoàng');
                            $mail->addAddress($receiver_email);

                            $mail->isHTML(true);
                            $mail->Subject = 'OTP';
                            $mail->Body    = "<div>Tới $lname $fname <br>Cảm ơn vì đã đăng ký, Mã OTP của bạn là: $otp. </div>";

                            $mail->send();
                            echo 'success';
                        } catch (Exception $e) {
                            echo "Lỗi khi gửi mail: {$mail->ErrorInfo}";
                        }
                    } else {
                        echo "Đã có lỗi xảy ra";
                    }
                } else {
                    echo "Mật khẩu không khớp";
                }
            }
        } else {
            echo "$email không phải là email hợp lệ";
        }
    } else {
        echo "Số điện thoại phải là 10 chữ số";
    }
} else {
    echo "Cần nhập đủ thông tin";
}

$conn->close();
?>
