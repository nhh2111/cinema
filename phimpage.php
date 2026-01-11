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
        <h1 style="text-align: center">Quản lý phim</h1>

        <button type="button" id="btn-add">Thêm mới</button>
        <div id="add-form" style="display: none;">
            <form id="form-reg">
                <div class="form-group">
                    <label for="tinh_trang">Tinh trạng</label>
                    <input type="text" name="tinh_trang" id="tinh_trang" required>
                </div>
                <div class="form-group">
                    <label for="name">Tên phim</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="form-group">
                    <label for="movie_type">Thể loại</label>
                    <input type="text" name="movie_type" id="movie_type" required>
                </div>
                <div class="form-group">
                    <label for="duration">Thời lượng</label>
                    <input type="text" name="duration" id="duration" required>
                </div>
                <div class="form-group">
                    <label for="youtube_link">Link YouTube</label>
                    <input type="text" name="youtube_link" id="youtube_link" required
                        oninvalid="this.setCustomValidity('Nhập link YouTube')" oninput="this.setCustomValidity('')">
                </div>
                <div class="form-group">
                    <label for="php_link">Đường dẫn file PHP</label>
                    <input type="text" name="php_link" id="php_link" required
                        oninvalid="this.setCustomValidity('Nhập đường dẫn file PHP')"
                        oninput="this.setCustomValidity('')">
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

        <div id="movie-data-list" style="margin-left:200px">
        </div>

    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnAdd = document.getElementById('btn-add');
        const addForm = document.getElementById('add-form');
        const formReg = document.getElementById('form-reg');
        const recordId = document.getElementById('record-id');
        const nameInput = document.getElementById('name');
        const tinhTrangInput = document.getElementById('tinh_trang');
        const movieTypeInput = document.getElementById('movie_type');
        const durationInput = document.getElementById('duration');
        const youtubeLinkInput = document.getElementById('youtube_link');
        const phpLinkInput = document.getElementById('php_link');

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
            xhr.open('POST', 'changeMovie.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);
                        if (response.status === 'success') {
                            addForm.style.display = 'none';
                            loadData(); // Làm mới danh sách phim
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

        // Tải danh sách phim
        function loadData() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'indexphim.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('movie-data-list').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        loadData(); // Tải dữ liệu ban đầu

        // Xử lý xóa phim
        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('btn-delete')) {
                const id = e.target.getAttribute('data-id');
                if (confirm('Bạn có chắc chắn muốn xóa?')) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'deleteMovie.php', true);
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


        // Xử lý chỉnh sửa phim
        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('btn-edit')) {
                const id = e.target.dataset.id;
                const name = e.target.dataset.name;
                const movieType = e.target.dataset.movie_type;
                const duration = e.target.dataset.duration;
                const tinhTrang = e.target.dataset.tinh_trang;
                const youtubeLink = e.target.dataset.youtube_link;
                const phpLink = e.target.dataset.php_link;

                tinhTrangInput.value = tinhTrang;
                recordId.value = id;
                nameInput.value = name;
                movieTypeInput.value = movieType;
                durationInput.value = duration;
                phpLinkInput.value = phpLink;
                youtubeLinkInput.value = youtubeLink;

                addForm.style.display = 'block';
            }
        });
    });
</script>


</html>