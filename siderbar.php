<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* CSS reset */
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 200px;
            background-color: #f1f1f1;
        }

        li a {
            display: block;
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s, color 0.3s;
        }

        li a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Style cho mục đang được chọn */
        li a.active {
            background-color: #4CAF50;
            color: white;
        }

        /* Loại bỏ border-bottom cho mục cuối cùng */
        li:last-child a {
            border-bottom: none;
        }
    </style>
</head>

<body>

    <ul>
        <li><a href="admin_post.php">Danh sách tin đăng</a></li>
        <li><a href="admin_approve.php">Tin chờ duyệt</a></li>
        <li><a href="admin_category.php">Quản lý danh mục</a></li>
        <li><a href="admin_users.php">Quản lý người dùng</a></li>
        <li><a href="account.php">Quản lý tài khoản</a></li>
        <li><a href="logout.php">Đăng xuất</a></li>
    </ul> -->









<!-- siderbar.php -->
<style>
    .sidebar {
        width: 200px;
        background-color: #f1f1f1;
        height: 100vh;
    }

    .sidebar ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

    .sidebar li a {
        display: block;
        color: #333;
        padding: 12px 16px;
        text-decoration: none;
        border-bottom: 1px solid #ddd;
        transition: background-color 0.3s, color 0.3s;
    }

    .sidebar li a:hover {
        background-color: #ddd;
        color: black;
    }

    .sidebar li a.active {
        background-color: #4CAF50;
        color: white;
    }

    .sidebar li:last-child a {
        border-bottom: none;
    }
</style>

<div class="sidebar">
    <ul>

        <li><a href="admin_approve.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_approve.php' ? 'active' : ''; ?>">Tin chờ
                duyệt</a></li>
        <li><a href="admin_category.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_category.php' ? 'active' : ''; ?>">Quản
                lý
                danh mục</a></li>
        <li><a href="admin_users.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin_users.php' ? 'active' : ''; ?>">Quản lý người
                dùng</a></li>
        <li><a href="account.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'account.php' ? 'active' : ''; ?>">Quản lý tài
                khoản</a></li>
        <li><a href="logout.php">Đăng xuất</a></li>
    </ul>
</div>











<!-- </body>

</html> -->