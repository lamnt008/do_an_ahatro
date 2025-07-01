<?php
include 'config.php';
include 'header.php';
// session_start();


$session_id = session_id();
if (empty($session_id)) {
    session_regenerate_id();
    $session_id = session_id();
}

$user_condition = isset($_SESSION['user_id'])
    ? "user_id = {$_SESSION['user_id']}"
    : "session_id = '$session_id'";

$sql_ids = "SELECT DISTINCT post_id FROM saved_posts WHERE $user_condition";
$result_ids = mysqli_query($conn, $sql_ids);
$total_saved = mysqli_num_rows($result_ids);

if ($total_saved > 0) {
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
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script> -->
    <style>
        .saved-posts-container {
            padding: 20px;
            margin-top: 118px;
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
    </style>
</head>

<body>
    <div class="saved-posts-container">
        <div class="saved-header">
            <h2>Bài đăng đã lưu</h2>
            <div class="saved-count"><?php echo $total_saved; ?> bài đã lưu</div>
        </div>

        <?php if ($total_saved > 0): ?>

            <div class="room-container" style="width: 50vw; margin: 10px auto;">
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

    <?php
    include 'footer.php';
    ?>

</body>

</html>