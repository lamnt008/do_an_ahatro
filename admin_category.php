<?php
include 'config.php';

$message = '';


if (isset($_POST['add'])) {
    $loaiPhong = trim($_POST['loaiPhong']);
    if ($loaiPhong != "") {
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM loai_phong WHERE loaiPhong = ?");
        $checkStmt->bind_param("s", $loaiPhong);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            $message = "Danh mục đã tồn tại, hãy nhập danh mục khác.";
        } else {
            $stmt = $conn->prepare("INSERT INTO loai_phong (loaiPhong) VALUES (?)");
            $stmt->bind_param("s", $loaiPhong);
            if ($stmt->execute()) {
                $message = "Thêm danh mục thành công.";
            } else {
                $message = "Có lỗi xảy ra khi thêm danh mục.";
            }
            $stmt->close();
        }
    }
}


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
            <h2>Quản lý danh mục loại phòng</h2>
            <?php if (!empty($message)): ?>
                <div class="alert alert-info"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="POST" class="form-inline" style="margin-bottom:20px;">
                <div class="form-group">
                    <label>Danh mục:</label>
                    <input type="text" name="loaiPhong" class="form-control" required>
                </div>
                <button type="submit" name="add" class="btn btn-success">Thêm</button>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Danh mục</th>
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