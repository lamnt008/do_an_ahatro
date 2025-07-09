<?php
session_start();
require 'config.php';


// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Xử lý các hành động
$action = $_GET['action'] ?? '';
$user_id = $_GET['id'] ?? 0;

if ($action && $user_id) {
    switch ($action) {
        // case 'toggle_trangThai':
        //     $stmt = $conn->prepare("UPDATE users SET trangThai = IF(trangThai='active', 'banned', 'active') WHERE id = ?");
        //     $stmt->bind_param("i", $user_id);
        //     $stmt->execute();
        //     $_SESSION['message'] = "Đã thay đổi trạng thái người dùng";
        //     break;

        // case 'reset_password':
        //     $new_password = password_hash("123456", PASSWORD_DEFAULT);
        //     $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        //     $stmt->bind_param("si", $new_password, $user_id);
        //     $stmt->execute();
        //     $_SESSION['message'] = "Đã reset mật khẩu về 123456";
        //     break;

        case 'change_role':
            $stmt = $conn->prepare("UPDATE users SET role = IF(role='admin', 'user', 'admin') WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $_SESSION['message'] = "Đã thay đổi vai trò người dùng";
            break;

        case 'delete':
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $_SESSION['message'] = "Đã xóa người dùng";
            break;
    }

    header("Location: admin_users.php");
    exit();
}

// Lấy danh sách người dùng
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM users";
if ($search) {
    $query .= " WHERE username LIKE ? OR email LIKE ? OR sdt LIKE ?";
    $search_param = "%$search%";
}

// $query .= " ORDER BY created_at DESC";
$stmt = $conn->prepare($query);

if ($search) {
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
}

$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Người Dùng</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <style>
        .action-btns .btn {
            margin: 2px;
        }
    </style>
</head>

<body>
    <?php include('header.php'); ?>

    <div class="main-layout">
        <?php include('siderbar.php'); ?>

        <div class="main-content">
            <h2 class="mb-4">Quản Lý Người Dùng</h2>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-info"><?= $_SESSION['message'] ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>


            <div class="card mb-4">
                <div class="card-body">
                    <form method="get" class="row g-3">
                        <div class="col-md-8">
                            <input type="text" name="search" class="form-control"
                                placeholder="Tìm theo username, email hoặc số điện thoại"
                                value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                        <div class="col-md-2">
                            <a href="admin_users.php" class="btn btn-secondary">Làm mới</a>
                        </div>
                    </form>
                </div>
            </div>


            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Số ĐT</th>
                                    <th>Vai trò</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td><?= htmlspecialchars($user['sdt']) ?? 'N/A' ?></td>
                                        <td>
                                            <span class="badge bg-<?= $user['role'] === 'admin' ? 'success' : 'primary' ?>">
                                                <?= $user['role'] === 'admin' ? 'Admin' : 'User' ?>
                                            </span>
                                        </td>

                                        <td class="action-btns">

                                            <a href="admin_users.php?action=change_role&id=<?= $user['id'] ?>"
                                                class="btn btn-sm btn-<?= $user['role'] === 'admin' ? 'primary' : 'success' ?>">
                                                <?= $user['role'] === 'admin' ? 'Hạ quyền' : 'Thăng Admin' ?>
                                            </a>
                                            <a href="admin_users.php?action=delete&id=<?= $user['id'] ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Xóa người dùng này?')">
                                                Xóa
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>