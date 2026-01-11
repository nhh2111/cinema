<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/form.css">
    <link rel="stylesheet" href="./css/cinema.css">
</head>

<body style=" flex-wrap: wrap;">
    <div class="form">
        <h2 style="text-align: center">Đăng nhập</h2>
        <form>
            <div class="error-text">Error</div>
            <div class="input">
                <label>Email</label>
                <input type="email" name="email" placeholder="Nhập email" required>
            </div>
            <div class="input">
                <label>Password</label>
                <input type="password" name="pass" placeholder="Nhập mật khẩu" required>
            </div>
            <div class="submit">
                <input style="width: 91%" type="submit" value="Xác nhận" class="button">
            </div>
        </form>
        <div class="link">Chưa có tài khoản?<a href="./register.php"> Đăng ký ngay</a></div>
    </div>
    <div>
        <script>
            const form = document.querySelector('.form form'),
                submitbtn = form.querySelector('.submit input'),
                errortxt = form.querySelector('.error-text');

            form.onsubmit = (e) => {
                e.preventDefault();  // Ngăn chặn form gửi
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "./php/login.php", true);
                xhr.onload = () => {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status == 200) {
                            let response = JSON.parse(xhr.response);  // Parse the JSON response
                            if (response.status == "success") {
                                // Lưu unique_id vào sessionStorage
                                sessionStorage.setItem('unique_id', response.unique_id);
                                location.href = "index.php";  // Redirect to the home page
                            } else {
                                // Hiển thị thông báo lỗi nếu đăng nhập thất bại
                                errortxt.textContent = response.message;
                                errortxt.style.display = 'block';
                            }
                        }
                    }
                };
                let formData = new FormData(form); 
                xhr.send(formData); 
            };

        </script>
        <div>

</body>

</html>