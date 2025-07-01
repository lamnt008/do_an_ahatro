<?php
session_start();
include 'config.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header('Location: index.php');
    exit();
}

// Xử lý duyệt/từ chối tin
if (isset($_GET['action']) && isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'approve') {
        $sql = "UPDATE phong_tro SET status = 'approved' WHERE IDPhongTro = $post_id";
        $message = "Tin đã được duyệt thành công!";
    } elseif ($action == 'reject') {
        $sql = "UPDATE phong_tro SET status = 'rejected' WHERE IDPhongTro = $post_id";
        $message = "Tin đã bị từ chối!";
    }

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = $message;
    } else {
        $_SESSION['error'] = "Lỗi khi xử lý: " . mysqli_error($conn);
    }

    header("Location: admin_approve.php");
    exit();
}

// Lấy danh sách tin chờ duyệt
$pending_posts = mysqli_query($conn, "SELECT * FROM phong_tro WHERE status = 'pending' ORDER BY ThoiGianDang DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Quản lý duyệt tin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"> -->


</head>

<body>
    <?php include('header.php'); ?>

    <div class="main-layout">
        <?php include('siderbar.php'); ?>

        <div class="main-content">
            <h2>Danh sách tin chờ duyệt</h2>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['message'];
                unset($_SESSION['message']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <table class="table table-bordered">
                <thead style="text-align: center;">
                    <tr>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Người đăng</th>
                        <th>Ngày đăng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($post = mysqli_fetch_assoc($pending_posts)): ?>
                        <tr>
                            <td><?php echo $post['IDPhongTro']; ?></td>
                            <td><?php echo $post['TieuDe']; ?></td>
                            <td><?php echo $post['user_name']; ?></td>
                            <td><?php echo $post['ThoiGianDang']; ?></td>
                            <td>
                                <a href="room_detail.php?id=<?php echo $post['IDPhongTro']; ?>" class="btn btn-info">Xem</a>
                                <a href="admin_approve.php?action=approve&id=<?php echo $post['IDPhongTro']; ?>"
                                    class="btn btn-success">Duyệt</a>
                                <a href="admin_approve.php?action=reject&id=<?php echo $post['IDPhongTro']; ?>"
                                    class="btn btn-danger">Từ chối</a>
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