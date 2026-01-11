document.addEventListener('DOMContentLoaded', function () {
    const btnAdd = document.getElementById('btn-add');
    const addForm = document.getElementById('add-form');
    const formReg = document.getElementById('form-reg');
    const recordId = document.getElementById('record-id');
    const nameInput = document.getElementById('name');
    const locationInput = document.getElementById('location');

    // Hiển thị form thêm mới
    btnAdd.addEventListener('click', function () {
        addForm.style.display = 'block';
        formReg.reset(); // Xóa dữ liệu form
        recordId.value = ''; // Xóa giá trị ID (thêm mới)
    });

    // Nút hủy
    const btnCancel = document.getElementById('btn-cancel');
    btnCancel.addEventListener('click', function () {
        addForm.style.display = 'none';
    });

    // Gửi form (AJAX thêm hoặc sửa)
    formReg.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(formReg);

        fetch('change.php', {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.text())
            .then((data) => {
                alert(data);
                addForm.style.display = 'none'; // Ẩn form
                loadData(); // Cập nhật danh sách
            })
            .catch((error) => console.error('Lỗi:', error));
    });

    // Tải danh sách
    function loadData() {
        fetch('fetch.php')
            .then((response) => response.text())
            .then((data) => {
                document.getElementById('data-list').innerHTML = data;
            })
            .catch((error) => console.error('Lỗi:', error));
    }

    loadData(); // Tải danh sách khi trang load

    // Xóa
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-delete')) {
            const id = e.target.dataset.id;
            if (confirm('Bạn có chắc chắn muốn xóa?')) {
                fetch('delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}`,
                })
                    .then((response) => response.text())
                    .then((data) => {
                        alert(data);
                        loadData(); // Tải lại danh sách
                    });
            }
        }
    });

    // Sửa
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-edit')) {
            const id = e.target.dataset.id;
            const name = e.target.dataset.name;
            const location = e.target.dataset.location;

            recordId.value = id;
            nameInput.value = name;
            locationInput.value = location;
            addForm.style.display = 'block'; // Hiển thị form
        }
    });
});
