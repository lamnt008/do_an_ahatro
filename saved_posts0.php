<?php
include 'config.php';
include 'header.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$saved_post_ids = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT IDPhongTro FROM tin_luu WHERE idUser = ? ORDER BY ThoiGianLuu DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $saved_post_ids[] = $row['IDPhongTro'];
        }
    }
    mysqli_stmt_close($stmt);
} else {
    if (isset($_SESSION['saved_posts']) && !empty($_SESSION['saved_posts'])) {
        $saved_post_ids = $_SESSION['saved_posts'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài đăng đã lưu</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h2 style="color:green">Bài đăng đã lưu của bạn</h2>
    <div class="saved-posts-list">
        <?php
        if (!empty($saved_post_ids)) {
            $ids_string = implode(',', array_map('intval', $saved_post_ids));
            $sql_posts = "SELECT pt.IDPhongTro, pt.QuanHuyen, pt.TinhThanh, pt.TieuDe, pt.GiaChoThue, pt.DienTich,
                          pt.user_name, pt.ThoiGianDang, lp.loaiPhong
                          FROM phong_tro pt
                          JOIN loai_phong lp ON pt.idLoaiPhong = lp.idLoaiPhong
                          WHERE pt.IDPhongTro IN ($ids_string)
                          ORDER BY FIELD(pt.IDPhongTro, $ids_string)";
            $result_posts = mysqli_query($conn, $sql_posts);

            if (mysqli_num_rows($result_posts) > 0) {
                while ($row = mysqli_fetch_assoc($result_posts)) {
                    include 'room.php';
                }
            } else {
                echo '<p>Không tìm thấy bài đăng nào đã lưu.</p>';
            }
        } else {
            echo '<p>Bạn chưa lưu bài đăng nào.</p>';
        }
        ?>
    </div>

    <script>
        $(document).ready(function () {
            // Hủy các sự kiện click cũ trước khi gán mới
            $(document).off('click', '.save-post-btn').on('click', '.save-post-btn', function () {
                var postId = $(this).data('post-id');
                var button = $(this);
                var roomItem = button.closest('.room-item');

                $.ajax({
                    url: 'save_post.php',
                    type: 'POST',
                    data: {
                        post_id: postId
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === 'success') {
                            if (data.action === 'saved') {
                                button.html('<i class="fas fa-bookmark" style="color: blue;"></i> Bỏ lưu');
                                alert(data.message);
                            } else if (data.action === 'unsaved') {
                                button.html('<i class="far fa-bookmark"></i> Lưu');
                                alert(data.message);
                                // Nếu đang ở trang saved_posts.php, ẩn bài đăng
                                if (roomItem.length) {
                                    roomItem.fadeOut('slow', function () {
                                        $(this).remove();
                                        if ($('.saved-posts-list .room-item').length === 0) {
                                            $('.saved-posts-list').html('<p>Bạn chưa lưu bài đăng nào.</p>');
                                        }
                                    });
                                }
                            }
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        console.error("Response Text:", xhr.responseText);
                        alert('Đã xảy ra lỗi khi thực hiện thao tác. Vui lòng thử lại.');
                    }
                });
            });
        });
    </script>
</body>

</html>