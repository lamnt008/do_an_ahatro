<?php
include 'config.php';
session_start();

// Đảm bảo không có output trước khi gửi header
if (ob_get_length())
    ob_clean();

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Lỗi không xác định'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
        $post_id = (int) $_POST['post_id'];
        $current_action = $_POST['current_action'] ?? ''; // 'save' hoặc 'unsave'

        if ($post_id > 0) {
            if (isset($_SESSION['user_id'])) {
                // Xử lý cho user đã đăng nhập
                $user_id = $_SESSION['user_id'];

                if ($current_action === 'unsave') {
                    // Thực hiện bỏ lưu
                    $delete_sql = "DELETE FROM tin_luu WHERE idUser = ? AND IDPhongTro = ?";
                    $stmt = mysqli_prepare($conn, $delete_sql);
                    mysqli_stmt_bind_param($stmt, "ii", $user_id, $post_id);

                    if (mysqli_stmt_execute($stmt)) {
                        $response = [
                            'status' => 'success',
                            'action' => 'unsaved',
                            'message' => 'Đã bỏ lưu bài đăng.'
                        ];
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    // Thực hiện lưu bài
                    $insert_sql = "INSERT INTO tin_luu (idUser, IDPhongTro) VALUES (?, ?)";
                    $stmt = mysqli_prepare($conn, $insert_sql);
                    mysqli_stmt_bind_param($stmt, "ii", $user_id, $post_id);

                    if (mysqli_stmt_execute($stmt)) {
                        $response = [
                            'status' => 'success',
                            'action' => 'saved',
                            'message' => 'Đã lưu bài đăng thành công!'
                        ];
                    }
                    mysqli_stmt_close($stmt);
                }
            } else {
                // Xử lý cho user chưa đăng nhập
                $_SESSION['saved_posts'] = $_SESSION['saved_posts'] ?? [];

                if ($current_action === 'unsave') {
                    // Bỏ lưu
                    if (($key = array_search($post_id, $_SESSION['saved_posts'])) !== false) {
                        unset($_SESSION['saved_posts'][$key]);
                        $_SESSION['saved_posts'] = array_values($_SESSION['saved_posts']);
                        $response = [
                            'status' => 'success',
                            'action' => 'unsaved',
                            'message' => 'Đã bỏ lưu bài đăng.'
                        ];
                    }
                } else {
                    // Lưu bài
                    if (!in_array($post_id, $_SESSION['saved_posts'])) {
                        $_SESSION['saved_posts'][] = $post_id;
                        $response = [
                            'status' => 'success',
                            'action' => 'saved',
                            'message' => 'Đã lưu bài đăng vào phiên của bạn!'
                        ];
                    }
                }
            }
        }
    }
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit();
?>