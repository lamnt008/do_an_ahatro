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