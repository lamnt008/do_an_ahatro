<?php
// Đảm bảo không có khoảng trắng hay output nào trước đây
if (ob_get_level() > 0) {
    ob_end_clean();
}

// Kết nối database trước
include 'config.php';

// Bắt đầu session nếu chưa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tắt tất cả output buffering
while (ob_get_level())
    ob_end_clean();

// Set header JSON
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

try {
    // Lấy và validate input
    $raw_input = file_get_contents('php://input');
    $data = json_decode($raw_input, true) ?? $_POST;

    $post_id = isset($data['post_id']) ? intval($data['post_id']) : 0;
    $action = isset($data['action']) ? trim($data['action']) : '';

    if ($post_id <= 0) {
        throw new Exception('ID bài đăng không hợp lệ');
    }
    if (!in_array($action, ['save', 'unsave'])) {
        throw new Exception('Hành động không hợp lệ');
    }

    // Xử lý transaction
    mysqli_begin_transaction($conn);

    if (isset($_SESSION['user_id'])) {
        $user_id = (int) $_SESSION['user_id'];
        $table_condition = "user_id = $user_id";
    } else {
        $session_id = mysqli_real_escape_string($conn, session_id());
        $table_condition = "session_id = '$session_id'";
    }

    if ($action === 'save') {
        // Kiểm tra trước khi lưu
        $check = mysqli_query($conn, "SELECT 1 FROM saved_posts WHERE $table_condition AND post_id = $post_id");
        if (!$check)
            throw new Exception(mysqli_error($conn));

        if (mysqli_num_rows($check) === 0) {
            $insert = mysqli_query($conn, "INSERT INTO saved_posts SET $table_condition, post_id = $post_id");
            if (!$insert)
                throw new Exception(mysqli_error($conn));
        }
        $response = ['success' => true, 'saved' => true];
    } else {
        $delete = mysqli_query($conn, "DELETE FROM saved_posts WHERE $table_condition AND post_id = $post_id");
        if (!$delete)
            throw new Exception(mysqli_error($conn));
        $response = ['success' => true, 'saved' => false];
    }

    mysqli_commit($conn);

    // Đảm bảo chỉ có 1 output duy nhất
    die(json_encode($response));

} catch (Exception $e) {
    if (isset($conn)) {
        mysqli_rollback($conn);
    }

    // Log lỗi ra file
    error_log('[' . date('Y-m-d H:i:s') . '] Save Post Error: ' . $e->getMessage() . "\n", 3, 'error_log.txt');

    // Trả về lỗi chuẩn
    header('HTTP/1.1 200 OK'); // Vẫn trả về 200 để jQuery xử lý
    die(json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error' => true
    ]));
}
?>