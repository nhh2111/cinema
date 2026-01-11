<?php
include("../php/header.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý rạp chiếu phim</title>
    <style>
        body {
            margin-top: 100px;
        }

        .main-form {
            width: 100%;
        }

        #btn-add {
            margin-left: 200px;
            padding: 5px;
            background-color: greenyellow;
            border: 1px solid;
        }

        #data-list {
            margin-left: 300px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="main-form">
        <h1 style="text-align: center;">Quản lý rạp chiếu phim</h1>

        <button type="button" id="btn-add">Thêm mới</button>
        <div id="add-form" style="display: none;">
            <form id="form-reg">
                <div class="form-group">
                    <label for="name">Tên rạp</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="form-group">
                    <label for="location">Địa điểm</label>
                    <input type="text" name="location" id="location" required>
                </div>
                <input type="hidden" name="id" id="record-id"> 
                <button type="submit">Lưu</button>
                <button type="button" id="btn-cancel">Hủy</button>
            </form>
        </div>
        
        <div id="data-list">
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnAdd = document.getElementById('btn-add');
            const addForm = document.getElementById('add-form');
            const formReg = document.getElementById('form-reg');
            const recordId = document.getElementById('record-id');
            const name = document.getElementById('name');
            const location = document.getElementById('location');

            // "Thêm mới" Button
            btnAdd.addEventListener('click', function () {
                addForm.style.display = 'block';
                recordId.value = ''; // Reset ID for new entry
                formReg.reset();
            });

            // "Hủy" Button
            const btnCancel = document.getElementById('btn-cancel');
            if (btnCancel) {
                btnCancel.addEventListener('click', function () {
                    addForm.style.display = 'none';
                });
            }

            // Submit Form with AJAX
            formReg.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(formReg);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'change.php', true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert(xhr.responseText);
                        addForm.style.display = 'none'; // Hide form on success
                        loadData(); // Refresh the list
                    } else {
                        alert('Có lỗi xảy ra, vui lòng thử lại.');
                    }
                };
                xhr.send(formData);
            });

            // Load Data List
            function loadData() {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'fetch.php', true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        document.getElementById('data-list').innerHTML = xhr.responseText;
                    }
                };
                xhr.send();
            }

            loadData(); // Initial load

            // Delete Record
            document.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('btn-delete')) {
                    const id = e.target.getAttribute('data-id');
                    if (confirm('Bạn có chắc chắn muốn xóa?')) {
                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', 'delete.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onload = function () {
                            if (xhr.status === 200) {
                                alert(xhr.responseText);
                                loadData(); // Refresh the list
                            }
                        };
                        xhr.send('id=' + id);
                    }
                }
            });

            // Edit Record
            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-edit')) {
                    const id = e.target.dataset.id;
                    const cinemaName = e.target.dataset.name;
                    const cinemaLocation = e.target.dataset.location;

                    recordId.value = id;
                    name.value = cinemaName;
                    location.value = cinemaLocation;
                    addForm.style.display = 'block';
                }
            });
        });

    </script>
</body>

</html>