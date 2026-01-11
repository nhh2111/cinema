<?php
include('php/header.php');
include('php/db.php');

// Kiểm tra yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];

    if (isset($_POST['selected'])) {
        $selectedSeats = $_POST['selected'];
        $success = true;

        // Cập nhật trạng thái ghế trong cơ sở dữ liệu
        foreach ($selectedSeats as $id) {
            $sql = "UPDATE datghe SET trangThai = 1 WHERE id = $id";
            if (!mysqli_query($conn, $sql)) {
                $success = false;
                $response['message'] = 'Lỗi cập nhật cơ sở dữ liệu.';
                break;
            }
        }

        if ($success) {
            $response['success'] = true;
            $response['message'] = 'Cập nhật thành công.';
        }
    } 

    // Trả về JSON phản hồi
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Dừng xử lý sau khi trả JSON
}

// Lấy dữ liệu từ bảng datghe
$sql = "SELECT * FROM datghe ORDER BY hang, cot";
$result = mysqli_query($conn, $sql);
$data = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

// Nhóm dữ liệu theo hàng
$groupedSeats = [];
foreach ($data as $item) {
    $groupedSeats[$item['hang']][] = $item;
}

?>

<!DOCTYPE html>
<html lang="vi">
<style>
    .canhbao {
        text-align: center;
        font-family: "Roboto Light";
        padding: 15px 100px;
        margin-left: 180px;
        font-weight: bold;
        position: relative;
        top: 25px;
        display: inline-block;
        animation: blink-bg 1s infinite;
        text-decoration: none;
        /* Hiệu ứng nhấp nháy */
    }

    @keyframes blink-bg {
        0%,
        100% {
            background-color: orange;
        }

        /* Nền đỏ */
        100% {
            background-color: navajowhite;
        }

        /* Nền vàng */
    }

    .anhtivi {
        width: 750px;
        margin-top: 30px;
        margin-left: 175px;
    }

    .datVeForm {
        margin-left: 310px;
        margin-top: 100px;
    }

    .dongGhe {
        display: flex;
        margin-left: 350px;
        margin-top: 50px;
    }

    .dongGhe img {
        margin: -10px 45px;
        display: flex;
        height: 50px;
        width: 50px;
    }

    .dongGhe a {
        display: block;
        margin-top: 10px;
        margin-left: 30px;
    }

    .thuoctinh {
        margin-top: 70px;
    }

    .seat span {
        position: absolute;
        top: 40%;
        left: 37%;
        right: 20px;
        transform: translate(-30%, -70%);
        font-size: 10px;
        color: black;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    .benduoi {
        margin-top: 20px;
    }

    .benduoi img {
        margin-top: 10px;
        position: relative;
        top: 15px;
        left: 6px;
    }

    .benduoi a {
        margin-top: 25px;
        margin-left: 4px;
    }

    .benduoi b {
        margin-top: 25px;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt vé</title>
    <link rel="stylesheet" href="css/dattvest.css">
</head>

<body style="margin-top:100px">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var movieName = sessionStorage.getItem('selectedMovie');
            var image = sessionStorage.getItem('selectedMovieImage');
            var type = sessionStorage.getItem('selectedMovieType');
            var duration = sessionStorage.getItem('selectedMovieDuration');
            var cinema = sessionStorage.getItem('cinemaName');

            document.getElementById('movie-image').src = image;
            document.getElementById('movie-type').textContent = type;
            document.getElementById('cinema').textContent = cinema;
            document.getElementById('movie-duration').textContent = duration;
            document.getElementById('movie-name').textContent = movieName;
            document.getElementById('movie-name1').textContent = movieName;
        });
    </script>
    <div class="dongdatve" style="display:flex">
        <div>Trang Chủ</div> <b>></b>
        <div>Đặt Vé</div> <b>></b>
        <div id="movie-name"></div>
    </div>

    <span style="color: red" class="canhbao">Theo quy định của cục điện ảnh, phim này không dành cho khán giả dưới 18
        tuổi</span>
    <div class="dongGhe">
        <div>
            <img src="Images/ghengoitrong.png">
            <a>Ghế Trống</a>
        </div>
        <div>
            <img src="Images/nghedangdat.png">
            <a>Ghế Đang Chọn</a>
        </div>
        <div>
            <img src="Images/ghedaban.png">
            <a>Ghế Đã Bán</a>
        </div>
    </div>
    <img class="anhtivi" src="Images/2d487c64-0835-4133-9a96-1fec00bcbf9c.png">

    <label style="margin-right: 200px;position:absolute; right: 450px;top: 350px;" class="titlecuara">Cửa Ra</label>
    <img style="width: 50px ; height:auto; position: absolute; right: 650px;top: 280px" src="Images/anhcua2.png">

    <form id="datVeForm" class="datVeForm">
        <div class="seat-container">
            <?php foreach ($groupedSeats as $hang => $seats): ?>
                <div class="seat-row">
                    <?php foreach ($seats as $seat): ?>
                        <div class="seat">
                            <img src="<?php
                            echo $seat['trangThai'] == 0 ? 'Images/ghengoitrong.png' :
                                ($seat['trangThai'] == 1 ? 'Images/ghedaban.png' : 'Images/ghedaban.png');
                            ?>" style="width:55px" alt="Trạng thái ghế" data-id="<?php echo $seat['id']; ?>"
                                data-status="<?php echo $seat['trangThai']; ?>"
                                onclick="<?php echo $seat['trangThai'] == 0 ? 'toggleSelection(this)' : 'return false;'; ?>">
                            <input type="checkbox" name="selected[]" value="<?php echo $seat['id']; ?>" hidden>
                            <span><?php echo $seat['tenGhe']; ?> </span>
                        </div>
                    <?php endforeach; ?>

                </div>

            <?php endforeach; ?>
            <button style="width: 150px; position:absolute;right: 225px;bottom:15px" type="submit" id="confirm-btn"
                disabled>Xác nhận</button>
        </div>
        <label style="margin-right: 200px;position:absolute; left: 300px;bottom: -45px;" class="titlecuara">Cửa
            Vào</label>
        <img style="width: 50px ; height:auto; position: absolute; left: ;: 350px;bottom: -10px"
            src="Images/anhcua2.png">
    </form>

    <div class="container"
        style="background-color: white;height: 600px; position: relative; bottom: 730px; left: 990px;width: 30%; bottom:620px">
        <form>
            <div>
                <img style="width: 200px;height: 240px;padding-left:10px;padding-top: 5px" id="movie-image" src="">
                <div style="position: relative;color: #03599D;top: 15px;right:-10px;font-size: 20px;font-family:Arial, sans-serif;text-align: left;width:400px"
                    id="movie-name1"></div>
            </div>
            <div class="thuoctinh" style="margin:auto">
                <div class="benduoi">
                    <div style="margin-top:55px">
                        <img src="Images/icontheloai.png" style="margin-bottom: 5px">
                        <a>Thể loại:</a>
                    </div>
                    <div style="position: absolute; right:30px;bottom: 214px" id="movie-type"></div>
                </div>
                <div class="benduoi">
                    <div>
                        <img src="Images/thoiluongicon.png" style="margin-bottom: 5px; margin-left: 4px">
                        <a>Thời lượng:</a>
                    </div>
                    <div style="position: absolute;right: 65px; bottom:148px" id="movie-duration"></div>
                    <a style="position: absolute; right:25px;bottom: 148px">Phút</a>
                </div>
                <div class="benduoi">
                    <div>
                        <img src="Images/rapchieuicon.png" style="margin-bottom: 5px">
                        <a>Rạp chiếu:</a>
                    </div>
                    <div style="position: absolute; right:30px;bottom: 77px" id="cinema"></div>
                </div>

            </div>
        </form>
    </div>
    <script>
        // Danh sách ghế đã chọn
        let selectedSeats = [];
        const cinemaName = sessionStorage.getItem('cinemaName');
        // Hàm chuyển đổi trạng thái ghế
        function toggleSelection(img) {
            const seatId = img.getAttribute('data-id');
            const currentStatus = img.getAttribute('data-status');
            const seatName = img.nextElementSibling.nextElementSibling.innerText; // Lấy tên ghế

            const checkbox = img.nextElementSibling;
            if (checkbox.checked) {
                // Bỏ chọn ghế
                img.src = 'Images/ghengoitrong.png';
                checkbox.checked = false;
                selectedSeats = selectedSeats.filter(name => name !== seatName); // Lọc bỏ tên ghế
            } else {
                // Chọn ghế
                img.src = 'Images/nghedangdat.png';
                checkbox.checked = true;
                selectedSeats.push(seatName); // Thêm tên ghế vào danh sách
            }

            // Kích hoạt nút Xác nhận nếu có ghế được chọn
            document.getElementById('confirm-btn').disabled = selectedSeats.length === 0;
        }

        // Lưu ghế "đang đặt" vào sessionStorage
        document.getElementById('datVeForm').addEventListener('submit', async function (event) {
            event.preventDefault();
            // Lưu danh sách tên ghế đã chọn vào sessionStorage
            sessionStorage.setItem('selectedSeats', JSON.stringify(selectedSeats));

            // Điều hướng đến trang combo.php
            window.location.href = 'combo.php';
        });

        // Xử lý khi bấm Xác nhận
        document.getElementById('datVeForm').addEventListener('submit', async function (event) {
            event.preventDefault();

            const formData = new FormData(this);
            const response = await fetch(window.location.href, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            if (result.success) {
                alert('Cập nhật thành công!');
                window.location.href = 'combo.php'; // Chuyển sang trang chọn combo
            } else {
                alert(result.message || 'Có lỗi xảy ra!');
            }
        });

    </script>
</body>

</html>