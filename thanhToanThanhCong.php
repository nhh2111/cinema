<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận vé</title>
</head>
<body style="margin-top: 100px;">
    <div style=" text-align:center" >
        <div>
        <img src="Images/anh-meo-con-35.jpg" style="with:200px; height:200px">
        </div>
        <div><button id="confirmButton" style="padding:10px 20px;margin-top:20px;background-color:green;border:1px solid green;color:white">Xác nhận</button></div>
        
    </div>
    
    <script>
        document.getElementById('confirmButton').addEventListener('click', () => {
            // Lấy dữ liệu từ sessionStorage
            const selectedMovie = sessionStorage.getItem('selectedMovie');
            const cinemaName = sessionStorage.getItem('cinemaName');
            const selectedSeats = sessionStorage.getItem('selectedSeats');
            const totalAmount = sessionStorage.getItem('totalAmount');
            const unique_id = sessionStorage.getItem('unique_id');

            // Chuyển đổi dữ liệu ghế ngồi từ JSON sang chuỗi
            const formattedSeats = selectedSeats ? JSON.parse(selectedSeats).join(',') : '';

            // Tạo dữ liệu gửi đi
            const data = {
                ma_hoa_don: generateRandomId(), // Tạo mã hóa đơn ngẫu nhiên
                phim: selectedMovie,
                rap_chieu: cinemaName,
                ghe_da_dat: formattedSeats,
                tongTien: totalAmount,
                unique_id: unique_id
            };

            // Gửi dữ liệu đến server bằng Fetch API
            fetch('insert_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Dữ liệu đã được lưu thành công!');
                    // Chuyển hướng đến trang khác nếu cần
                    window.location.href = 'index.php';
                } else {
                    alert('Có lỗi xảy ra: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Không thể lưu dữ liệu.');
            });
        });

        // Hàm tạo mã hóa đơn ngẫu nhiên
        function generateRandomId() {
            return 'HD' + Math.floor(Math.random() * 1000000);
        }
    </script>
</body>
</html>
