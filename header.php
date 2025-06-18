<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$current_category = isset($_GET['id']) ? $_GET['id'] : null;
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="header">
        <!-- Dòng 1: Logo và các nút chức năng -->
        <div class="top-bar">
            <div class="top-left">
                <a href="index.php">
                    <img src="img/logo2.PNG" alt="Logo" height="40">
                </a>
            </div>

            <div class="top-right">
                <div class="top-right-items">
                    <a href="saved_posts.php" class="top-link">
                        <i class="fa-solid fa-bookmark"></i> Đã lưu
                    </a>

                    <a href="post.php" class="post-button">
                        <i class="fa-solid fa-pen-to-square"></i> Đăng tin
                    </a>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="post_mana.php" class="top-link">
                            <i class="fa-solid fa-gear"></i> Quản lý
                        </a>
                    <?php endif; ?>

                    <div class="hdropdown">
                        <?php if (isset($_SESSION['user_name'])): ?>
                            <div class="hdropdown-toggle">
                                <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                                <i class="fa-solid fa-caret-down"></i>
                            </div>
                            <div class="hdropdown-menu">
                                <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                            </div>
                        <?php else: ?>
                            <div class="hdropdown-toggle">
                                <i class="fa-solid fa-user"></i> Tài khoản <i class="fa-solid fa-caret-down"></i>
                            </div>
                            <div class="hdropdown-menu">
                                <a href="login.php"><i class="fa-solid fa-right-to-bracket"></i> Đăng nhập</a>
                                <a href="register.php"><i class="fa-solid fa-user-plus"></i> Đăng ký</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dòng 2: Danh mục loại phòng -->
        <div class="category-bar">
            <?php
            include 'config.php';
            $sql = "SELECT * FROM loai_phong";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                echo '<ul class="category-menu">';
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['idLoaiPhong'];
                    $ten = $row['loaiPhong'];
                    $is_active = ($current_category == $id) ? 'active' : '';
                    echo '<li><a href="./category.php?id=' . $id . '" class="' . $is_active . '">' . htmlspecialchars($ten) . '</a></li>';
                }
                echo '</ul>';
            } else {
                echo '<div class="no-category">Không có loại phòng nào.</div>';
            }
            ?>
        </div>
    </div>
</body>

</html>