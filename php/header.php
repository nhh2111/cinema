<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/db.php';
// Kiểm tra nếu có rạp chiếu đã chọn trong session
if (isset($_POST['cinema'])) {
    $_SESSION['selected_cinema'] = $_POST['cinema'];
}
// Lấy thông tin người dùng từ session
$unique_id = $_SESSION['unique_id'] ?? null;
$email = $_SESSION['email'] ?? null;
$selected_cinema = $_SESSION['selected_cinema'] ?? 'Mỹ Đình';

// Lấy danh sách các rạp chiếu, nhóm theo location
$sql = "SELECT * FROM cinema ORDER BY location, name";
$result = $conn->query($sql);
$cinemas_by_location = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cinemas_by_location[$row['location']][] = $row;
    }
}
if ($unique_id) {
    $sql_user = "SELECT * FROM users WHERE unique_id = '$unique_id'";
    $user_result = $conn->query($sql_user);
    
    if ($user_result && $user_result->num_rows > 0) {
        $user_data = $user_result->fetch_assoc();
        $_SESSION['verification_status'] = $user_data['verification_status'];
        $_SESSION['role'] = $user_data['role']; // Lưu vai trò người dùng vào session

        // Kiểm tra trạng thái xác thực của người dùng
        if ($user_data['verification_status'] !== 'Verified') {
            header("Location: verify.php");
            exit;
        }
    }
}
?>

<link rel="stylesheet" href="/cinemaWeb1/css/header.css">
<header id="header">
    <a href="/cinemaWeb1/index.php" class="logo">
        <img class="logo" style="width: 200px;height: 60px;" src="/cinemaWeb1/Images/logo phim hub.png"></a>
    <a href="/cinemaWeb1/phim.php" style="font-size:18px">Phim</a>
    <ul id="main-menu">
        <li>
            <a href="#">
                <h3><?php echo $selected_cinema; ?></h3>
            </a>
            <ul class="sub-menu">
                <?php foreach ($cinemas_by_location as $location => $cinemas): ?>
                    <li>
                        <a href="#">
                            <h4><?php echo $location ?></h4>
                        </a>
                        <ul class="sub-menu">
                            <?php foreach ($cinemas as $cinema): ?>
                                <li>
                                    <a href="#" onclick="setCinema('<?php echo $cinema['name'] ?>')">
                                        <?php echo $cinema['name'] ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
    <a style="font-size:18px" id="update-cinema-link" href="#" onclick="updateCinema()">Rạp</a>
    
    <a href="/cinemaWeb1/ttca.php" style="font-size:18px">Thành viên</a>

    <?php if ($unique_id): ?>
        <h2>Xin chào: <span><?php echo htmlspecialchars($email); ?></span></h2>
        <nav>
            <ul class="navigation">
                <li><a href="/cinemaWeb1/php/logout.php?logout_id=<?php echo $unique_id; ?>"><button class="logout_btn">Đăng
                            xuất</button></a></li>
            </ul>
        </nav>
    <?php else: ?>
        <nav>
            <ul class="navigation" style="display: flex">
                <li style="margin-right:10px"><a href="/cinemaWeb1/login.php"><button class="login_btn">Đăng
                            nhập</button></a></li>
                <li><a href="/cinemaWeb1/register.php"><button class="register_btn">Đăng ký</button></a></li>
            </ul>
        </nav>
    <?php endif; ?>

    <?php if (isset($user_data['role']) && $user_data['role'] === 'admin'): ?>
        <!-- Hiển thị menu admin nếu vai trò là admin -->
        <nav id="admin-menu">
            <a id="admin-toggle">
                <h3>Admin</h3>
            </a>
            <ul class="admin-submenu">
                <li><a href="/cinemaWeb1/admin/themrap.php">Thêm rạp</a></li>
                <li><a href="/cinemaWeb1/phimpage.php">Thêm phim</a></li>
                <li><a href="/cinemaWeb1/them_combo.php">Thêm combo</a></li>
            </ul>
        </nav>
    <?php endif; ?>
</header>

