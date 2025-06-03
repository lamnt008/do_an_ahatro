<?php
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = $user_id_tu_database; // Thiết lập ID người dùng vào session

    // Kiểm tra nếu có bài đăng đã lưu trong session trước khi đăng nhập
    if (isset($_SESSION['saved_posts']) && !empty($_SESSION['saved_posts'])) {
        $user_id = $_SESSION['user_id'];
        foreach ($_SESSION['saved_posts'] as $post_id) {
            // Kiểm tra xem bài đăng đã tồn tại trong CSDL cho người dùng này chưa
            $check_sql = "SELECT * FROM tin_luu WHERE idUser = ? AND IDPhongTro = ?";
            $stmt_check = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $post_id);
            mysqli_stmt_execute($stmt_check);
            $check_result = mysqli_stmt_get_result($stmt_check);

            if (mysqli_num_rows($check_result) == 0) {
                // Nếu chưa tồn tại, thêm vào CSDL
                $insert_sql = "INSERT INTO tin_luu (idUser, IDPhongTro) VALUES (?, ?)";
                $stmt_insert = mysqli_prepare($conn, $insert_sql);
                mysqli_stmt_bind_param($stmt_insert, "ii", $user_id, $post_id);
                mysqli_stmt_execute($stmt_insert);
                mysqli_stmt_close($stmt_insert);
            }
            mysqli_stmt_close($stmt_check);
        }
        // Xóa các bài đăng đã lưu khỏi session sau khi đã chuyển vào CSDL
        unset($_SESSION['saved_posts']);
    }

    // Chuyển hướng người dùng đến trang chính hoặc trang đã lưu
    header("Location: index.php"); // Hoặc trang bất kỳ
    exit();
}
// ...
?>