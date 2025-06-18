<?php
include 'config.php';

// Thêm mới
if (isset($_POST['add'])) {
    $loaiPhong = trim($_POST['loaiPhong']);
    if ($loaiPhong != "") {
        $stmt = $conn->prepare("INSERT INTO loai_phong (loaiPhong) VALUES (?)");
        $stmt->bind_param("s", $loaiPhong);
        $stmt->execute();
    }
}

// Xóa
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM loai_phong WHERE idLoaiPhong = $id");
}

$loai_phongs = $conn->query("SELECT * FROM loai_phong");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Quản lý danh mục loại phòng</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

</head>

<body>
    <?php include('header.php'); ?>

    <div class="main-layout">
        <?php include('siderbar.php'); ?>
        <div class="main-content">
            <!-- <div style="margin-left:220px; padding:20px;"> -->
            <h2>Quản lý loại phòng</h2>
            <form method="POST" class="form-inline" style="margin-bottom:20px;">
                <div class="form-group">
                    <label>Loại phòng:</label>
                    <input type="text" name="loaiPhong" class="form-control" required>
                </div>
                <button type="submit" name="add" class="btn btn-success">Thêm</button>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Loại phòng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $loai_phongs->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['idLoaiPhong']; ?></td>
                            <td><?php echo htmlspecialchars($row['loaiPhong']); ?></td>
                            <td>
                                <a href="ad_category_edit.php?id=<?php echo $row['idLoaiPhong']; ?>"
                                    class="btn btn-warning btn-sm">Sửa</a>
                                <a href="?delete=<?php echo $row['idLoaiPhong']; ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Xóa danh mục này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>