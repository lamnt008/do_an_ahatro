<?php
include "config.php";
session_start();


if (isset($_POST['DangTin'])) {

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $thoiGianDang = date('Y-m-d H:i:s');

    // 1. Thêm phòng trọ với status = 'pending'
    $sql_insert_phong_tro = 'INSERT INTO phong_tro(user_name, DiaChi, QuanHuyen, TinhThanh, TenChuTro, Sdt, 
    TieuDe, idLoaiPhong, KieuVeSinh, GiaChoThue, DienTich, GiaDien, GiaNuoc, DoiTuong, TienIch, MoTa, ThoiGianDang, status)
    VALUES (
        "' . $_SESSION['user_name'] . '",
        "' . $_POST['diaChi'] . '",
        "' . $_POST['quanHuyen'] . '",
        "' . $_POST['tinhThanh'] . '",
        "' . $_POST['tenChuTro'] . '",
        "' . $_POST['sdt'] . '",
        "' . $_POST['tieuDe'] . '",
        "' . $_POST['idLoaiPhong'] . '",
        "' . $_POST['VeSinh'] . '",
        "' . $_POST['gia'] . '",
        "' . $_POST['dienTich'] . '",
        "' . $_POST['dien'] . '",
        "' . $_POST['nuoc'] . '",
        "' . $_POST['doiTuong'] . '",
        "' . $_POST['tienIch'] . '",
        "' . $_POST['moTa'] . '",
        "' . $thoiGianDang . '",
        "pending"
    )';

    if (mysqli_query($conn, $sql_insert_phong_tro)) {
        $last_id = mysqli_insert_id($conn);

        // 2. Xử lý hình ảnh
        $target_dir = "uploads/";
        $num_of_imgs = count($_FILES['fileToUpload']['name']);

        for ($i = 0; $i < $num_of_imgs; $i++) {
            $file_tmp = $_FILES["fileToUpload"]["tmp_name"][$i];
            $file_name = basename($_FILES["fileToUpload"]["name"][$i]);
            $target_file = $target_dir . time() . "_" . $file_name;

            if (move_uploaded_file($file_tmp, $target_file)) {
                $sql_insert_img = 'INSERT INTO hinh_anh_phong_tro(IDPhongTro, DuongDan) VALUES("' . $last_id . '", "' . $target_file . '")';
                mysqli_query($conn, $sql_insert_img);
            }
        }

        // Thông báo cho người dùng
        $_SESSION['message'] = "Tin đăng của bạn đã được gửi đi và đang chờ kiểm duyệt từ quản trị viên.";
        header("Location: post_mana.php");
        exit();
    } else {
        echo "Lỗi khi thêm phòng trọ: " . mysqli_error($conn);
    }
}
?>