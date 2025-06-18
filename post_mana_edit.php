<?php
session_start();

if (isset($_GET['id'])) {
    $IDPhongTro = $_GET['id'];
} else {
    echo "ID phòng trọ không được cung cấp!";
    exit();
}

// Kết nối đến cơ sở dữ liệu
include('config.php');

// Lấy thông tin phòng trọ từ cơ sở dữ liệu
$sql = 'SELECT * FROM phong_tro WHERE IDPhongTro = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $IDPhongTro);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $room = $result->fetch_assoc();
} else {
    echo "Phòng trọ không tồn tại!";
    exit();
}
// Xử lý khi người dùng gửi form cập nhật

if (isset($_POST['update'])) {

    $diaChi = mysqli_real_escape_string($conn, $_POST['DiaChi']);
    $quanHuyen = mysqli_real_escape_string($conn, $_POST['quanHuyen']);
    $tinhThanh = mysqli_real_escape_string($conn, $_POST['tinhThanh']);
    $tenChuTro = mysqli_real_escape_string($conn, $_POST['TenChuTro']);
    $sdt = mysqli_real_escape_string($conn, $_POST['Sdt']);
    $tieuDe = mysqli_real_escape_string($conn, $_POST['TieuDe']);
    $idLoaiPhong = mysqli_real_escape_string($conn, $_POST['idLoaiPhong']);
    $veSinh = mysqli_real_escape_string($conn, $_POST['KieuVeSinh']);
    $gia = mysqli_real_escape_string($conn, $_POST['GiaChoThue']);
    $dienTich = mysqli_real_escape_string($conn, $_POST['DienTich']);
    $dien = mysqli_real_escape_string($conn, $_POST['GiaDien']);
    $nuoc = mysqli_real_escape_string($conn, $_POST['GiaNuoc']);
    $doiTuong = mysqli_real_escape_string($conn, $_POST['DoiTuong']);
    $tienIch = mysqli_real_escape_string($conn, $_POST['TienIch']);
    $moTa = mysqli_real_escape_string($conn, $_POST['MoTa']);



    $update_sql = "UPDATE phong_tro SET DiaChi = ?, QuanHuyen = ?, TinhThanh = ? , TenChuTro = ?, Sdt = ?,
    TieuDe = ?, idLoaiPhong = ?, KieuVeSinh = ?, GiaChoThue = ?, DienTich = ?, GiaDien = ?, GiaNuoc = ?,
     DoiTuong = ?, TienIch = ?, MoTa = ?, status = 'pending' WHERE IDPhongTro = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param(
        "ssssssisiisssssi",
        $diaChi,
        $quanHuyen,
        $tinhThanh,
        $tenChuTro,
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
        $IDPhongTro
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
    // Bắt đầu transaction
    $conn->begin_transaction();

    // Xóa ảnh phòng trọ từ cơ sở dữ liệu
    $delete2_sql = "DELETE FROM hinh_anh_phong_tro WHERE IDPhongTro = ?";
    $delete2_stmt = $conn->prepare($delete2_sql);
    $delete2_stmt->bind_param("i", $IDPhongTro);

    // Xóa phòng trọ từ cơ sở dữ liệu
    $delete_sql = "DELETE FROM phong_tro WHERE IDPhongTro = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $IDPhongTro);

    if ($delete2_stmt->execute() && $delete_stmt->execute()) {
        // Commit transaction
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
    // Kiểm tra xem ảnh có được tải lên không
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $imagePath = 'uploads/' . basename($image['name']);

        // Di chuyển ảnh tải lên vào thư mục đích
        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            // Lưu đường dẫn ảnh vào cơ sở dữ liệu
            $add_anh_sql = "INSERT INTO hinh_anh_phong_tro (IDPhongTro, DuongDan) VALUES (?, ?)";
            $add_anh_stmt = $conn->prepare($add_anh_sql);
            $add_anh_stmt->bind_param("is", $IDPhongTro, $imagePath);

            if ($add_anh_stmt->execute()) {
                $message = "Ảnh đã được thêm thành công!";
            } else {
                $message = "Lỗi: " . $conn->error;
            }
        } else {
            $message = "Lỗi khi tải lên ảnh.";
        }
    } else {
        $message = "Ảnh chưa được chọn hoặc có lỗi khi tải lên.";
    }
}

