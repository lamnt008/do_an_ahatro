<?php
include 'config.php';
session_start();

// Kiểm tra session
$session_id = session_id();
if (empty($session_id)) {
    session_regenerate_id();
    $session_id = session_id();
}

// Truy vấn chính xác
$user_condition = isset($_SESSION['user_id'])
    ? "user_id = {$_SESSION['user_id']}"
    : "session_id = '$session_id'";

// Lấy danh sách ID bài đăng KHÔNG TRÙNG LẶP
$sql_ids = "SELECT DISTINCT post_id FROM saved_posts WHERE $user_condition";
$result_ids = mysqli_query($conn, $sql_ids);
$total_saved = mysqli_num_rows($result_ids); // Số lượng thực tế

if ($total_saved > 0) {
    // Lấy thông tin chi tiết các bài đăng
    $post_ids = [];
    while ($row = mysqli_fetch_assoc($result_ids)) {
        $post_ids[] = $row['post_id'];
    }
    $ids_string = implode(",", $post_ids);

    $sql_posts = "SELECT pt.*, lp.loaiPhong 
                 FROM phong_tro pt
                 JOIN loai_phong lp ON pt.idLoaiPhong = lp.idLoaiPhong
                 WHERE pt.IDPhongTro IN ($ids_string)
                 ORDER BY FIELD(pt.IDPhongTro, $ids_string)";

    $result_posts = mysqli_query($conn, $sql_posts);
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài đăng đã lưu</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .saved-posts-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .saved-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .saved-count {
            font-size: 18px;
            color: #555;
        }

        .no-saved-posts {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #777;
        }

        .room-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
    </style>
</head>

<body>
    <div class="saved-posts-container">
        <div class="saved-header">
            <h1>Bài đăng đã lưu</h1>
            <div class="saved-count"><?php echo $total_saved; ?> bài đã lưu</div>
        </div>

        <?php if ($total_saved > 0): ?>
            <div class="room-list">
                <?php while ($row = mysqli_fetch_assoc($result_posts)): ?>
                    <?php
                    $room_row = $row;
                    include 'room.php';
                    ?>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-saved-posts">
                <p>Bạn chưa lưu bài đăng nào.</p>
                <a href="index.php" class="btn btn-primary">Khám phá bài đăng</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>