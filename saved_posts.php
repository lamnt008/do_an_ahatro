<?php
include 'config.php';
include 'header.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$saved_post_ids = [];
if (isset($_SESSION['user_id'])) {
    // Người dùng đã đăng nhập, lấy từ CSDL
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT IDPhongTro FROM tin_luu WHERE idUser = $user_id ORDER BY ThoiGianLuu DESC";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $saved_post_ids[] = $row['IDPhongTro'];
        }
    }
} else {
    // Người dùng chưa đăng nhập, lấy từ session
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
</head>

<body>
    <h2 style="color:green">Bài đăng đã lưu của bạn</h2>

    <div class="saved-posts-list">
        <?php
        if (!empty($saved_post_ids)) {
            $ids_string = implode(',', $saved_post_ids);
            $sql_posts = "SELECT pt.IDPhongTro, pt.QuanHuyen, pt.TinhThanh, pt.TieuDe, pt.GiaChoThue, pt.DienTich,
                          pt.user_name, pt.ThoiGianDang, lp.loaiPhong
                          FROM phong_tro pt
                          JOIN loai_phong lp ON pt.idLoaiPhong = lp.idLoaiPhong
                          WHERE pt.IDPhongTro IN ($ids_string)
                          ORDER BY FIELD(pt.IDPhongTro, $ids_string)"; // Giữ nguyên thứ tự ID đã lưu
        
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

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script>
        // Có thể cần lại script AJAX từ room.php nếu bạn muốn chức năng bỏ lưu cũng hoạt động trên trang này
        $(document).ready(function () {
            $('.save-post-btn').on('click', function () {
                var postId = $(this).data('post-id');
                var button = $(this);
                var roomItem = button.closest('.room-item'); // Lấy phần tử room-item cha để ẩn

                $.ajax({

                    url: 'save_post.php', // File PHP xử lý lưu/bỏ lưu
                    type: 'POST',
                    data: {
                        post_id: postId
                    },
                    success: function (response) {
                        // console.log("Response từ server:", response);
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            if (data.action === 'unsaved') {
                                alert(data.message + ". Bài đăng sẽ bị ẩn khỏi danh sách.");
                                roomItem.fadeOut('slow', function () {
                                    $(this).remove(); // Ẩn và xóa khỏi DOM sau khi animation kết thúc
                                    if ($('.saved-posts-list .room-item').length === 0) {
                                        // Nếu không còn bài đăng nào, hiển thị thông báo
                                        $('.saved-posts-list').html('<p>Bạn chưa lưu bài đăng nào.</p>');
                                    }
                                });
                            } else if (data.action === 'saved') {
                                // Trong trang saved_posts.php, action 'saved' không nên xảy ra
                                // Nhưng nếu có, bạn có thể xử lý nó
                                button.html('<i class="fas fa-bookmark" style="color: blue;"></i> Bỏ lưu');
                                alert(data.message);
                            }
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function () {
                        alert('Đã xảy ra lỗi khi thực hiện thao tác.');
                    }
                });
            });
        });
    </script>
</body>

</html>