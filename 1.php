file room.php:
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
</div>










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



file save_post.php:
<?php
// Đảm bảo không có output trước khi bắt đầu
while (ob_get_level())
    ob_end_clean();

include 'config.php';

// Bắt đầu session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Thiết lập header JSON
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');

// Hàm gửi response JSON
function sendResponse($data)
{
    die(json_encode($data));
}

try {
    // Nhận dữ liệu từ POST
    $input = file_get_contents('php://input');
    $data = json_decode($input, true) ?: $_POST;

    // Debug - ghi log dữ liệu nhận được
    error_log('Received data: ' . print_r($data, true));

    // Validate ID bài đăng
    if (!isset($data['post_id']) || empty($data['post_id'])) {
        sendResponse(['success' => false, 'message' => 'Thiếu ID bài đăng']);
    }

    $post_id = (int) $data['post_id'];
    if ($post_id <= 0) {
        sendResponse(['success' => false, 'message' => 'ID bài đăng phải là số dương']);
    }

    // Validate action
    $action = isset($data['action']) ? trim($data['action']) : '';
    if (!in_array($action, ['save', 'unsave'])) {
        sendResponse(['success' => false, 'message' => 'Hành động không hợp lệ']);
    }

    // Kiểm tra bài đăng có tồn tại không
    $check_post = mysqli_query($conn, "SELECT 1 FROM phong_tro WHERE IDPhongTro = $post_id");
    if (!$check_post || mysqli_num_rows($check_post) === 0) {
        sendResponse(['success' => false, 'message' => 'Bài đăng không tồn tại']);
    }

    // Xử lý lưu/bỏ lưu
    if (isset($_SESSION['user_id'])) {
        $user_id = (int) $_SESSION['user_id'];
        $condition = "user_id = $user_id";
    } else {
        $session_id = mysqli_real_escape_string($conn, session_id());
        $condition = "session_id = '$session_id'";
    }

    if ($action === 'save') {
        // Kiểm tra đã lưu chưa
        $check = mysqli_query($conn, "SELECT 1 FROM saved_posts WHERE $condition AND post_id = $post_id");
        if (mysqli_num_rows($check) === 0) {
            $insert = mysqli_query($conn, "INSERT INTO saved_posts SET $condition, post_id = $post_id");
            if (!$insert)
                throw new Exception('Lỗi khi lưu bài: ' . mysqli_error($conn));
        }
        sendResponse(['success' => true, 'saved' => true, 'message' => 'Đã lưu bài đăng']);
    } else {
        $delete = mysqli_query($conn, "DELETE FROM saved_posts WHERE $condition AND post_id = $post_id");
        if (!$delete)
            throw new Exception('Lỗi khi bỏ lưu: ' . mysqli_error($conn));
        sendResponse(['success' => true, 'saved' => false, 'message' => 'Đã bỏ lưu bài đăng']);
    }

} catch (Exception $e) {
    sendResponse(['success' => false, 'message' => $e->getMessage()]);
}
?>

file saved_posts.php:
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

bạn xem các file này sai ở đâu mà khi tôi ấn "lưu bài" thì console log hiển thị: