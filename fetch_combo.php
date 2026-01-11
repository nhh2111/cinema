<?php
include("php/db.php");

$sql = "SELECT * FROM combo";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo '<table border="1">';
    echo '<caption>Bảng thông tin combo</caption>';
    echo '<tr>';
    echo '<th>Tên combo</th>';
    echo '<th>Giá combo</th>';
    echo '<th>Mô tả</th>';
    echo '<th>Ảnh</th>';
    echo '<th>Chỉnh sửa</th>';
    echo '</tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        $imagePath = !empty($row['image']) && file_exists($row['image']) ? $row['image'] : 'default-image.jpg';
        echo '<tr>';
        echo '<td>' . $row["ten_combo"] . '</td>';
        echo '<td>' . $row["gia_combo"] . '</td>';
        echo '<td>' . $row["mota"] . '</td>';
        echo '<td><img src="' . $imagePath . '" alt="Image" style="width: 100px; height: auto;"></td>';
        echo '<td>';
        echo '<button class="btn-edit" data-id="' . $row['id'] . '"';
        echo ' data-ten_combo="' . $row['ten_combo'] . '"';
        echo ' data-gia_combo="' . $row['gia_combo'] . '"';
        echo ' data-mota="' . $row['mota'] . '">Sửa</button>';
        echo ' <button class="btn-delete" data-id="' . $row['id'] . '">Xóa</button>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo '<p>Không có combo nào trong danh sách.</p>';
}

mysqli_close($conn);
?>
