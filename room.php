<?php
include 'config.php';
?>

<!-- <div class="room-container" style="width: 50vw; margin: 10px auto;"> -->
<div class="room-item">

    <a href="room_detail.php?id=<?php echo $row['IDPhongTro']; ?>">

        <?php
        $sql_select_image = 'SELECT DuongDan FROM hinh_anh_phong_tro WHERE IDPhongTro=' . $row['IDPhongTro'] . ' LIMIT 1';
        $result_img = mysqli_query($conn, $sql_select_image);
        if ($row_img = mysqli_fetch_assoc($result_img)) {
            echo '<img src="' . $row_img['DuongDan'] . '" alt="Hình phòng">';
        } else {
            echo '<img src="img/define_img.png" alt="Hình mặc định">';
        }
        ?>
    </a>

    <div class="room-info">
        <h3>
            <a href="room_detail.php?id=<?php echo $row['IDPhongTro']; ?>">
                <?php echo $row['TieuDe']; ?>
            </a>
        </h3>


        <p><strong>Vị trí:</strong> <?php echo $row['QuanHuyen'] . ", " . $row['TinhThanh']; ?></p>
        <p style="color: red; font-weight: bold;"><strong>Giá thuê:</strong>
            <?php echo number_format($row['GiaChoThue'], 0, ',', '.'); ?> VNĐ</p>
        <p><strong>Diện tích:</strong> <?php echo $row['DienTich']; ?> m<sup>2</sup></p>

        <div class="user-info">
            <?php
            // Lấy thời gian hiện tại
            $now = time();

            // Lấy thời gian từ CSDL (giả sử cột ThoiGianDang lưu theo định dạng YYYY-MM-DD HH:II:SS)
            $time_posted = strtotime($row['ThoiGianDang']);

            // Tính khoảng cách thời gian
            $time_diff = $now - $time_posted;

            if ($time_diff < 60) {
                $time_ago = $time_diff . " giây trước";
            } elseif ($time_diff < 3600) {
                $time_ago = floor($time_diff / 60) . " phút trước";
            } elseif ($time_diff < 86400) {
                $time_ago = floor($time_diff / 3600) . " giờ trước";
            } elseif ($time_diff < 604800) {
                $time_ago = floor($time_diff / 86400) . " ngày trước";
            } elseif ($time_diff < 2592000) {
                $time_ago = floor($time_diff / 604800) . " tuần trước";
            } elseif ($time_diff < 31536000) {
                $time_ago = floor($time_diff / 2592000) . " tháng trước";
            } else {
                $time_ago = floor($time_diff / 31536000) . " năm trước";
            }
            ?>
            <img src="img/user_icon.png" alt="">
            <div>
                <p style="font-weight: bold;"><?php echo $row['user_name']; ?></p>
                <p style="color: gray;"><?php echo $time_ago; ?></p>
            </div>














            <?php
            // Kiểm tra xem bài đăng đã được lưu chưa
            $is_saved = false;
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $check_saved = mysqli_query($conn, "SELECT id FROM saved_posts WHERE user_id = $user_id AND post_id = " . $row['IDPhongTro']);
                $is_saved = mysqli_num_rows($check_saved) > 0;
            } else {
                $session_id = session_id();
                $check_saved = mysqli_query($conn, "SELECT id FROM saved_posts WHERE session_id = '$session_id' AND post_id = " . $row['IDPhongTro']);
                $is_saved = mysqli_num_rows($check_saved) > 0;
            }
            ?>





            <button class="save-post-btn <?php echo $is_saved ? 'saved' : ''; ?>"
                data-post-id="<?php echo (int) $row['IDPhongTro']; ?>">
                <?php echo $is_saved ? '<i class="fas fa-bookmark"></i> Đã lưu' : '<i class="far fa-bookmark"></i> Lưu bài'; ?>
            </button>










        </div>
    </div>
</div>
<!-- </div> -->










<script>
    $(document).ready(function () {
        $(document).off('click', '.save-post-btn').on('click', '.save-post-btn', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var button = $(this);
            var postId = button.data('post-id');

            // Validate client-side trước
            if (!postId || isNaN(postId) || postId <= 0) {
                console.error('Invalid post ID:', postId);
                alert('ID bài đăng không hợp lệ');
                return;
            }

            var isSaved = button.hasClass('saved');

            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: 'save_post.php',
                type: 'POST',
                data: {
                    post_id: postId,
                    action: isSaved ? 'unsave' : 'save'
                },
                dataType: 'json',
                success: function (response) {
                    if (response && response.success !== undefined) {
                        button.toggleClass('saved');
                        button.html(response.saved ?
                            '<i class="fas fa-bookmark"></i> Đã lưu' :
                            '<i class="far fa-bookmark"></i> Lưu bài');

                        // Hiển thị thông báo
                        if (response.message) {
                            console.log(response.message);
                        }

                        // Xử lý cập nhật giao diện
                        if (!response.saved && window.location.pathname.includes('saved_posts.php')) {
                            button.closest('.room-item').fadeOut(300, function () {
                                $(this).remove();
                                updateSavedCount(-1);
                            });
                        }
                    } else {
                        alert(response?.message || 'Thao tác không thành công');
                    }
                },
                error: function (xhr) {
                    try {
                        var errorResponse = JSON.parse(xhr.responseText);
                        alert(errorResponse.message || 'Lỗi không xác định');
                    } catch (e) {
                        alert('Lỗi kết nối với server');
                    }
                    console.error('AJAX Error:', xhr.status, xhr.responseText);
                },
                complete: function () {
                    button.prop('disabled', false);
                }
            });
        });

        function updateSavedCount(change) {
            var countElement = $('.saved-count');
            var currentCount = parseInt(countElement.text()) || 0;
            countElement.text(Math.max(0, currentCount + change));
        }
    });
</script>