<?php
// Kết nối CSDL
require_once 'config.php';

// Bắt đầu session
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $cpass = $_POST['confirm_password'];

    // Kiểm tra trống
    if (empty($username) || empty($email) || empty($pass) || empty($cpass)) {
        $errors[] = "Vui lòng điền đầy đủ thông tin.";
    }


    if (strpos($email, '@') === false) {
        $error[] = 'Email không hợp lệ. Vui lòng nhập email có ký tự @.';
    } else {
        $select = "SELECT * FROM users WHERE username='$username' or email ='$email'";
        $result = mysqli_query($conn, $select);

        if (mysqli_num_rows($result) > 0) {
            $error[] = 'Tên người dùng hoặc email đã tồn tại';
        } else {
            if ($pass != $cpass) {
                $error[] = 'Mật khẩu không giống nhau!';
            } else {
                $insert = "INSERT INTO users(username, password, email) VALUES('$username','$pass','$email')";
                mysqli_query($conn, $insert);
                header('location:index.php');
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>





</head>

<body>
    <h2>Đăng ký tài khoản</h2>

    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $e): ?>
                <li><?php echo htmlspecialchars($e); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Tên đăng nhập:</label><br>
        <input type="text" name="username"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email"><br><br>

        <label>Mật khẩu:</label><br>
        <input type="password" name="password"><br><br>

        <label>Nhập lại mật khẩu:</label><br>
        <input type="password" name="confirm_password"><br><br>

        <button type="submit">Đăng ký</button>
    </form>
</body>

</html>