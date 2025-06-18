<?php
include 'config.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
ob_clean();
header('Content-Type: application/json'); // Trả về JSON response
$response = ['status' => 'error', 'message' => 'Lỗi không xác định'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);
    if ($post_id > 0) {
        // Kiểm tra xem người dùng đã đăng nhập hay chưa
        if (isset($_SESSION['user_id'])) {
            // Người dùng đã đăng nhập, lưu vào CSDL
            $user_id = $_SESSION['user_id']; // Thay bằng tên session chứa ID người dùng của bạn
            // Kiểm tra xem bài đăng đã được lưu chưa
            $check_sql = "SELECT * FROM tin_luu WHERE idUser = ? AND IDPhongTro= ?";
            $stmt_check = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $post_id);
            mysqli_stmt_execute($stmt_check);
            $check_result = mysqli_stmt_get_result($stmt_check);
            if (mysqli_num_rows($check_result) > 0) {
                // Đã lưu, thực hiện bỏ lưu
                $delete_sql = "DELETE FROM tin_luu WHERE idUser = ? AND IDPhongTro = ?";
                $stmt_delete = mysqli_prepare($conn, $delete_sql);
                mysqli_stmt_bind_param($stmt_delete, "ii", $user_id, $post_id);
                if (mysqli_stmt_execute($stmt_delete)) {
                    $response = ['status' => 'success', 'action' => 'unsaved', 'message' => 'Đã bỏ lưu bài đăng.'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Không thể bỏ lưu bài đăng.'];
                }
                mysqli_stmt_close($stmt_delete);
            } else {
                // Chưa lưu, thực hiện lưu
                $insert_sql = "INSERT INTO tin_luu (idUser, IDPhongTro) VALUES (?, ?)";
                $stmt_insert = mysqli_prepare($conn, $insert_sql);
                mysqli_stmt_bind_param($stmt_insert, "ii", $user_id, $post_id);
                if (mysqli_stmt_execute($stmt_insert)) {
                    $response = ['status' => 'success', 'action' => 'saved', 'message' => 'Đã lưu bài đăng thành công!'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Không thể lưu bài đăng.'];
                }
                mysqli_stmt_close($stmt_insert);
            }
            mysqli_stmt_close($stmt_check);

        } else {
            // Người dùng chưa đăng nhập, lưu vào session
            if (!isset($_SESSION['saved_posts'])) {
                $_SESSION['saved_posts'] = [];
            }

            if (in_array($post_id, $_SESSION['saved_posts'])) {
                // Đã lưu, thực hiện bỏ lưu
                $_SESSION['saved_posts'] = array_diff($_SESSION['saved_posts'], [$post_id]);
                $response = ['status' => 'success', 'action' => 'unsaved', 'message' => 'Đã bỏ lưu bài đăng.'];
            } else {
                // Chưa lưu, thực hiện lưu
                $_SESSION['saved_posts'][] = $post_id;
                $response = ['status' => 'success', 'action' => 'saved', 'message' => 'Đã lưu bài đăng vào phiên của bạn!'];
            }
            // Đảm bảo không có ID trùng lặp nếu có
            $_SESSION['saved_posts'] = array_values(array_unique($_SESSION['saved_posts']));
        }
    } else {
        $response = ['status' => 'error', 'message' => 'ID bài đăng không hợp lệ.'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Yêu cầu không hợp lệ.'];
}
echo json_encode($response);
exit();
?>