<script>
    // Sử dụng AJAX để cập nhật rạp chiếu đã chọn và thay đổi thông tin ngay lập tức
    function setCinema(cinema) {
        var cinemaSlug = cinema.toLowerCase()
            .replace(/á|â|à|ạ|ả|ã/g, 'a')
            .replace(/é|ê|è|ẹ|ế|ẻ|ẽ/g, 'e')
            .replace(/í|ì|ị|ỉ|ĩ/g, 'i')
            .replace(/ó|ô|ò|ọ|ỏ|õ/g, 'o')
            .replace(/ú|ư|ù|ụ|ủ|ũ/g, 'u')
            .replace(/ý|ỳ|ỵ|ỷ|ỹ/g, 'y')
            .replace(/đ/g, 'd')
            .replace(/\s+/g, '') // Loại bỏ khoảng trắng
            .replace(/[^\w-]+/g, ''); // Loại bỏ ký tự không phải chữ cái hoặc số

        var currentPage = window.location.pathname; // Lấy đường dẫn hiện tại
        var newCinemaUrl = "/cinemaWeb1/rap/" + cinemaSlug + ".php"; // Đường dẫn mới
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/cinemaWeb1/php/update_cinema.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        sessionStorage.setItem("cinemaSlug", newCinemaUrl);
                        sessionStorage.setItem("cinemaName", cinema); // Lưu tên rạp
                        // Kiểm tra nếu đang ở trang rạp
                        if (currentPage.includes("/cinemaWeb1/rap/")) {
                            window.location.href = newCinemaUrl; // Chuyển hướng đến rạp mới
                        } else {
                            window.location.reload(); // Tải lại trang
                        }
                    } else {
                        console.error("Failed to update cinema:", response.message);
                    }
                } catch (e) {
                    console.error("Invalid response from server");
                }
            }
        };

        xhr.onerror = function () {
            console.error("Request failed");
        };

        xhr.send("cinema=" + encodeURIComponent(cinema));
    }

    window.onload = function () {
    // Kiểm tra nếu có giá trị cinemaSlug trong sessionStorage
    var cinemaSlug = sessionStorage.getItem("cinemaSlug");
    var cinemaName = sessionStorage.getItem("cinemaName");
    // Nếu không có, đặt giá trị mặc định
    if (!cinemaSlug) {
        cinemaSlug = "/cinemaWeb1/rap/mydinh.php";
        cinemaName = "Mỹ Đình";
        sessionStorage.setItem("cinemaSlug", cinemaSlug);
        sessionStorage.setItem("cinemaName", cinemaName);
    }

    document.getElementById("update-cinema-link").href = cinemaSlug;

    // Cập nhật tên rạp chiếu đã chọn trên giao diện
    var cinemaNameElement = document.querySelector('#header h3');
    if (cinemaNameElement) {
        cinemaNameElement.innerText = cinemaName;
    }

    var uniqueId = sessionStorage.getItem("unique_id");

};

function updateCinema() {
    var selectedCinema = sessionStorage.getItem("cinemaSlug") || "/cinemaWeb1/rap/mydinh.php";
    window.location.href = selectedCinema; // Chuyển hướng đến trang rạp đã chọn
}
</script>
<style>
    #admin-menu {
        padding: 10px;
        border-radius: 5px;
        position: relative;
    }

    #admin-menu a {
        color: black;
        text-decoration: none;
        display: block;
        padding: 10px;

    }

    #admin-menu h3 {
        text-align: center;
        cursor: pointer;
        /* Thêm hiệu ứng con trỏ khi hover */
    }

    .admin-submenu {
        list-style: none;
        padding: 0;
        display: none;
        /* Ẩn menu các quyền truy cập */
        position: absolute;
        top: 60%;
        /* Đảm bảo menu phụ hiển thị bên dưới */
        left: 0;
        width: 100%;
        background-color: #fff;
        z-index: 100;
        /* Đảm bảo menu phụ nằm trên các phần tử khác */
        border: 1px solid black;
    }

    .admin-submenu a {
        font-size: 14px;
    }

    .admin-submenu a:hover {
        background-color: #555;
    }

    #admin-menu:hover .admin-submenu {
        display: block;
    }
</style>