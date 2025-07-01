<?php
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = $user_id_tu_database;


    if (isset($_SESSION['saved_posts']) && !empty($_SESSION['saved_posts'])) {
        $user_id = $_SESSION['user_id'];
        foreach ($_SESSION['saved_posts'] as $post_id) {

            $check_sql = "SELECT * FROM tin_luu WHERE idUser = ? AND IDPhongTro = ?";
            $stmt_check = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $post_id);
            mysqli_stmt_execute($stmt_check);
            $check_result = mysqli_stmt_get_result($stmt_check);

            if (mysqli_num_rows($check_result) == 0) {

                $insert_sql = "INSERT INTO tin_luu (idUser, IDPhongTro) VALUES (?, ?)";
                $stmt_insert = mysqli_prepare($conn, $insert_sql);
                mysqli_stmt_bind_param($stmt_insert, "ii", $user_id, $post_id);
                mysqli_stmt_execute($stmt_insert);
                mysqli_stmt_close($stmt_insert);
            }
            mysqli_stmt_close($stmt_check);
        }

        unset($_SESSION['saved_posts']);
    }


    header("Location: index.php");
    exit();
}
// ...
?>