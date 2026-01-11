<?php
include("php/header.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body style="margin-top:100px">
    <div class="main-form">
        <h1 style="text-align: center">Quản lý combo</h1>

        <button type="button" id="btn-add">Thêm mới</button>
        <div id="add-form" style="display: none;">
            <form id="form-reg">
                <div class="form-group">
                    <label for="ten_combo">Tên combo</label>
                    <input type="text" name="ten_combo" id="ten_combo" required>
                </div>
                <div class="form-group">
                    <label for="gia_combo">Giá combo</label>
                    <input type="text" name="gia_combo" id="gia_combo" required>
                </div>
                <div class="form-group">
                    <label for="mota">Mô tả</label>
                    <input type="text" name="mota" id="mota" required>
                </div>
                <div class="profile-img">
                    <div class="file-upload">
                        <input type="file" id="image-preview" name="image" class="files-input" required
                            oninvalid="this.setCustomValidity('Chọn ảnh phim')" oninput="this.setCustomValidity('')">
                    </div>
                </div>
                <input type="hidden" name="id" id="record-id"> 
                <button type="submit">Lưu</button>
                <button type="button" id="btn-cancel">Hủy</button>
            </form>
        </div>

        <div id="combo-data-list" style="margin-left:350px">
        </div>

    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnAdd = document.getElementById('btn-add');
        const addForm = document.getElementById('add-form');
        const formReg = document.getElementById('form-reg');
        const recordId = document.getElementById('record-id');
        const giaComboInput = document.getElementById('gia_combo');
        const tenComboInput = document.getElementById('ten_combo');
        const motaInput = document.getElementById('mota');

        // Hiển thị form "Thêm mới"
        btnAdd.addEventListener('click', function () {
            addForm.style.display = 'block';
            recordId.value = ''; // Reset ID khi thêm mới
            formReg.reset();
        });

        // Ẩn form khi nhấn "Hủy"
        const btnCancel = document.getElementById('btn-cancel');
        btnCancel.addEventListener('click', function () {
            addForm.style.display = 'none';
        });

        // Gửi dữ liệu form bằng AJAX
        formReg.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(formReg);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'change_combo.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);
                        if (response.status === 'success') {
                            addForm.style.display = 'none';
                            loadData(); // Làm mới danh sách 
                        }
                    } catch (error) {
                        alert('Đã xảy ra lỗi khi xử lý phản hồi.');
                    }
                } else {
                    alert('Có lỗi xảy ra, vui lòng thử lại.');
                }
            };
            xhr.send(formData);
        });

        // Tải danh sách 
        function loadData() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_combo.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('combo-data-list').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        loadData(); // Tải dữ liệu ban đầu

        // Xử lý xóa 
        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('btn-delete')) {
                const id = e.target.getAttribute('data-id');
                if (confirm('Bạn có chắc chắn muốn xóa?')) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'delete_combo.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            try {
                                const response = JSON.parse(xhr.responseText); // Phân tích JSON
                                alert(response.message);
                                if (response.status === 'success') {
                                    loadData(); // Làm mới danh sách
                                }
                            } catch (error) {
                                alert('Đã xảy ra lỗi khi xử lý phản hồi.');
                            }
                        } else {
                            alert('Có lỗi xảy ra, vui lòng thử lại.');
                        }
                    };
                    xhr.send('id=' + id);
                }
            }
        });

        // Xử lý chỉnh sửa 
        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('btn-edit')) {
                const id = e.target.dataset.id;
                const gia = e.target.dataset.gia_combo;
                const mota = e.target.dataset.mota;
                const combo = e.target.dataset.ten_combo;

                tenComboInput.value = combo;
                recordId.value = id;
                giaComboInput.value = gia;
                motaInput.value = mota;

                addForm.style.display = 'block';
            }

        });
    });
</script>


</html>