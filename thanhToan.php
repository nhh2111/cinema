<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <style>
        .combo-item {
            margin-bottom: 20px;
        }

        .seat-item {
            margin-bottom: 10px;
        }

        .total-price {
            font-weight: bold;
            margin-top: 20px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <h1>Trang Thanh Toán</h1>

    <div>
        <h2>Danh sách Combo đã chọn</h2>
        <div id="selectedCombos"></div>

        <h2>Danh sách Ghế đã chọn</h2>
        <div id="selectedSeats"></div>

        <div class="total-price">
            <p>Tổng tiền cần thanh toán: <span id="totalAmount">0</span> VND</p>
        </div>
        <button id="payButton">Thanh toán</button>
    </div>

    <script>
        // Lấy dữ liệu về các combo và ghế đã chọn từ sessionStorage
        const selectedCombos = JSON.parse(sessionStorage.getItem('selectedCombos')) || [];
        const selectedSeats = JSON.parse(sessionStorage.getItem('selectedSeats')) || [];
        const seatPrice = 50000; // Mỗi ghế có giá 50,000 VND
        const savedTotalPrice = parseInt(sessionStorage.getItem('totalPrice')) || 0; // Lấy totalPrice từ sessionStorage

        let totalAmount = savedTotalPrice; // Khởi tạo với giá trị totalPrice từ sessionStorage
        const selectedCombosContainer = document.getElementById('selectedCombos');
        const selectedSeatsContainer = document.getElementById('selectedSeats');
        const totalAmountContainer = document.getElementById('totalAmount');

        const comboPrices = JSON.parse(sessionStorage.getItem('comboPrices')) || {}; 

        // Hiển thị các combo đã chọn và tính tổng tiền của combo
        selectedCombos.forEach(comboStr => {
            const [comboName, quantityStr] = comboStr.split('*');
            const quantity = parseInt(quantityStr);

            const comboElement = document.createElement('div');
            comboElement.classList.add('combo-item');
            comboElement.innerHTML = `<p>Combo: ${comboName} (Số lượng: ${quantity})</p>`;
            selectedCombosContainer.appendChild(comboElement);

            // Lấy giá combo từ sessionStorage
            const price = comboPrices[comboName] || 0;

            // Tính tổng tiền cho combo đã chọn (bao gồm số lượng)
            totalAmount += price * quantity;
        });

        // Hiển thị các ghế đã chọn và tính tổng tiền của ghế
        selectedSeats.forEach(seatId => {
            const seatElement = document.createElement('div');
            seatElement.classList.add('seat-item');
            seatElement.innerHTML = `<p>Ghế: ${seatId}</p>`;
            selectedSeatsContainer.appendChild(seatElement);
            totalAmount += seatPrice; // Mỗi ghế có giá 50,000 VND
        });

        // Cập nhật tổng tiền cần thanh toán
        totalAmountContainer.innerText = totalAmount.toLocaleString();

        // Xử lý khi bấm nút Thanh toán
        document.getElementById('payButton').addEventListener('click', function () {
            // Lưu tổng tiền vào sessionStorage
            sessionStorage.setItem('totalAmount', totalAmount);
            alert('Đang chuyển đến trang thanh toán.');
            // Điều hướng đến trang thanh toán kết quả 
            window.location.href = 'thanhToanThanhCong.php'; 
        });
    </script>
</body>

</html>
