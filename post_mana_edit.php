<?php
session_start();

if (isset($_GET['id'])) {
    $id_tro = $_GET['id'];
} else {
    echo "ID phòng trọ không được cung cấp!";
    exit();
}

include('config.php');

$sql = 'SELECT * FROM phong_tro WHERE id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_tro);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $room = $result->fetch_assoc();
} else {
    echo "Phòng trọ không tồn tại!";
    exit();
}


if (isset($_POST['update'])) {

    $diaChi = mysqli_real_escape_string($conn, $_POST['diaChi']);
    $quanHuyen = mysqli_real_escape_string($conn, $_POST['quanHuyen']);
    $tinhThanh = mysqli_real_escape_string($conn, $_POST['tinhThanh']);
    $chuTro = mysqli_real_escape_string($conn, $_POST['chuTro']);
    $sdt = mysqli_real_escape_string($conn, $_POST['sdt']);
    $tieuDe = mysqli_real_escape_string($conn, $_POST['tieuDe']);
    $idLoaiPhong = mysqli_real_escape_string($conn, $_POST['idLoaiPhong']);
    $veSinh = mysqli_real_escape_string($conn, $_POST['veSinh']);
    $gia = mysqli_real_escape_string($conn, $_POST['giaThue']);
    $dienTich = mysqli_real_escape_string($conn, $_POST['dienTich']);
    $dien = mysqli_real_escape_string($conn, $_POST['dien']);
    $nuoc = mysqli_real_escape_string($conn, $_POST['nuoc']);
    $doiTuong = mysqli_real_escape_string($conn, $_POST['doiTuong']);
    $tienIch = mysqli_real_escape_string($conn, $_POST['tienIch']);
    $moTa = mysqli_real_escape_string($conn, $_POST['moTa']);



    $update_sql = "UPDATE phong_tro SET diaChi = ?, quanHuyen = ?, tinhThanh = ? , chuTro = ?, sdt = ?,
    tieuDe = ?, idLoaiPhong = ?, veSinh = ?, giaThue = ?, dienTich = ?, dien = ?, nuoc = ?,
     doiTuong = ?, tienIch = ?, moTa = ?, trangThai = 'cho_duyet' WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param(
        "ssssssisiisssssi",
        $diaChi,
        $quanHuyen,
        $tinhThanh,
        $chuTro,
        $sdt,
        $tieuDe,
        $idLoaiPhong,
        $veSinh,
        $gia,
        $dienTich,
        $dien,
        $nuoc,
        $doiTuong,
        $tienIch,
        $moTa,
        $id_tro
    );

    if ($update_stmt->execute()) {
        echo "Phòng đã được cập nhật thành công!";
        header("Location: post_mana.php");
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
if (isset($_POST['delete'])) {
    $conn->begin_transaction();

    $delete2_sql = "DELETE FROM hinh_anh_phong_tro WHERE IDPhongTro = ?";
    $delete2_stmt = $conn->prepare($delete2_sql);
    $delete2_stmt->bind_param("i", $id_tro);


    $delete_sql = "DELETE FROM phong_tro WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);

    $delete_stmt->bind_param("i", $id_tro);

    if ($delete2_stmt->execute() && $delete_stmt->execute()) {

        $conn->commit();
        echo "<script>
                alert('Phòng đã được xóa thành công!');
                window.location.href = 'post_mana.php';
              </script>";
        exit();
    } else {
        $conn->rollback();
        echo "<script>alert('Lỗi xóa phòng: " . $conn->error . "');</script>";
    }

}
if (isset($_POST['themanh'])) {
    if (isset($_FILES['image'])) {
        $total = count($_FILES['image']['name']);
        for ($i = 0; $i < $total; $i++) {
            if ($_FILES['image']['error'][$i] === 0) {
                $tmpName = $_FILES['image']['tmp_name'][$i];
                $fileName = basename($_FILES['image']['name'][$i]);
                $imagePath = 'uploads/' . $fileName;

                if (move_uploaded_file($tmpName, $imagePath)) {
                    $add_anh_sql = "INSERT INTO hinh_anh_phong_tro (IDPhongTro, DuongDan) VALUES (?, ?)";
                    $add_anh_stmt = $conn->prepare($add_anh_sql);
                    $add_anh_stmt->bind_param("is", $id_tro, $imagePath);
                    $add_anh_stmt->execute();
                }
            }
        }
        $message = "Đã thêm ảnh thành công!";
    }


}


if (isset($_POST['xoaanh'])) {

    $check_anh_sql = "SELECT COUNT(*) AS count FROM hinh_anh_phong_tro WHERE IDPhongTro = ?";
    $check_anh_stmt = $conn->prepare($check_anh_sql);
    $check_anh_stmt->bind_param("i", $id_tro);
    $check_anh_stmt->execute();
    $result = $check_anh_stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {

        $delete_anh_sql = "DELETE FROM hinh_anh_phong_tro WHERE IDPhongTro = ?";
        $delete_anh_stmt = $conn->prepare($delete_anh_sql);
        $delete_anh_stmt->bind_param("i", $id_tro);

        if ($delete_anh_stmt->execute()) {
            $message = "Tất cả ảnh đã được xóa thành công!";
        } else {
            $message = "Lỗi: " . $conn->error;
        }
    } else {
        $message = "Không có ảnh nào để xóa.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Phòng Chi Tiết</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">



    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="styles/CSS.css">
    <style type="text/css">
        h4 {
            color: green;
        }

        input,
        select,
        textarea {
            background-color: #f8f8f8;
            border: solid #32cc66 1px;
            padding: 5px 0px;
            width: 100%;
        }

        input {
            padding: 5px 10px;
        }

        .notes {
            background-color: #dff0d8;
            padding: 5px 10px;
        }

        h4 span {
            color: red;
        }

        .view_images {
            height: 200px;
        }
    </style>

</head>

<body>

    <?php
    include('header.php');
    ?>



    <div class="container">
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="row">
                <form method="POST" onsubmit="return confirmDelete();"
                    action="post_mana_edit.php?id=<?php echo $id_tro; ?>" enctype="multipart/form-data">
                    <div class="col-xs-12">
                        <h3>Các thông tin cơ bản</h3>
                    </div>

                    <div class="col-xs-12">
                        <div class="col-xs-12">
                            <h4>Mã tin</h4>
                        </div>
                        <div class="col-xs-12">
                            <input readonly id="id_post" type="text" name="id_post" maxlength="80" style="width: 100%"
                                value="<?php echo htmlspecialchars($room['id']); ?>">
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="col-xs-12">
                            <h4>Tiêu đề tin <span>*</span> <span class="error_input" id="error_input_title"></span></h4>
                        </div>
                        <div class="col-xs-12">
                            <input id="input_title" type="text" name="tieuDe" maxlength="80" style="width: 100%"
                                placeholder="Hãy đặt tiêu đề đầy đủ ý nghĩa, khách sẽ quan tâm hơn" title="Tiêu đề"
                                value="<?php echo htmlspecialchars($room['tieuDe']); ?>">
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <h4 class="col-xs-12">Chọn loại phòng <span>*</span> <span class="error_input"
                                id="error_input_kind_of_room"></span></h4>
                        <?php
                        $sql_loaiPhong = "SELECT idLoaiPhong, loaiPhong FROM loai_phong";
                        $result_loaiPhong = mysqli_query($conn, $sql_loaiPhong);
                        while ($row = mysqli_fetch_assoc($result_loaiPhong)) {
                            $checked = ($row['idLoaiPhong'] == $room['idLoaiPhong']) ? 'checked' : '';
                            ?>
                            <div class="col-sm-6 col-xs-12">
                                <input name="idLoaiPhong" type="radio" value="<?php echo $row['idLoaiPhong']; ?>" <?php echo $checked; ?> style="width: 13px;" />
                                <?php echo $row['loaiPhong']; ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <div class="col-xs-12">
                        <h4 class="col-xs-12">Kiểu vệ sinh <span>*</span> <span class="error_input"
                                id="error_input_kind_of_toilet"></span></h4>
                        <div class="col-sm-6 col-xs-12">
                            <input name="veSinh" type="radio" value="Khép kín" style="width: 13px;" <?php if ($room['veSinh'] == 'Khép kín')
                                echo 'checked'; ?> /> Khép kín
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <input name="veSinh" type="radio" value="Không khép kín" style="width: 13px;" <?php if ($room['veSinh'] == 'Không khép kín')
                                echo 'checked'; ?> /> Không khép kín
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="col-xs-12">
                            <h4>Giá cho thuê <span>*</span> <span class="error_input"
                                    id="error_input_room_price"></span></h4>
                        </div>
                        <div class="col-xs-12">
                            <input id="input_room_price" type="number" name="giaThue" min="0"
                                placeholder="Giá cho thuê(VNĐ)" title="Giá thuê phòng"
                                value="<?php echo htmlspecialchars($room['giaThue']); ?>">
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="col-xs-12">
                            <h4>Diện tích <span>*</span> <span class="error_input" id="error_input_room_area"></span>
                            </h4>
                        </div>
                        <div class="col-xs-12">
                            <input id="input_room_area" type="number" name="dienTich" min="0"
                                placeholder="Diện tích(đơn vị m²)" title="Diện tích căn phòng"
                                value="<?php echo htmlspecialchars($room['dienTich']); ?>">
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="col-xs-12">
                            <h4>Giá điện <span>*</span> <span class="error_input"
                                    id="error_input_electric_price"></span></h4>
                        </div>
                        <div class="col-xs-12">
                            <input id="input_electric_price" type="text" name="dien" mi n="0"
                                placeholder="Giá điện(đơn vị kWh)" title="Giá điện sử dụng"
                                value="<?php echo htmlspecialchars($room['dien']); ?>">
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="col-xs-12">
                            <h4>Giá nước <span>*</span> <span class="error_input" id="error_input_water_price"></span>
                            </h4>
                        </div>
                        <div class="col-xs-12">
                            <input id="input_water_price" type="text" name="nuoc" min="0"
                                placeholder="Giá nước(đơn vị m³)" title="Giá nước sử dụng"
                                value="<?php echo htmlspecialchars($room['nuoc']); ?>">
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="col-xs-12">
                            <h4>Đối tượng cho thuê <span>*</span> <span class="error_input"
                                    id="error_input_room_person"></span></h4>
                        </div>
                        <div class="col-xs-12">
                            <select id="input_room_person" name="doiTuong" title="Đối tượng cho thuê">
                                <option value="Tất cả" <?php if ($room['doiTuong'] == 'Tất cả')
                                    echo 'selected'; ?>>Tất cả
                                </option>
                                <option value="Nam" <?php if ($room['doiTuong'] == 'Nam')
                                    echo 'selected'; ?>>Nam</option>
                                <option value="Nữ" <?php if ($room['doiTuong'] == 'Nữ')
                                    echo 'selected'; ?>>Nữ</option>
                                <option value="Sinh viên" <?php if ($room['doiTuong'] == 'Sinh viên')
                                    echo 'selected'; ?>>
                                    Sinh viên</option>
                                <option value="Nhân viên văn phòng" <?php if ($room['doiTuong'] == 'Nhân viên văn phòng')
                                    echo 'selected'; ?>>Nhân viên văn phòng</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="col-xs-12">
                            <h4>Tiện ích</h4>
                        </div>
                        <div class="col-xs-12">
                            <textarea id="input_room_utilities" name="tienIch" rows="5" style="width: 100%;"
                                title="Tiện ích căn phòng">
                        </textarea>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="col-xs-12">
                            <h4>Mô tả thêm</h4>
                        </div>
                        <div class="col-xs-12">
                            <textarea id="input_room_description" name="moTa" rows="5" style="width: 100%;"
                                title="Mô tả căn phòng"><?php echo htmlspecialchars($room['moTa']); ?></textarea>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="col-xs-12">
                            <h4>Địa chỉ</h4>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xs-12">
                        <div class="col-xs-12">
                            <h4>Tỉnh thành <span>*</span></h4>
                        </div>
                        <div class="col-xs-12">



                            <select id="tinh" name="maTinh">
                                <option value="">Chọn tỉnh/thành</option>
                            </select>
                            <input type="hidden" name="tinhThanh" id="tenTinhHidden">












                        </div>
                    </div>

                    <div class="col-sm-6 col-xs-12">
                        <div class="col-xs-12">
                            <h4>Quận/ huyện <span>*</span></h4>
                        </div>
                        <div class="col-xs-12">

                            <select id="quan" name="quanHuyen">
                                <option value="">Chọn quận/huyện</option>
                            </select>




                        </div>
                    </div>

                    <script>
                        const initialTinhName = "<?php echo htmlspecialchars($room['tinhThanh'] ?? ''); ?>";
                        const initialQuanName = "<?php echo htmlspecialchars($room['quanHuyen'] ?? ''); ?>";
                    </script>
                    <script type="text/javascript" src="address.js"></script>

                    <div class="col-xs-12" style="margin-top: 10px;">
                        <div class="col-xs-12">
                            <h4>Địa chỉ cụ thể <span>*</span> <span class="error_input" id="error_input_address"></span>
                            </h4>
                        </div>
                        <div class="col-xs-12">
                            <input id="input_address" type="text" name="diaChi" placeholder="Địa chỉ căn phòng"
                                title="Địa chỉ căn phòng" value="<?php echo htmlspecialchars($room['diaChi']); ?>">
                        </div>
                    </div>


                    <div class="col-xs-12" style="margin-top: 10px;">
                        <div class="col-xs-12">
                            <h4>Thông Tin Liên Hệ <span>*</span> <span class="error_input"
                                    id="error_input_contact_info"></span></h4>
                        </div>
                        <!-- <div class="col-xs-12"> -->
                        <div class="col-xs-6 text-left">
                            <div class="col-xs-12 ">
                                <h4>Tên chủ trọ <span>*</span> <span class="error_input"
                                        id="error_input_contact_info"></span></h4>
                            </div>
                            <div class="col-xs-12 text-left">
                                <input id="input_contact_name" type="text" name="chuTro" placeholder="Tên liên hệ"
                                    title="Tên liên hệ" value="<?php echo htmlspecialchars($room['chuTro']); ?>">
                            </div>
                        </div>

                        <div class="col-xs-6 ">
                            <div class="col-xs-12">
                                <h4>Số điện thoại <span>*</span> <span class="error_input"
                                        id="error_input_contact_info"></span></h4>
                            </div>
                            <div class="col-xs-12 text-right ">
                                <input id="input_contact_phone" type="text" name="sdt" placeholder="Số điện thoại"
                                    title="Số điện thoại" value="<?php echo htmlspecialchars($room['sdt']); ?>">
                            </div>

                        </div>
                        <!-- </div> -->
                    </div>
                    <div class=" col-xs-12 " style="margin-block-start:30px;">
                        <div class="col-lg-6 col-xs-12  text-left">
                            <button type="submit" name="update" class="btn btn-primary"
                                style="background-color: rgb(175, 0, 0);">Cập nhật</button>
                        </div>
                        <div class="col-lg-6 col-xs-12  text-right">
                            <button type="submit" name="delete" class="btn btn-primary"
                                style="background-color: rgb(175, 0, 0);">Xóa</button>
                        </div>
                    </div>
                </form>



            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <h3>Hình ảnh phòng trọ</h3>

            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <?php
                    $sql2 = 'SELECT DuongDan FROM hinh_anh_phong_tro WHERE IDPhongTro = ?';
                    $stmt2 = $conn->prepare($sql2);
                    $stmt2->bind_param('i', $id_tro);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();

                    $images = [];
                    while ($row = $result2->fetch_assoc()) {
                        $images[] = $row['DuongDan'];
                    }

                    $num_of_images = count($images);
                    for ($i = 0; $i < $num_of_images; $i++) {
                        if ($i == 0) {
                            echo '<li data-target="#myCarousel" data-slide-to="0" class="active"></li>';
                        } else {
                            echo '<li data-target="#myCarousel" data-slide-to="' . $i . '"></li>';
                        }
                    }
                    ?>
                </ol>

                <div class="carousel-inner">
                    <?php
                    $count = 0;
                    foreach ($images as $Image) {
                        $Image = trim($Image);
                        if (!empty($Image)) {
                            if ($count == 0) {
                                echo '<div class="item active">
                                        <img src="' . $Image . '" alt="Hình ảnh phòng trọ">
                                    </div>';
                            } else {
                                echo '<div class="item">
                                        <img src="' . $Image . '" alt="Hình ảnh phòng trọ">
                                    </div>';
                            }
                            $count++;
                        }
                    }
                    ?>
                </div>

                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <form method="POST" action="post_mana_edit.php?id=<?php echo $id_tro; ?>" enctype="multipart/form-data">
                <div class="row" style="margin-top: 20px;">
                    <div class="form-group col-xs-12">
                        <label>Thêm ảnh mới:</label>


                        <!-- <input type="file" class="form-control" name="image" id="upload_images"
                            onchange="previewImages()" multiple="multiple"> -->

                        <input type="file" class="form-control" name="image[]" id="upload_images"
                            onchange="previewImages()" multiple="multiple">


                        <div class="col-xs-12">
                            <div class="preview_images  col-xs-12" id="preview_images"
                                style="margin: 15px 0px; padding: 0px;"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left">
                        <button type="submit" class="btn btn-success" name="themanh"
                            style="background-color: rgb(175, 0, 0);">Thêm ảnh</button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                        <button type="submit" class="btn btn-danger" name="xoaanh"
                            style="background-color: rgb(175, 0, 0);">Xóa tất cả</button>
                    </div>
                </div>
                <div class="col-row">
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info" style="margin-top: 20px;">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </form>

        </div>
    </div>
    </div>

    <?php
    include('footer.php');
    ?>

    <script type="text/javascript" src="scripts/JSDangTin.js"></script>
    <script type="text/javascript" src="scripts/JavaScript.js"></script>

</body>

</html>