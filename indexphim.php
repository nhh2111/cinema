<?php
include("php/db.php");

$sql = "SELECT * FROM movies ORDER BY tinh_trang ASC, id ASC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo '<table border="1">';
    echo '<caption>Bảng thông tin phim</caption>';
    echo '<tr>';
    echo '<th>Tình trạng</th>';
    echo '<th>Tên phim</th>';
    echo '<th>Thể loại</th>';
    echo '<th>Thời lượng</th>';
    echo '<th>Link YouTube</th>';
    echo '<th>Link PHP</th>';
    echo '<th>Ảnh</th>';
    echo '<th>Chỉnh sửa</th>';
    echo '</tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row["tinh_trang"] . '</td>';
        echo '<td>' . $row["name"] . '</td>';
        echo '<td>' . $row["movie_type"] . '</td>';
        echo '<td>' . $row["duration"] . '</td>';
        echo '<td><a href="' . $row["youtube_link"] . '" target="_blank">Xem trên YouTube</a></td>';
        echo '<td><a href="' . $row["php_link"]. '" target="_blank">Xem chi tiết</a></td>';
        echo '<td><img src="' . $row["image"] . '" alt="Image" style="width: 100px; height: auto;"></td>';
        echo '<td>';
        echo '<button class="btn-edit" data-id="' . $row['id'] . '"';
        echo ' data-name="' . $row['name'] . '"';
        echo ' data-movie_type="' . $row['movie_type'] . '"';
        echo ' data-duration="' . $row['duration'] . '"';
        echo ' data-tinh_trang="' . $row['tinh_trang'] . '"';
        echo ' data-youtube_link="' . $row['youtube_link']. '"';
        echo ' data-php_link="' . $row['php_link'] . '">Sửa</button>';
        echo ' <button class="btn-delete" data-id="' . $row['id'] . '">Xóa</button>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo '<p>Không có phim nào trong danh sách.</p>';
}

mysqli_close($conn);
?>