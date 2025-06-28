<?php
session_start();
include 'config.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo "Bạn cần đăng nhập để thay đổi mật khẩu.";
    exit;
}

$id = $_SESSION['user_id'];

// Lấy thông tin người dùng
$result = mysqli_query($conn, "SELECT username, sdt, password FROM users WHERE id = $id");
$user = mysqli_fetch_assoc($result);

// Xử lý cập nhật mật khẩu
$message = "";

if (isset($_POST['update_password'])) {
    $currentPassword = mysqli_real_escape_string($conn, $_POST['current_password']);
    $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if ($user['password'] != $currentPassword) {
        $message = "Mật khẩu hiện tại không đúng.";
    } elseif ($newPassword != $confirmPassword) {
        $message = "Mật khẩu mới và xác nhận không khớp.";
    } else {
        $sql = "UPDATE users SET password = '$newPassword' WHERE id = $id";
        if (mysqli_query($conn, $sql)) {
            $message = "Đã cập nhật mật khẩu thành công.";
            header("Location: login.php");
        } else {
            $message = "Lỗi: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Thay đổi mật khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        .container {
            width: 400px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .info {
            margin-top: 10px;
            background: #e7f3ff;
            padding: 10px;
            border-radius: 6px;
            color: #333;
        }

        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 6px;
            background-color: #fff3cd;
            color: #856404;
        }

        input[type="submit"] {
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Thay đổi mật khẩu</h2>

        <div class="info">
            <strong>Tên tài khoản:</strong> <?php echo htmlspecialchars($user['username']); ?><br>
            <strong>Số điện thoại:</strong> <?php echo htmlspecialchars($user['sdt']); ?>
        </div>

        <?php if ($message != ""): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post">
            <label>Mật khẩu hiện tại:</label>
            <input type="password" name="current_password" required>

            <label>Mật khẩu mới:</label>
            <input type="password" name="new_password" required>

            <label>Nhập lại mật khẩu mới:</label>
            <input type="password" name="confirm_password" required>

            <input type="submit" name="update_password" value="Cập nhật mật khẩu">
        </form>
    </div>
</body>

</html>