<?php
include('php/header.php');
include('php/db.php');

// Xử lý yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    // Kiểm tra xem có dữ liệu selectedCombos không
    if (isset($_POST['selectedCombos']) || empty($_POST['selectedCombos'])) {
        $selectedCombos = $_POST['selectedCombos'];
        $success = true;

        // Chuyển đổi các combo từ chuỗi JSON
        foreach ($selectedCombos as $comboData) {
            $combo = json_decode($comboData, true); // Chuyển từ chuỗi JSON thành mảng

            if (isset($combo['comboId'], $combo['quantity'])) {
                $comboId = $combo['comboId'];
                $quantity = $combo['quantity'];

            }
        }

        if ($success) {
            $response['success'] = true;
            $response['message'] = 'Đặt vé thành công!';
        }
    } else {
        $response['message'] = 'Không có dữ liệu combo.';
    }

    // Trả về phản hồi
    echo $response['message'];
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chọn Combo</title>
    <style>
        .combo-item {
            margin-bottom: 20px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
        }

        .quantity-control button {
            padding: 5px 10px;
            margin: 0 5px;
        }

        .total-price {
            font-weight: bold;
            margin-top: 20px;
        }
        
    </style>
</head>

<body style="margin-top:100px">
    <form id="comboForm">
        <h2>Chọn Combo</h2>
        <div>
            
        </div>
        <?php
        include('php/db.php');
        // Lấy danh sách combo
        $sql = "SELECT * FROM combo";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            // Lặp qua tất cả các combo và hiển thị thông tin
            while ($row = mysqli_fetch_assoc($result)) {
                $comboId = $row["id"];
                $comboName = $row["ten_combo"];
                $comboPrice = $row["gia_combo"];
                $comboDescription = $row["mota"];
                $comboImage = $row["image"];
                // Hiển thị thông tin combo
                echo '<table class="combo-item" style="display:flex; padding:20px;margin:30px;width:95%;border:1px solid black">';
                echo '<tr>';
                echo '<td style="text-align:center;width:250px; border-right:1px solid black"><h3>' . $comboName . '</h3></td>';
                echo '<td style="text-align:center;width:250px; border-right:1px solid black">' . $comboDescription . '</td>';
                echo '<td style="padding:20px 70px; border-right:1px solid black"><img src="' . $comboImage . '" alt="Image" style="width: 120px; height: auto;"></td>';
                echo '<td style="text-align:center;width:250px; border-right:1px solid black">Giá: ' . $comboPrice. ' VND</td>';
                // Thêm điều khiển số lượng
                echo '<td class="quantity-control" style="padding:70px 120px;width:100px">';
                echo '<button type="button" class="decrease" data-id="' . $comboId . '">-</button>';
                echo '<input type="number" id="quantity_' . $comboId . '" value="0" min="0" readonly style="width: 50px; text-align: center;">';
                echo '<button type="button" class="increase" data-id="' . $comboId . '">+</button>';
                echo '</td>';
                echo '<input type="hidden" class="combo-price" value="' . $comboPrice . '" data-id="' . $comboId . '">';
                echo '</tr>';
                echo '</table>';
            }
        }
        ?>
        <div class="total-price" style="text-align:center;font-size:26px">
            <p>Tổng giá tiền: <span id="totalPrice">0</span> VND</p>
        </div>
        <div style="display:flex">
        <button style="padding:15px 40px; background-color:green; color: white; margin:auto;bpttom:50px" type="submit">Xác nhận</button>
        </div>
        
    </form>
    <script>
         // Lắng nghe sự kiện cho các nút tăng giảm số lượng
 document.querySelectorAll('.increase').forEach(button => {
    button.addEventListener('click', function () {
        const comboId = this.dataset.id;
        const quantityInput = document.getElementById('quantity_' + comboId);
        let currentQuantity = parseInt(quantityInput.value);
        quantityInput.value = currentQuantity + 1;
        updateTotalPrice();
    });
});

document.querySelectorAll('.decrease').forEach(button => {
    button.addEventListener('click', function () {
        const comboId = this.dataset.id;
        const quantityInput = document.getElementById('quantity_' + comboId);
        let currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity > 0) {
            quantityInput.value = currentQuantity - 1;
            updateTotalPrice();
        }
    });
});

// Cập nhật tổng giá tiền
function updateTotalPrice() {
    let totalPrice = 0;

    // Lấy tổng giá từ các combo đã chọn
    document.querySelectorAll('.combo-item').forEach(item => {
        const comboId = item.querySelector('.increase').dataset.id;
        const quantity = parseInt(document.getElementById('quantity_' + comboId).value);

        // Lấy giá tiền combo từ hidden input
        const price = parseInt(item.querySelector('.combo-price').value);

        totalPrice += quantity * price;
    });

    document.getElementById('totalPrice').innerText = totalPrice;
}

// Xử lý khi người dùng gửi form
document.getElementById('comboForm').addEventListener('submit', async function (event) {
    event.preventDefault();

    const selectedCombos = [];
    const totalPrice = document.getElementById('totalPrice').innerText;

    // Lưu tổng giá tiền vào sessionStorage
    sessionStorage.setItem('totalPrice', totalPrice);

    // Thu thập các combo và số lượng đã chọn
    document.querySelectorAll('.combo-item').forEach(item => {
        const comboId = item.querySelector('.increase').dataset.id;
        const quantity = parseInt(document.getElementById('quantity_' + comboId).value);
        const comboName = item.querySelector('h3').innerText; // Lấy tên combo

        if (quantity > 0) {
            // Lưu tên combo và số lượng vào mảng dưới định dạng: "comboName*quantity"
            selectedCombos.push(comboName + '*' + quantity);
        }
    });

    // Lưu tên các combo và số lượng vào sessionStorage
    sessionStorage.setItem('selectedCombos', JSON.stringify(selectedCombos)); // Lưu mảng vào sessionStorage

    const formData = new FormData();
    // Thêm các combo và số lượng vào formData
    selectedCombos.forEach(combo => {
        formData.append('selectedCombos[]', combo); // Lưu dưới dạng chuỗi
    });

    // Thêm giá trị tổng tiền
    formData.append('totalPrice', totalPrice);

    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData // Gửi dữ liệu thông qua FormData
        });

        const text = await response.text(); // Nhận phản hồi dưới dạng văn bản
        console.log('Server response:', text); // Log phản hồi để kiểm tra

        if (text.includes("Đặt vé thành công!")) {
            alert('Đặt vé thành công!');
            window.location.href = 'thanhToan.php'; // Chuyển đến trang thanh toán
        } else {
            alert('Có lỗi xảy ra!');
        }

    } catch (error) {
        console.error('Lỗi khi gửi yêu cầu:', error);
        alert('Có lỗi khi gửi yêu cầu! Vui lòng thử lại.');
    }
});
    </script>
<!-- <script src="js/combo.js"></script> -->
</body>

</html>