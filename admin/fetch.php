<table border="1">
    <caption>Bảng thông tin rạp chiếu</caption>
    <tr>
        <th>Tên rạp</th>
        <th>Địa điểm</th>
        <th>Chỉnh sửa</th>
    </tr>
    <?php
    include("../php/db.php");
    $sql = "SELECT * FROM cinema ORDER BY location ASC, id ASC";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td><?php echo $row["name"]; ?></td>
            <td><?php echo $row["location"]; ?></td>
            <td>
                <button class="btn-edit" data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['name']; ?>"
                    data-location="<?php echo $row['location']; ?>">Sửa</button>
                <button class="btn-delete" data-id="<?php echo $row['id']; ?>">Xóa</button>
            </td>
        </tr>
    <?php } ?>
</table>