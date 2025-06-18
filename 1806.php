<?php
session_start();
require 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $sdt = trim($_POST['sdt']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra dữ liệu
    if (empty($username) || empty($sdt) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "Vui lòng điền đầy đủ tất cả các trường.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Mật khẩu nhập lại không khớp.";
    }

    // Kiểm tra username hoặc email đã tồn tại
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $errors[] = "Tên đăng nhập hoặc email đã tồn tại.";
    }

    // Nếu không có lỗi thì thêm vào DB
    if (empty($errors)) {
        $role = 'user'; // mặc định là user

        $stmt = $conn->prepare("INSERT INTO users (username, password, email, role, sdt) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $password, $email, $role, $sdt);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Đăng ký thành công!";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Lỗi khi đăng ký: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f4f7;
        }

        .register-container {
            max-width: 450px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>Đăng ký tài khoản</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $err): ?>
                    <div><?= $err ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Tên người dùng</label>
                <input type="text" name="username" class="form-control"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="sdt" class="form-control" value="<?= htmlspecialchars($_POST['sdt'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Nhập lại mật khẩu</label>
                <input type="password" name="confirm_password" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
            <div class="text-center mt-3">
                <a href="login.php">Đã có tài khoản? Đăng nhập</a>
            </div>
        </form>
    </div>
</body>

</html>