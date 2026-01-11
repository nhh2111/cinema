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