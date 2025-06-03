<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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

        <div class="top-bar">

            <a href="index.php">
                <img src="img/logo2.PNG" alt="Logo" height="40">
            </a>



            <?php
            include 'config.php';

            $sql = "SELECT * FROM loai_phong";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                echo '<ul class="menu">';
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['idLoaiPhong'];
                    $ten = $row['loaiPhong'];


                    echo '<li style="font-size: 16px;"><a href="./category.php?id= ' . $id . '">' . htmlspecialchars($ten) . '</a></li>';
                }
                echo '</ul>';
            } else {
                echo 'Không có loại phòng nào.';
            }
            ?>



            <div class="top-right">
                <a href="saved_posts.php" style="color: white; font-weight: bold; margin-bottom: 10px">
                    <i class="fa-solid fa-bookmark"></i> Đã lưu
                </a>

                <a href="post.php" class="post-button">Đăng tin</a>

                <div class="hdropdown">
                    <!-- <img class="avatar" src="img/user_icon.png" alt="Avatar"> -->
                    <div class="hdropdown-toggle"><i class="fa-solid fa-user-tie"></i> Tài khoản
                        &nabla;
                    </div>
                    <div class="hdropdown-menu">
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="login.php">Đăng nhập</a>
                            <a href="register.php">Đăng ký</a>
                        <?php else: ?>
                            <a href="post_mana.php">Quản lý tin đăng</a>

                            <a href="logout.php">Đăng xuất</a>
                        <?php endif; ?>
                    </div>
                </div>



            </div>




        </div>












    </div>

</body>

</html>