<?php
require_once 'config.php';
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $errors[] = "Vui lòng điền đầy đủ thông tin.";
    } else {
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['username'];
            $_SESSION['user_role'] = $row['role'];
            if ($_SESSION['user_role'] == "admin") {
                header("Location: admin_approve.php");
            } else {
                header("Location: index.php");
            }


        } else {
            $errors[] = "Tên đăng nhập hoặc mật khẩu không đúng!";
        }

    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        ul {
            padding: 0;
            list-style: none;
            margin-bottom: 15px;
        }

        ul li {
            color: red;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Đăng nhập</h2>

        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Tên đăng nhập:</label>
            <input type="text" name="username">

            <label>Mật khẩu:</label>
            <input type="password" name="password">

            <button type="submit">Đăng nhập</button>
        </form>
    </div>
</body>

</html>