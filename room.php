<?php
include 'config.php';
?>

<div class="room-container" style="width: 50vw; margin: 10px auto;">
    <div class="room-item">

        <a href="room_detail.php?id=<?php echo $row['IDPhongTro']; ?>">

            <?php
            $sql_select_image = 'SELECT DuongDan FROM hinh_anh_phong_tro WHERE IDPhongTro=' . $row['IDPhongTro'] . ' LIMIT 1';
            $result_img = mysqli_query($conn, $sql_select_image);
            if ($row_img = mysqli_fetch_assoc($result_img)) {
                echo '<img src="' . $row_img['DuongDan'] . '" alt="Hình phòng">';
            } else {
                echo '<img src="images/icon-account.png" alt="Hình mặc định">';
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
                <img src="images/user_icon.png" alt="">
                <div>
                    <p style="font-weight: bold;"><?php echo $row['user_name']; ?></p>
                    <p style="color: gray;"><?php echo $time_ago; ?></p>
                </div>





                <div class="save-post-container">
                    <?php
                    $is_saved = false;
                    if (isset($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];
                        $post_id = $row['IDPhongTro'];
                        $check_sql = "SELECT * FROM tin_luu WHERE idUser = $user_id AND IDPhongTro = $post_id";
                        $check_result = mysqli_query($conn, $check_sql);
                        $is_saved = mysqli_num_rows($check_result) > 0;
                    } elseif (isset($_SESSION['saved_posts']) && in_array($row['IDPhongTro'], $_SESSION['saved_posts'])) {
                        $is_saved = true;
                    }
                    ?>
                    <button class="save-post-btn" data-post-id="<?php echo $row['IDPhongTro']; ?>"
                        data-is-saved="<?php echo $is_saved ? 'true' : 'false'; ?>">
                        <?php if ($is_saved): ?>
                            <i class="fas fa-bookmark" style="color: blue;"></i> Bỏ lưu
                        <?php else: ?>
                            <i class="far fa-bookmark"></i> Lưu
                        <?php endif; ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>













<script>
    $(document).ready(function () {
        $('.save-post-btn').on('click', function (e) {
            e.preventDefault();
            var button = $(this);
            var postId = button.data('post-id');
            var isCurrentlySaved = button.data('is-saved') === 'true';

            // Hiệu ứng loading
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');

            $.ajax({
                url: 'save_post.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    post_id: postId,
                    current_action: isCurrentlySaved ? 'unsave' : 'save'
                },
                success: function (response) {
                    console.log("Server Response:", response);

                    if (response.status === 'success') {
                        // Cập nhật giao diện
                        if (response.action === 'saved') {
                            button.html('<i class="fas fa-bookmark" style="color: blue;"></i> Bỏ lưu');
                            button.data('is-saved', 'true');
                        } else {
                            button.html('<i class="far fa-bookmark"></i> Lưu');
                            button.data('is-saved', 'false');

                            // Ẩn bài đăng nếu ở trang saved_posts
                            if (window.location.pathname.includes('saved_posts.php')) {
                                button.closest('.room-item').fadeOut('slow', function () {
                                    $(this).remove();
                                    if ($('.saved-posts-list .room-item').length === 0) {
                                        $('.saved-posts-list').html('<p>Bạn chưa lưu bài đăng nào.</p>');
                                    }
                                });
                            }
                        }

                        // Hiển thị thông báo thành công
                        alert(response.message);
                    } else {
                        // Hiển thị thông báo lỗi chi tiết
                        alert('Lỗi: ' + response.message);

                        // Khôi phục trạng thái nút nếu có lỗi
                        button.html(isCurrentlySaved
                            ? '<i class="fas fa-bookmark" style="color: blue;"></i> Bỏ lưu'
                            : '<i class="far fa-bookmark"></i> Lưu');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    alert('Lỗi kết nối máy chủ. Vui lòng thử lại.');

                    // Khôi phục trạng thái nút
                    button.html(isCurrentlySaved
                        ? '<i class="fas fa-bookmark" style="color: blue;"></i> Bỏ lưu'
                        : '<i class="far fa-bookmark"></i> Lưu');
                },
                complete: function () {
                    button.prop('disabled', false);
                }
            });
        });
    });
</script>