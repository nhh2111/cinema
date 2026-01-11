<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./css/form.css">
</head>
<body>
    <div class="form">
        <h2 style="text-align: center">Đăng ký</h2>
        <form action="" enctype="multipart/form-data">
            <div class="error-text">Error</div>
            <div class="grid-details">
                <div class="input">
                    <label>Tên</label>
                    <input type="text" name="fname" placeholder="Nhập tên" required>
                </div>
                <div class="input">
                    <label>Họ</label>
                    <input type="text" name="lname" placeholder="Nhập họ" required>
                </div>
            </div>
            <div class="input">
                <label>Email</label>
                <input style="width: 95%" type="email" name="email" placeholder="enter your email" required>
            </div>
            <div class="input">
                <label>Phone</label>
                <input style="width: 95%" type="tel" name="phone" placeholder="enter your phone number" required pattern="[0-9]{10}" oninvalid="this.setCustomValidity('Nhập vào 10 số')" oninput="this.setCustomValidity('')">
            </div>
            <div class="grid-details">
                <div class="input">
                    <label>Mật khẩu</label>
                    <input type="password" name="pass" placeholder="Mật khẩu" required>
                </div>
                <div class="input">
                    <label>Nhập lại mật khẩu</label>
                    <input type="password" name="cpass" placeholder="Nhập lại mật khẩu" required>
                </div>
            </div>
            <div class="submit">
                <input type="submit" value="Xác nhận" class="button">
            </div> 
        </form>
        <div class="link">Đã có tài khoản? <a href="/cinemaWeb1/login.php">Đăng nhập ngay</a></div>
    </div>
    <script src="./js/register.js"></script>
</body>
</html>