// Xóa tất cả ảnh
if (isset($_POST['xoaanh'])) {
    // Kiểm tra xem có ảnh nào không
    $check_anh_sql = "SELECT COUNT(*) AS count FROM hinh_anh_phong_tro WHERE IDPhongTro = ?";
    $check_anh_stmt = $conn->prepare($check_anh_sql);
    $check_anh_stmt->bind_param("i", $IDPhongTro);
    $check_anh_stmt->execute();
    $result = $check_anh_stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // Xóa tất cả ảnh liên quan đến ID phòng trọ
        $delete_anh_sql = "DELETE FROM hinh_anh_phong_tro WHERE IDPhongTro = ?";
        $delete_anh_stmt = $conn->prepare($delete_anh_sql);
        $delete_anh_stmt->bind_param("i", $IDPhongTro);

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
    <!-- <script type="text/javascript" src="vendor/bootstrap.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <link rel="stylesheet" href="vendor/bootstrap.css"> -->
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

    <!-- Phần hiển thị đường dẫn các trang -->
    <div class="container">
        <p id="path">
            <a href="index.php" class="link">Trang chủ / </a>
            <a href="post_mana.php" class="link">Quản lý phòng /</a>
            <a class="link">Quản lý phòng chi tiết</a>
        </p>
    </div>

    <div class="container" style="margin-bottom: 20px; ">
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
            <div class="row">
                <form method="POST" onsubmit="return confirmDelete();"
                    action="post_mana_edit.php?id=<?php echo $IDPhongTro; ?>" enctype="multipart/form-data">
                    <!-- Phần các thông tin cơ bản -->
                    <div class="col-xs-12">
                        <h3>Các thông tin cơ bản</h3>
                    </div>




                    <div class="col-xs-12">
                        <div class="col-xs-12">
                            <h4>Mã tin</h4>
                        </div>
                        <div class="col-xs-12">
                            <input readonly id="id_post" type="text" name="id_post" maxlength="80" style="width: 100%"
                                value="<?php echo htmlspecialchars($room['IDPhongTro']); ?>">
                        </div>
                    </div>



                    <div class="col-xs-12"> <!-- Tiêu đề tin -->
                        <div class="col-xs-12">
                            <h4>Tiêu đề tin <span>*</span> <span class="error_input" id="error_input_title"></span></h4>
                        </div>
                        <div class="col-xs-12">
                            <input id="input_title" type="text" name="TieuDe" maxlength="80" style="width: 100%"
                                placeholder="Hãy đặt tiêu đề đầy đủ ý nghĩa, khách sẽ quan tâm hơn" title="Tiêu đề"
                                value="<?php echo htmlspecialchars($room['TieuDe']); ?>">
                        </div>
                    </div>






                    <div class="col-xs-12">
                        <h4 class="col-xs-12">Chọn loại phòng <span>*</span> <span class="error_input"
                                id="error_input_kind_of_room"></span></h4>
                        <?php
                        $sql_loaiPhong = "SELECT idLoaiPhong, loaiPhong FROM loai_phong";
                        $result_loaiPhong = mysqli_query($conn, $sql_loaiPhong);
                        while ($row = mysqli_fetch_assoc($result_loaiPhong)) {
                            // Kiểm tra nếu loại phòng của phòng trọ hiện tại trùng với loại phòng trong danh sách
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





                    <div class="col-xs-12"> <!-- Kiểu vệ sinh -->
                        <h4 class="col-xs-12">Kiểu vệ sinh <span>*</span> <span class="error_input"
                                id="error_input_kind_of_toilet"></span></h4>
                        <div class="col-sm-6 col-xs-12">
                            <input name="KieuVeSinh" type="radio" value="Khép kín" style="width: 13px;" <?php if ($room['KieuVeSinh'] == 'Khép kín')
                                echo 'checked'; ?> /> Khép kín
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <input name="KieuVeSinh" type="radio" value="Không khép kín" style="width: 13px;" <?php if ($room['KieuVeSinh'] == 'Không khép kín')
                                echo 'checked'; ?> /> Không khép kín
                        </div>
                    </div>

                    <div class="col-xs-6"> <!-- Mức giá cho thuê -->
                        <div class="col-xs-12">
                            <h4>Giá cho thuê <span>*</span> <span class="error_input"
                                    id="error_input_room_price"></span></h4>
                        </div>
                        <div class="col-xs-12">
                            <input id="input_room_price" type="number" name="GiaChoThue" min="0"
                                placeholder="Giá cho thuê(VNĐ)" title="Giá thuê phòng"
                                value="<?php echo htmlspecialchars($room['GiaChoThue']); ?>">
                        </div>
                    </div>

                    <div class="col-xs-6"> <!-- Diện tích căn phòng -->
                        <div class="col-xs-12">
                            <h4>Diện tích <span>*</span> <span class="error_input" id="error_input_room_area"></span>
                            </h4>
                        </div>
                        <div class="col-xs-12">
                            <input id="input_room_area" type="number" name="DienTich" min="0"
                                placeholder="Diện tích(đơn vị m²)" title="Diện tích căn phòng"
                                value="<?php echo htmlspecialchars($room['DienTich']); ?>">
                        </div>
                    </div>

                    <div class="col-xs-6"> <!-- Mức giá cho thuê sử dụng điện-->
                        <div class="col-xs-12">
                            <h4>Giá điện <span>*</span> <span class="error_input"
                                    id="error_input_electric_price"></span></h4>
                        </div>
                        <div class="col-xs-12">
                            <input id="input_electric_price" type="text" name="GiaDien" min="0"
                                placeholder="Giá điện(đơn vị kWh)" title="Giá điện sử dụng"
                                value="<?php echo htmlspecialchars($room['GiaDien']); ?>">
                        </div>
                    </div>

                    <div class="col-xs-6"> <!-- Mức giá cho thuê sử dụng nước-->
                        <div class="col-xs-12">
                            <h4>Giá nước <span>*</span> <span class="error_input" id="error_input_water_price"></span>
                            </h4>
                        </div>
                        <div class="col-xs-12">
                            <input id="input_water_price" type="text" name="GiaNuoc" min="0"
                                placeholder="Giá nước(đơn vị m³)" title="Giá nước sử dụng"
                                value="<?php echo htmlspecialchars($room['GiaNuoc']); ?>">
                        </div>
                    </div>

                    <div class="col-xs-12"> <!-- Chọn đối tượng cho thuê -->
                        <div class="col-xs-12">
                            <h4>Đối tượng cho thuê <span>*</span> <span class="error_input"
                                    id="error_input_room_person"></span></h4>
                        </div>
                        <div class="col-xs-12">
                            <select id="input_room_person" name="DoiTuong" title="Đối tượng cho thuê">
                                <option value="Tất cả" <?php if ($room['DoiTuong'] == 'Tất cả')
                                    echo 'selected'; ?>>Tất cả
                                </option>
                                <option value="Nam" <?php if ($room['DoiTuong'] == 'Nam')
                                    echo 'selected'; ?>>Nam</option>
                                <option value="Nữ" <?php if ($room['DoiTuong'] == 'Nữ')
                                    echo 'selected'; ?>>Nữ</option>
                                <option value="Sinh viên" <?php if ($room['DoiTuong'] == 'Sinh viên')
                                    echo 'selected'; ?>>
                                    Sinh viên</option>
                                <option value="Nhân viên văn phòng" <?php if ($room['DoiTuong'] == 'Nhân viên văn phòng')
                                    echo 'selected'; ?>>Nhân viên văn phòng</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12"> <!-- Chọn tiện ích của căn phòng -->
                        <div class="col-xs-12">
                            <h4>Tiện ích</h4>
                        </div>
                        <div class="col-xs-12">
                            <textarea id="input_room_utilities" name="TienIch" rows="5" style="width: 100%;"
                                title="Tiện ích căn phòng">
                        </textarea>
                        </div>
                    </div>

                    <div class="col-xs-12"> <!-- Mô tả thêm -->
                        <div class="col-xs-12">
                            <h4>Mô tả thêm</h4>
                        </div>
                        <div class="col-xs-12">
                            <textarea id="input_room_description" name="MoTa" rows="5" style="width: 100%;"
                                title="Mô tả căn phòng"><?php echo htmlspecialchars($room['MoTa']); ?></textarea>
                        </div>
                    </div>









                    <!-- Phần địa chỉ -->
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
                            <select id="tinh" name="tinhThanh">
                                <option value=""><?php echo htmlspecialchars($room['TinhThanh']); ?></option>
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
                                <option value=""><?php echo htmlspecialchars($room['QuanHuyen']); ?></option>
                            </select>
                        </div>
                    </div>
                    <script>
                        // Đảm bảo biến $room['TinhThanh'] và $room['QuanHuyen'] có sẵn từ PHP
                        const initialTinhName = "<?php echo htmlspecialchars($room['TinhThanh'] ?? ''); ?>";
                        const initialQuanName = "<?php echo htmlspecialchars($room['QuanHuyen'] ?? ''); ?>";
                    </script>
                    <script type="text/javascript" src="address.js"></script>



                    <div class="col-xs-12" style="margin-top: 10px;"> <!-- Địa chỉ căn phòng -->
                        <div class="col-xs-12">
                            <h4>Địa chỉ cụ thể <span>*</span> <span class="error_input" id="error_input_address"></span>
                            </h4>
                        </div>
                        <div class="col-xs-12">
                            <input id="input_address" type="text" name="DiaChi" placeholder="Địa chỉ căn phòng"
                                title="Địa chỉ căn phòng" value="<?php echo htmlspecialchars($room['DiaChi']); ?>">
                        </div>
                    </div>


                    <div class="col-xs-12" style="margin-top: 10px;"> <!-- Thông tin liên hệ -->
                        <div class="col-xs-12">
                            <h4>Thông Tin Liên Hệ <span>*</span> <span class="error_input"
                                    id="error_input_contact_info"></span></h4>
                        </div>
                        <div class="col-xs-12">
                            <div class="col-xs-6 text-left">
                                <div class="col-xs-12 ">
                                    <h4>Tên chủ trọ <span>*</span> <span class="error_input"
                                            id="error_input_contact_info"></span></h4>
                                </div>
                                <div class="col-xs-12 text-left">
                                    <input id="input_contact_name" type="text" name="TenChuTro"
                                        placeholder="Tên liên hệ" title="Tên liên hệ"
                                        value="<?php echo htmlspecialchars($room['TenChuTro']); ?>">
                                </div>
                            </div>

                            <div class="col-xs-6 ">
                                <div class="col-xs-12">
                                    <h4>Số điện thoại <span>*</span> <span class="error_input"
                                            id="error_input_contact_info"></span></h4>
                                </div>
                                <div class="col-xs-12 text-right ">
                                    <input id="input_contact_phone" type="text" name="Sdt" placeholder="Số điện thoại"
                                        title="Số điện thoại" value="<?php echo htmlspecialchars($room['Sdt']); ?>">
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class=" col-xs-12 " style="margin-block-start:30px;">
                        <div class="col-lg-6 col-xs-12  text-left">
                            <button type="submit" name="update" class="btn btn-primary"
                                style="background-color: rgb(175, 0, 0);">Cập nhật thông tin</button>
                        </div>
                        <div class="col-lg-6 col-xs-12  text-right">
                            <button type="submit" name="delete" class="btn btn-primary"
                                style="background-color: rgb(175, 0, 0);">Xóa phòng</button>
                        </div>
                    </div>
                </form>



            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <h3>Hình ảnh phòng trọ</h3>

            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <?php
                    $sql2 = 'SELECT DuongDan FROM hinh_anh_phong_tro WHERE IDPhongTro = ?';
                    $stmt2 = $conn->prepare($sql2);
                    $stmt2->bind_param('i', $IDPhongTro);
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

                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <?php
                    $count = 0;
                    foreach ($images as $Image) {
                        $Image = trim($Image); // Loại bỏ khoảng trắng thừa (nếu có)
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


                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <form method="POST" action="post_mana_edit.php?id=<?php echo $IDPhongTro; ?>" enctype="multipart/form-data">
                <div class="row" style="margin-top: 20px;">
                    <div class="form-group col-xs-12">
                        <label>Thêm ảnh mới:</label>


                        <input type="file" class="form-control" name="image" id="upload_images"
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
                            style="background-color: rgb(175, 0, 0);">Thêm Ảnh</button>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                        <button type="submit" class="btn btn-danger" name="xoaanh"
                            style="background-color: rgb(175, 0, 0);">Xóa Tất Cả Ảnh</button>
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

    <!-- Phần chân trang -->
    <?php
    include('footer.php');
    ?>


    <script type="text/javascript" src="scripts/JSDangTin.js"></script>
    <script type="text/javascript" src="scripts/JavaScript.js"></script>


    <script type="text/javascript">



    </script>
</body>

</html>