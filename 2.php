<?php
session_start();
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title id="title_room_page">Quản Lý Phòng</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="styles/CSS.css">
    <style type="text/css">
        button {
            margin: 0px 10px;
        }

        .search-box {

            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .search-btn {
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <?php include('header.php'); ?>

    <div class="container" style="margin-top: 20px; margin-bottom: 20px;">
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <h2 style="color:green">Các căn phòng đã đăng</h2>

            <div class="search-box">
                <form method="get" action="" class="form-inline">
                    <div class="form-group">
                        <input type="text" class="form-control" name="search" placeholder="Nhập ID hoặc tiêu đề"
                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary search-btn">Tìm kiếm</button>
                    <a href="?" class="btn btn-default">Xóa tìm kiếm</a>
                </form>
            </div>

            <div style="height: 20px"></div>

            <?php
            $username = $_SESSION['user_name'];
            $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

            $sql1 = 'SELECT * FROM phong_tro WHERE user_name = ?';
            $params = array($username);
            $types = 's';

            // Thêm điều kiện tìm kiếm nếu có
            if (!empty($search_term)) {
                if (is_numeric($search_term)) {
                    // Tìm theo ID nếu search_term là số
                    $sql1 .= ' AND IDPhongTro = ?';
                    $params[] = (int) $search_term;
                    $types .= 'i';
                } else {
                    // Tìm theo tiêu đề nếu search_term là chuỗi
                    $sql1 .= ' AND TieuDe LIKE ?';
                    $params[] = '%' . $search_term . '%';
                    $types .= 's';
                }
            }

            $sql1 .= ' ORDER BY ThoiGianDang DESC';

            $stmt1 = $conn->prepare($sql1);

            if ($stmt1) {
                // Bind parameters động
                $stmt1->bind_param($types, ...$params);
                $stmt1->execute();
                $result1 = $stmt1->get_result();

                if ($result1 && $result1->num_rows > 0) {
                    while ($row1 = $result1->fetch_assoc()) {
                        $row = $row1; ?>
                        <div class="room-wrapper" style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div class="room-info" style="flex-grow: 1;">
                                <?php include "room.php"; ?>
                            </div>
                            <div class="room-manage" style="padding-left: 15px;">
                                <a href="post_mana_edit.php?id=<?php echo $row['IDPhongTro']; ?>" class="btn btn-primary"
                                    style="background-color: rgb(175, 0, 0);">
                                    Quản lý chi tiết
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='alert alert-info'>Không tìm thấy phòng trọ nào";
                    if (!empty($search_term)) {
                        echo " phù hợp với từ khóa '<strong>" . htmlspecialchars($search_term) . "</strong>'";
                    }
                    echo ".</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Lỗi chuẩn bị truy vấn: " . $conn->error . "</div>";
            }
            ?>
        </div>
    </div>

    <?php include('footer.php'); ?>

</body>

</html>