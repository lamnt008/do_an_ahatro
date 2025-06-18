<?php
include 'config.php';

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM loai_phong WHERE idLoaiPhong = $id");
$loai_phong = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $newName = trim($_POST['loaiPhong']);
    if ($newName != "") {
        $stmt = $conn->prepare("UPDATE loai_phong SET loaiPhong = ? WHERE idLoaiPhong = ?");
        $stmt->bind_param("si", $newName, $id);
        $stmt->execute();
        header("Location: admin_category.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sửa loại phòng</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>

<body>
    <?php include('header.php'); ?>

    <div class="main-layout">
        <?php include('siderbar.php'); ?>

        <div class="main-content">
            <h2>Cập nhật danh mục</h2>

            <form method="POST" class="form-inline">
                <div class="form-group">
                    <label>Danh mục:</label>
                    <input type="text" name="loaiPhong" class="form-control"
                        value="<?php echo htmlspecialchars($loai_phong['loaiPhong']); ?>" required>
                </div>
                <button type="submit" name="update" class="btn btn-primary">Cập nhật</button>
                <a href="admin_category.php" class="btn btn-default">Quay lại</a>
            </form>
        </div>
    </div>
</body>

</html>